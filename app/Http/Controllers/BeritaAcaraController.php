<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Aset;
use App\Models\AuditLog;
use App\Models\BeritaAcara;
use App\Models\Kantor;
use Carbon\Carbon;

class BeritaAcaraController extends Controller
{
    // ============================================================
    // HELPERS
    // ============================================================

    private function isAdmin(): bool
    {
        return Session::get('user_type') === 'admin';
    }

    private function kantorDbId(): ?int
    {
        return Session::get('kantor_db_id');
    }

    private function sharedViewData(): array
    {
        return [
            'isAdmin'    => $this->isAdmin(),
            'kantorList' => Kantor::all(),
        ];
    }

    private function addAuditLog(
        string $aksi,
        string $modul  = 'Berita Acara',
        string $icon   = 'description',
        string $bg     = '#f5f3ff',
        string $ic     = '#7c3aed'
    ): void {
        AuditLog::create([
            'user_name' => Session::get('user_name', 'System'),
            'aksi'      => $aksi,
            'modul'     => $modul,
            'icon'      => $icon,
            'bg'        => $bg,
            'ic'        => $ic,
        ]);
    }

    private function baQuery()
    {
        $q = BeritaAcara::with(['asets', 'kantor'])->latest();
        if (!$this->isAdmin() && $this->kantorDbId()) {
            $q->where('kantor_id', $this->kantorDbId());
        }
        return $q;
    }

    // ============================================================
    // INDEX
    // ============================================================

    public function index(Request $request)
    {
        $query = $this->baQuery();

        if ($search = $request->get('q')) {
            $query->where(fn($qb) =>
                $qb->where('nomor', 'like', "%$search%")
                   ->orWhere('aset_nama', 'like', "%$search%")
                   ->orWhere('pihak_kedua_nama', 'like', "%$search%")
                   ->orWhere('pihak_pertama_nama', 'like', "%$search%")
            );
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $list = $query->paginate(20)->withQueryString();

        $asetList = Aset::with('kantor')
            ->when(!$this->isAdmin(), fn($q) => $q->where('kantor_id', $this->kantorDbId()))
            ->get();

        $allQ  = $this->baQuery()->get();
        $stats = [
            'total'           => $allQ->count(),
            'draft'           => $allQ->where('status', 'draft')->count(),
            'menunggu_upload' => $allQ->whereIn('status', ['template_downloaded', 'menunggu_upload'])->count(),
            'selesai'         => $allQ->where('status', 'selesai')->count(),
        ];

        return view('pages.berita-acara', array_merge($this->sharedViewData(), [
            'list'     => $list,
            'asetList' => $asetList,
            'stats'    => $stats,
        ]));
    }

    // ============================================================
    // STORE — Buat Berita Acara (multi-aset)
    // ============================================================

    public function store(Request $request)
    {
        $request->validate([
            'aset_ids'             => 'required|array|min:1',
            'aset_ids.*'           => 'exists:asets,id',
            'pihak_pertama_nama'   => 'required|string|max:150',
            'pihak_pertama_jabatan'=> 'required|string|max:150',
            'pihak_kedua_nama'     => 'required|string|max:150',
            'pihak_kedua_jabatan'  => 'required|string|max:150',
            'tanggal_serah_terima' => 'required|date',
            'keterangan'           => 'nullable|string|max:1000',
        ]);

        // Ambil semua aset yang dipilih
        $asets     = Aset::with('kantor')->whereIn('id', $request->aset_ids)->get();
        $asetPertama = $asets->first();

        // Buat record BA — snapshot dari aset PERTAMA untuk label ringkas di tabel
        $ba = BeritaAcara::create([
            'nomor'                => BeritaAcara::generateNomor(),
            'aset_id'              => $asetPertama->id,
            'kantor_id'            => $asetPertama->kantor_id,
            'pihak_pertama_nama'   => $request->pihak_pertama_nama,
            'pihak_pertama_jabatan'=> $request->pihak_pertama_jabatan,
            'pihak_kedua_nama'     => $request->pihak_kedua_nama,
            'pihak_kedua_jabatan'  => $request->pihak_kedua_jabatan,
            'tanggal_serah_terima' => $request->tanggal_serah_terima,
            'keterangan'           => $request->keterangan,
            // Snapshot aset pertama (untuk ringkasan di tabel list)
            'aset_nama'            => $asets->count() > 1
                                        ? $asetPertama->nama . ' (+' . ($asets->count() - 1) . ' lainnya)'
                                        : $asetPertama->nama,
            'aset_kode'            => $asetPertama->kode,
            'aset_kategori'        => $asetPertama->kategori,
            'aset_kondisi'         => $asetPertama->kondisi,
            'aset_nilai'           => $asets->sum('nilai'),
            'status'               => 'draft',
            'dibuat_oleh'          => Session::get('user_name', 'System'),
        ]);

        // Attach semua aset ke pivot dengan snapshot masing-masing
        foreach ($asets as $aset) {
            $ba->asets()->attach($aset->id, [
                'aset_nama'     => $aset->nama,
                'aset_kode'     => $aset->kode,
                'aset_kategori' => $aset->kategori,
                'aset_kondisi'  => $aset->kondisi,
                'aset_nilai'    => $aset->nilai ?? 0,
            ]);
        }

        $namaAsets = $asets->pluck('nama')->join(', ');
        $this->addAuditLog(
            "Membuat berita acara {$ba->nomor} untuk {$asets->count()} aset: {$namaAsets}"
        );

        return redirect()->route('berita-acara')
            ->with('success', "Berita Acara {$ba->nomor} berhasil dibuat untuk {$asets->count()} aset. Silakan unduh template.");
    }

    // ============================================================
    // DOWNLOAD TEMPLATE PDF
    // ============================================================

    public function downloadTemplate(int $id)
    {
        $ba = BeritaAcara::with(['asets', 'kantor'])->findOrFail($id);

        if ($ba->status === 'draft') {
            $ba->update(['status' => 'template_downloaded']);
        }

        $this->addAuditLog("Mengunduh template berita acara {$ba->nomor}");

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.berita-acara-pdf', [
            'ba' => $ba,
        ])->setPaper('a4', 'portrait');

        $filename = 'BA-' . str_replace('/', '-', $ba->nomor) . '.pdf';

        return $pdf->download($filename);
    }

    // ============================================================
    // UPLOAD DOKUMEN TTD
    // ============================================================

    public function upload(Request $request)
    {
        $request->validate([
            'id'      => 'required|exists:berita_acaras,id',
            'dokumen' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $ba = BeritaAcara::findOrFail($request->id);

        if ($ba->dokumen_signed_path && Storage::disk('public')->exists($ba->dokumen_signed_path)) {
            Storage::disk('public')->delete($ba->dokumen_signed_path);
        }

        $file = $request->file('dokumen');
        $ext  = $file->getClientOriginalExtension();
        $path = $file->storeAs(
            'berita-acara',
            $ba->nomor . '_signed_' . now()->format('YmdHis') . '.' . $ext,
            'public'
        );

        $ba->update([
            'dokumen_signed_path' => $path,
            'dokumen_signed_nama' => $file->getClientOriginalName(),
            'status'              => 'selesai',
            'uploaded_at'         => now(),
        ]);

        $this->addAuditLog(
            "Mengupload dokumen TTD berita acara {$ba->nomor}",
            'Berita Acara', 'upload_file', '#f0fdf4', '#16a34a'
        );

        return redirect()->route('berita-acara')
            ->with('success', "Dokumen berita acara {$ba->nomor} berhasil diupload dan ditandai Selesai.");
    }

    // ============================================================
    // VIEW DOKUMEN TTD
    // ============================================================

    public function viewDokumen(int $id)
    {
        $ba = BeritaAcara::findOrFail($id);

        if (!$ba->dokumen_signed_path || !Storage::disk('public')->exists($ba->dokumen_signed_path)) {
            return back()->withErrors(['Dokumen TTD belum diupload atau file tidak ditemukan.']);
        }

        return Storage::disk('public')->download(
            $ba->dokumen_signed_path,
            $ba->dokumen_signed_nama ?? 'dokumen-ttd.pdf'
        );
    }

    // ============================================================
    // DELETE
    // ============================================================

    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|exists:berita_acaras,id']);

        $ba = BeritaAcara::findOrFail($request->id);

        if ($ba->dokumen_signed_path && Storage::disk('public')->exists($ba->dokumen_signed_path)) {
            Storage::disk('public')->delete($ba->dokumen_signed_path);
        }

        $nomor = $ba->nomor;
        $ba->delete(); // pivot otomatis terhapus karena cascade

        $this->addAuditLog(
            "Menghapus berita acara {$nomor}",
            'Berita Acara', 'delete', '#fef2f2', '#ef4444'
        );

        return redirect()->route('berita-acara')
            ->with('success', "Berita Acara {$nomor} berhasil dihapus.");
    }

    // ============================================================
    // DETAIL (JSON untuk modal)
    // ============================================================

    public function detail(int $id)
    {
        $ba = BeritaAcara::with(['asets', 'kantor'])->findOrFail($id);

        // Daftar aset dari pivot (snapshot)
        $asetList = $ba->asets->map(fn($a) => [
            'nama'     => $a->pivot->aset_nama     ?? $a->nama,
            'kode'     => $a->pivot->aset_kode     ?? $a->kode,
            'kategori' => $a->pivot->aset_kategori ?? $a->kategori,
            'kondisi'  => $a->pivot->aset_kondisi  ?? $a->kondisi,
            'nilai'    => 'Rp ' . number_format($a->pivot->aset_nilai ?? 0, 0, ',', '.'),
        ])->values()->toArray();

        return response()->json([
            'id'                   => $ba->id,
            'nomor'                => $ba->nomor,
            'status'               => $ba->status,
            'status_label'         => $ba->status_label,
            'status_color'         => $ba->status_color,
            'status_bg'            => $ba->status_bg,
            'pihak_pertama_nama'   => $ba->pihak_pertama_nama,
            'pihak_pertama_jabatan'=> $ba->pihak_pertama_jabatan,
            'pihak_kedua_nama'     => $ba->pihak_kedua_nama,
            'pihak_kedua_jabatan'  => $ba->pihak_kedua_jabatan,
            'tanggal_serah_terima' => $ba->tanggal_serah_terima
                ? Carbon::parse($ba->tanggal_serah_terima)->translatedFormat('d F Y')
                : '-',
            'aset_list'            => $asetList,
            'aset_count'           => $ba->asets->count(),
            'total_nilai'          => $ba->total_nilai_format,
            'kantor'               => $ba->kantor?->short_name ?? '-',
            'keterangan'           => $ba->keterangan ?? '-',
            'dibuat_oleh'          => $ba->dibuat_oleh ?? '-',
            'created_at'           => $ba->created_at?->translatedFormat('d F Y, H:i') ?? '-',
            'uploaded_at'          => $ba->uploaded_at?->translatedFormat('d F Y, H:i') ?? null,
            'dokumen_signed_nama'  => $ba->dokumen_signed_nama ?? null,
            'has_dokumen'          => !empty($ba->dokumen_signed_path),
        ]);
    }
}