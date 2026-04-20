<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Aset;
use App\Models\AuditLog;
use App\Models\Jadwal;
use App\Models\Kantor;
use App\Models\Mutasi;
use App\Models\Stok;
use App\Models\User;
use App\Models\RegisterRequest;

class InventarisController extends Controller
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
        string $modul,
        string $icon = 'edit',
        string $bg   = '#fff7ed',
        string $ic   = '#f97316'
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

    private function asetQuery()
    {
        $q = Aset::with('kantor');
        if (!$this->isAdmin() && $this->kantorDbId()) {
            $q->where('kantor_id', $this->kantorDbId());
        }
        return $q;
    }

    private function formatNilai(float $nilai): string
    {
        if ($nilai >= 1_000_000_000) return 'Rp ' . number_format($nilai / 1_000_000_000, 1) . 'M';
        if ($nilai >= 1_000_000)     return 'Rp ' . number_format($nilai / 1_000_000, 0) . 'jt';
        return 'Rp ' . number_format($nilai, 0, ',', '.');
    }

    private function buildLaporanData(): array
    {
        return Kantor::all()->map(function ($kantor) {
            $asets = Aset::where('kantor_id', $kantor->id)->get();
            return [
                'id'    => $kantor->id,
                'nama'  => $kantor->nama,
                'short' => $kantor->short_name,
                'stat'  => [
                    'total'     => $asets->count(),
                    'baik'      => $asets->where('kondisi', 'Baik')->count(),
                    'perbaikan' => $asets->where('kondisi', 'Dalam Perbaikan')->count(),
                    'rusak'     => $asets->where('kondisi', 'Rusak')->count(),
                    'nilai'     => $this->formatNilai($asets->sum('nilai')),
                ],
            ];
        })->values()->toArray();
    }

    // ============================================================
    // AUTH
    // ============================================================

    public function loginForm()
    {
        if (Session::has('user_name')) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $role     = $request->input('role', 'admin');
        $email    = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->with(['kantor', 'kantors'])->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['Email atau kata sandi salah.'])->withInput();
        }
        if ($role === 'admin' && $user->peran !== 'admin') {
            return back()->withErrors(['Akun ini bukan Admin.'])->withInput();
        }
        if ($role === 'operator' && $user->peran !== 'operator') {
            return back()->withErrors(['Akun ini bukan Operator.'])->withInput();
        }

        // ✅ FIX: Validasi kantor dari pivot, bukan kantor_id legacy
        if ($role === 'operator') {
            $kantorKode = $request->input('kantor');
            $kantor     = Kantor::where('kode', $kantorKode)->first();

            if (!$kantor) {
                return back()->withErrors(['Pilih kantor yang valid.'])->withInput();
            }

            // hasKantor() sudah handle pivot + legacy sekaligus
            if (!$user->hasKantor($kantor->id)) {
                return back()->withErrors(['Kantor yang dipilih tidak sesuai dengan akun Anda.'])->withInput();
            }

            // ✅ Set session dari kantor yang benar-benar dipilih user
            Session::put('kantor_db_id', $kantor->id);
            Session::put('kantor_id',    $kantor->kode);
            Session::put('kantor_name',  $kantor->short_name);
        }

        $user->update(['last_login_at' => now()]);

        Session::put('user_id',    $user->id);
        Session::put('user_name',  $user->nama);
        Session::put('user_email', $user->email);
        Session::put('user_role',  $user->peran === 'admin' ? 'Administrator' : 'Operator');
        Session::put('user_type',  $user->peran);

        if ($user->peran === 'admin') {
            Session::put('kantor_db_id', null);
            Session::put('kantor_id',    'all');
        }

        $this->addAuditLog('Login ke sistem', 'Auth', 'login', '#f8fafc', '#94a3b8');
        return redirect()->route('dashboard');
    }

    // ✅ FIX: Baca pivot kantors, bukan fallback ke Kantor::all()
    public function loginCheck(Request $request)
    {
        $user = User::where('email', $request->input('email'))
                    ->with(['kantor', 'kantors'])
                    ->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Email atau kata sandi salah.'], 401);
        }
        if ($user->peran !== 'operator') {
            return response()->json(['error' => 'Akun ini bukan Operator.'], 403);
        }

        // Prioritaskan pivot kantors, fallback ke legacy kantor_id
        $pivotKantors = $user->kantors;
        if ($pivotKantors->isNotEmpty()) {
            $kantors = $pivotKantors->map(fn($k) => [
                'value' => $k->kode,
                'label' => $k->nama,
            ])->values()->toArray();
        } elseif ($user->kantor_id && $user->kantor) {
            $kantors = [[
                'value' => $user->kantor->kode,
                'label' => $user->kantor->nama,
            ]];
        } else {
            return response()->json(['error' => 'Akun belum memiliki kantor yang ditugaskan. Hubungi Admin.'], 403);
        }

        return response()->json([
            'nama'    => $user->nama,
            'kantors' => $kantors,
        ]);
    }

    public function logout()
    {
        $this->addAuditLog('Logout dari sistem', 'Auth', 'logout', '#f8fafc', '#94a3b8');
        Session::flush();
        return redirect()->route('login');
    }

    // ============================================================
    // DASHBOARD
    // ============================================================

    public function dashboard()
    {
        $asetAll = $this->asetQuery()->get();

        $stat = [
            'total'     => $asetAll->count(),
            'baik'      => $asetAll->where('kondisi', 'Baik')->count(),
            'perbaikan' => $asetAll->where('kondisi', 'Dalam Perbaikan')->count(),
            'rusak'     => $asetAll->where('kondisi', 'Rusak')->count(),
            'nilai'     => $this->formatNilai($asetAll->sum('nilai')),
        ];

        $kantorList = Kantor::all()->map(function ($k) {
            $asets = Aset::where('kantor_id', $k->id)->get();
            return [
                'id'    => $k->id,
                'nama'  => $k->nama,
                'short' => $k->short_name,
                'kode'  => $k->kode,
                'stat'  => [
                    'total'     => $asets->count(),
                    'baik'      => $asets->where('kondisi', 'Baik')->count(),
                    'perbaikan' => $asets->where('kondisi', 'Dalam Perbaikan')->count(),
                    'rusak'     => $asets->where('kondisi', 'Rusak')->count(),
                    'nilai'     => $this->formatNilai($asets->sum('nilai')),
                ],
            ];
        })->values()->toArray();

        $kantorLabel = $this->isAdmin()
            ? 'Semua Kantor'
            : Kantor::find($this->kantorDbId())?->nama ?? 'Kantor';

        $stokKritis = Stok::with('kantor')
            ->whereColumn('stok', '<', 'min_stok')
            ->when(!$this->isAdmin(), fn($q) => $q->where('kantor_id', $this->kantorDbId()))
            ->get();

        $aset = $asetAll->take(10)->map(fn($a) => [
            'id'       => $a->id,
            'kode'     => $a->kode,
            'nama'     => $a->nama,
            'kondisi'  => $a->kondisi,
            'kantor'   => $a->kantor?->short_name ?? '-',
            'ruangan'  => $a->ruangan ?? '-',
            'kategori' => $a->kategori,
            'nilai'    => $a->nilai,
        ])->values()->toArray();

        return view('pages.dashboard', [
            'isAdmin'     => $this->isAdmin(),
            'kantorList'  => $kantorList,
            'aset'        => $aset,
            'stat'        => $stat,
            'kantorLabel' => $kantorLabel,
            'stokKritis'  => $stokKritis,
        ]);
    }

    // ============================================================
    // INVENTARIS
    // ============================================================

    public function inventaris(Request $request)
    {
        $query = $this->asetQuery()->latest();

        if ($q = $request->get('q')) {
            $query->where(fn($qb) =>
                $qb->where('nama', 'like', "%$q%")
                   ->orWhere('kode', 'like', "%$q%")
                   ->orWhere('kategori', 'like', "%$q%")
                   ->orWhere('penanggung_jawab', 'like', "%$q%")
            );
        }
        if ($kondisi = $request->get('kondisi')) {
            $query->where('kondisi', $kondisi);
        }
        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }
        if ($kantorFilter = $request->get('kantor')) {
            $kantor = Kantor::where('short_name', $kantorFilter)->first();
            if ($kantor) $query->where('kantor_id', $kantor->id);
        }

        $aset = $query->paginate(20)->withQueryString();

        return view('pages.inventaris', array_merge($this->sharedViewData(), compact('aset')));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'      => 'required|exists:asets,id',
            'kondisi' => 'required|in:Baik,Dalam Perbaikan,Rusak',
        ]);

        $aset = Aset::findOrFail($request->id);
        $old  = $aset->kondisi;
        $aset->update(['kondisi' => $request->kondisi]);

        $this->addAuditLog(
            "Mengubah kondisi aset {$aset->kode} ({$aset->nama}): {$old} → {$request->kondisi}",
            'Inventaris', 'edit', '#fff7ed', '#f97316'
        );

        return redirect()->route('inventaris')
            ->with('success', "Status aset {$aset->kode} berhasil diubah ke {$request->kondisi}.");
    }

    public function deleteAset(Request $request)
    {
        $request->validate(['id' => 'required|exists:asets,id']);

        $aset = Aset::findOrFail($request->id);
        $nama = $aset->nama;
        $kode = $aset->kode;
        $aset->delete();

        $this->addAuditLog("Menghapus aset {$kode}: {$nama}", 'Inventaris', 'delete', '#fef2f2', '#ef4444');

        return redirect()->route('inventaris')
            ->with('success', "Aset {$kode} berhasil dihapus.");
    }

    // ============================================================
    // TAMBAH BARANG
    // ============================================================

    public function tambahBarang()
    {
        return view('pages.tambah-barang', $this->sharedViewData());
    }

    public function storeBarang(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string',
            'kantor_id' => 'required|exists:kantors,id',
            'pj'        => 'required|string|max:255',
            'kondisi'   => 'required|in:Baik,Dalam Perbaikan,Rusak',
        ]);

        $kantor = Kantor::findOrFail($request->kantor_id);
        $prefix = strtoupper($kantor->kode);

        do {
            $kode = $prefix . '-' . rand(6000, 9999);
        } while (Aset::where('kode', $kode)->exists());

        $nilaiRaw = str_replace(['.', ',', ' '], '', $request->nilai ?? '0');

        Aset::create([
            'kode'              => $kode,
            'nama'              => $request->nama,
            'kategori'          => $request->kategori,
            'kantor_id'         => $request->kantor_id,
            'ruangan'           => $request->ruangan,
            'kondisi'           => $request->kondisi,
            'nilai'             => is_numeric($nilaiRaw) ? (float) $nilaiRaw : 0,
            'tanggal_pengadaan' => $request->tanggal ?: null,
            'serial_number'     => $request->sn,
            'penanggung_jawab'  => $request->pj,
            'merek'             => $request->merek,
            'model'             => $request->model,
            'garansi_bulan'     => (int) ($request->garansi ?? 0),
            'garansi_habis'     => $request->garansi_habis ?: null,
            'catatan'           => $request->catatan,
        ]);

        $this->addAuditLog(
            "Menambahkan aset baru: {$request->nama} ({$kode})",
            'Inventaris', 'add_circle', '#f0fdf4', '#16a34a'
        );

        return redirect()->route('inventaris')
            ->with('success', "Aset \"{$request->nama}\" berhasil ditambahkan dengan kode {$kode}.");
    }

    // ============================================================
    // MUTASI
    // ============================================================

    public function mutasi()
    {
        $query = Mutasi::with(['aset', 'kantorAsal', 'kantorTujuan', 'pengaju'])->latest();
        if (!$this->isAdmin()) {
            $query->where('pengaju_id', Session::get('user_id'));
        }
        $mutasiList = $query->get();

        return view('pages.mutasi', array_merge($this->sharedViewData(), [
            'mutasiList' => $mutasiList,
            'asetList'   => $this->asetQuery()->get(),
        ]));
    }

    public function storeMutasi(Request $request)
    {
        $request->validate([
            'aset_id'          => 'required|exists:asets,id',
            'kantor_asal_id'   => 'required|exists:kantors,id',
            'kantor_tujuan_id' => 'required|exists:kantors,id|different:kantor_asal_id',
            'alasan'           => 'required|string|min:5',
        ]);

        $count = Mutasi::count() + 1;
        $kode  = 'MUT-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $aset   = Aset::findOrFail($request->aset_id);
        $tujuan = Kantor::findOrFail($request->kantor_tujuan_id);

        Mutasi::create([
            'kode'             => $kode,
            'aset_id'          => $request->aset_id,
            'kantor_asal_id'   => $request->kantor_asal_id,
            'kantor_tujuan_id' => $request->kantor_tujuan_id,
            'pengaju_id'       => Session::get('user_id'),
            'alasan'           => $request->alasan,
            'status'           => 'Menunggu',
        ]);

        $this->addAuditLog(
            "Mengajukan mutasi aset {$aset->kode} ({$aset->nama}) → {$tujuan->short_name}",
            'Mutasi', 'swap_horiz', '#fff7ed', '#f97316'
        );

        return redirect()->route('mutasi')
            ->with('success', "Pengajuan mutasi {$aset->nama} berhasil dikirim.");
    }

    public function approveMutasi(Request $request)
    {
        $request->validate(['id' => 'required|exists:mutasis,id']);

        $mutasi = Mutasi::with(['aset', 'kantorTujuan'])->findOrFail($request->id);
        $mutasi->update(['status' => 'Disetujui']);
        $mutasi->aset->update(['kantor_id' => $mutasi->kantor_tujuan_id]);

        $this->addAuditLog(
            "Menyetujui mutasi {$mutasi->kode}: {$mutasi->aset->nama} → {$mutasi->kantorTujuan->short_name}",
            'Mutasi', 'check_circle', '#f0fdf4', '#16a34a'
        );

        return redirect()->route('mutasi')
            ->with('success', "Mutasi {$mutasi->kode} disetujui & aset dipindahkan.");
    }

    public function rejectMutasi(Request $request)
    {
        $request->validate(['id' => 'required|exists:mutasis,id']);

        $mutasi = Mutasi::findOrFail($request->id);
        $mutasi->update(['status' => 'Ditolak']);

        $this->addAuditLog("Menolak mutasi {$mutasi->kode}", 'Mutasi', 'cancel', '#fef2f2', '#ef4444');

        return redirect()->route('mutasi')
            ->with('success', "Mutasi {$mutasi->kode} ditolak.");
    }

    // ============================================================
    // JADWAL
    // ============================================================

    public function jadwal()
    {
        $query = Jadwal::with(['aset', 'kantor'])->orderBy('tanggal');
        if (!$this->isAdmin()) {
            $query->where('kantor_id', $this->kantorDbId());
        }
        $jadwalList = $query->get();

        $statJadwal = [
            'terjadwal'    => $jadwalList->where('status', 'Terjadwal')->count(),
            'dalam_proses' => $jadwalList->where('status', 'Dalam Proses')->count(),
            'selesai'      => $jadwalList->where('status', 'Selesai')->count(),
            'terlewat'     => $jadwalList->where('status', 'Terlewat')->count(),
        ];

        return view('pages.jadwal', array_merge($this->sharedViewData(), [
            'jadwalList' => $jadwalList,
            'statJadwal' => $statJadwal,
            'asetList'   => $this->asetQuery()->get(),
        ]));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'aset_id'   => 'required|exists:asets,id',
            'jenis'     => 'required|string',
            'teknisi'   => 'required|string|max:100',
            'tanggal'   => 'required|date',
            'waktu'     => 'required|string',
            'kantor_id' => 'required|exists:kantors,id',
        ]);

        $count = Jadwal::count() + 1;
        $kode  = 'JDW-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        $aset  = Aset::findOrFail($request->aset_id);

        Jadwal::create([
            'kode'      => $kode,
            'aset_id'   => $request->aset_id,
            'kantor_id' => $request->kantor_id,
            'jenis'     => $request->jenis,
            'teknisi'   => $request->teknisi,
            'tanggal'   => $request->tanggal,
            'waktu'     => $request->waktu,
            'catatan'   => $request->catatan,
            'status'    => 'Terjadwal',
        ]);

        $this->addAuditLog(
            "Menambah jadwal pemeliharaan: {$aset->nama} — {$request->jenis}",
            'Jadwal', 'event', '#eff6ff', '#2563eb'
        );

        return redirect()->route('jadwal')
            ->with('success', "Jadwal pemeliharaan {$aset->nama} berhasil ditambahkan.");
    }

    public function mulaiJadwal(Request $request)
    {
        $request->validate(['id' => 'required|exists:jadwals,id']);
        $jadwal = Jadwal::findOrFail($request->id);
        $jadwal->update(['status' => 'Dalam Proses']);
        $this->addAuditLog("Memulai jadwal {$jadwal->kode}: {$jadwal->jenis}", 'Jadwal');
        return redirect()->route('jadwal')->with('success', "Jadwal {$jadwal->kode} dimulai.");
    }

    public function selesaiJadwal(Request $request)
    {
        $request->validate(['id' => 'required|exists:jadwals,id']);
        $jadwal = Jadwal::findOrFail($request->id);
        $jadwal->update(['status' => 'Selesai']);
        $this->addAuditLog("Menandai jadwal {$jadwal->kode} sebagai Selesai", 'Jadwal', 'task_alt', '#f0fdf4', '#16a34a');
        return redirect()->route('jadwal')->with('success', "Jadwal {$jadwal->kode} ditandai selesai.");
    }

    // ============================================================
    // STOK
    // ============================================================

    public function stok()
    {
        $query = Stok::with('kantor');
        if (!$this->isAdmin()) {
            $query->where('kantor_id', $this->kantorDbId());
        }
        $stokList = $query->get();

        $statStok = [
            'total'  => $stokList->count(),
            'aman'   => $stokList->filter(fn($s) => $s->stok >= $s->min_stok && $s->stok > 0)->count(),
            'kritis' => $stokList->filter(fn($s) => $s->stok > 0 && $s->stok < $s->min_stok)->count(),
            'habis'  => $stokList->where('stok', 0)->count(),
        ];

        return view('pages.stok', array_merge($this->sharedViewData(), compact('stokList', 'statStok')));
    }

    public function storeStok(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'satuan'    => 'required|string|max:30',
            'stok'      => 'required|integer|min:0',
            'min_stok'  => 'required|integer|min:0',
            'kategori'  => 'required|string',
            'kantor_id' => 'required|exists:kantors,id',
        ]);

        $count = Stok::count() + 1;
        $kode  = 'STK-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Stok::create([
            'kode'      => $kode,
            'nama'      => $request->nama,
            'satuan'    => $request->satuan,
            'stok'      => $request->stok,
            'min_stok'  => $request->min_stok,
            'kategori'  => $request->kategori,
            'kantor_id' => $request->kantor_id,
        ]);

        $this->addAuditLog(
            "Menambah item stok baru: {$request->nama} ({$kode})",
            'Stok', 'add_circle', '#f0fdf4', '#16a34a'
        );

        return redirect()->route('stok')
            ->with('success', "Item stok \"{$request->nama}\" berhasil ditambahkan.");
    }

    public function updateStok(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:stoks,id',
            'jumlah' => 'required|integer|min:0',
        ]);

        $stok = Stok::findOrFail($request->id);
        $old  = $stok->stok;
        $stok->update(['stok' => $request->jumlah]);

        $this->addAuditLog(
            "Memperbarui stok {$stok->kode} ({$stok->nama}): {$old} → {$request->jumlah} {$stok->satuan}",
            'Stok'
        );

        return redirect()->route('stok')
            ->with('success', "Stok {$stok->nama} berhasil diperbarui menjadi {$request->jumlah} {$stok->satuan}.");
    }

    public function deleteStok(Request $request)
    {
        $request->validate(['id' => 'required|exists:stoks,id']);

        $stok = Stok::findOrFail($request->id);
        $nama = $stok->nama;
        $kode = $stok->kode;
        $stok->delete();

        $this->addAuditLog("Menghapus item stok {$kode}: {$nama}", 'Stok', 'delete', '#fef2f2', '#ef4444');

        return redirect()->route('stok')
            ->with('success', "Item stok {$nama} berhasil dihapus.");
    }

    // ============================================================
    // QR LABEL
    // ============================================================

    public function qrLabel()
    {
        $asetList = $this->asetQuery()->get()->map(fn($a) => [
            'id'       => $a->id,
            'kode'     => $a->kode,
            'nama'     => $a->nama,
            'kategori' => $a->kategori,
            'kantor'   => $a->kantor?->short_name ?? '-',
            'ruangan'  => $a->ruangan ?? '-',
        ])->values()->toArray();

        return view('pages.qr-label', array_merge($this->sharedViewData(), compact('asetList')));
    }

    // ============================================================
    // LAPORAN
    // ============================================================

public function laporan()
    {
        if ($this->isAdmin()) {
            // Admin: semua kantor
            $kantorList = $this->buildLaporanData();
            $totalNilaiRaw = \App\Models\Aset::sum('nilai');
            $kantorName = 'Semua Kantor';
            $asetList = collect(); // admin tidak butuh tabel aset detail
            $kantorDbId = null;
        } else {
            // Operator: hanya kantor yang sedang aktif di session
            $kantorDbId = $this->kantorDbId();
            $kantor = Kantor::find($kantorDbId);
            $kantorName = $kantor?->nama ?? 'Kantor';
 
            $asets = \App\Models\Aset::where('kantor_id', $kantorDbId)->get();
 
            $kantorList = [[
                'id'    => $kantor?->id,
                'nama'  => $kantor?->nama ?? '-',
                'short' => $kantor?->short_name ?? '-',
                'stat'  => [
                    'total'     => $asets->count(),
                    'baik'      => $asets->where('kondisi', 'Baik')->count(),
                    'perbaikan' => $asets->where('kondisi', 'Dalam Perbaikan')->count(),
                    'rusak'     => $asets->where('kondisi', 'Rusak')->count(),
                    'nilai'     => $this->formatNilai($asets->sum('nilai')),
                ],
            ]];
 
            $totalNilaiRaw = $asets->sum('nilai');
            $asetList = \App\Models\Aset::where('kantor_id', $kantorDbId)->latest()->get();
        }
 
        return view('pages.laporan', [
            'isAdmin'      => $this->isAdmin(),
            'kantorList'   => $kantorList,
            'kantorName'   => $kantorName,
            'kantorDbId'   => $kantorDbId ?? null,
            'totalNilaiRaw'=> $totalNilaiRaw,
            'asetList'     => $asetList,
        ]);
    }
 

    public function eksporPdf()
    {
        $kantorList = $this->buildLaporanData();
        $asetList   = $this->asetQuery()->with('kantor')->latest()->get();
        $allAset    = Aset::all();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.laporan-pdf', [
            'kantorList'     => $kantorList,
            'asetList'       => $asetList,
            'totalAset'      => $allAset->count(),
            'totalBaik'      => $allAset->where('kondisi', 'Baik')->count(),
            'totalPerbaikan' => $allAset->where('kondisi', 'Dalam Perbaikan')->count(),
            'totalRusak'     => $allAset->where('kondisi', 'Rusak')->count(),
            'totalNilai'     => $this->formatNilai($allAset->sum('nilai')),
            'generatedBy'    => Session::get('user_name', 'System'),
            'periode'        => 'Semua Data per ' . now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        $this->addAuditLog('Mengekspor laporan format PDF', 'Laporan', 'picture_as_pdf', '#fef2f2', '#dc2626');

        return $pdf->download('Laporan-Inventaris-DPU-' . now()->format('Ymd') . '.pdf');
    }

    public function eksporExcel()
    {
        $this->addAuditLog('Mengekspor laporan format Excel', 'Laporan', 'table_view', '#f0fdf4', '#16a34a');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanExport(
                $this->buildLaporanData(),
                $this->isAdmin(),
                $this->kantorDbId()
            ),
            'Laporan-Inventaris-DPU-' . now()->format('Ymd') . '.xlsx'
        );
    }

    // ============================================================
    // PENGATURAN
    // ============================================================

    public function pengaturan()
    {
        return view('pages.pengaturan', $this->sharedViewData());
    }

    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'nullable|email',
            'password_lama' => 'nullable|string',
            'password_baru' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::find(Session::get('user_id'));
        if (!$user) return back()->withErrors(['Pengguna tidak ditemukan.']);

        $data = ['nama' => $request->nama];
        if ($request->filled('email')) $data['email'] = $request->email;

        if ($request->filled('password_baru') && $request->filled('password_lama')) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->withErrors(['Password lama tidak sesuai.']);
            }
            $data['password'] = Hash::make($request->password_baru);
        }

        $user->update($data);
        Session::put('user_name', $request->nama);
        if ($request->filled('email')) Session::put('user_email', $request->email);

        $this->addAuditLog("Memperbarui profil akun", 'Pengaturan');

        return redirect()->route('pengaturan')->with('success', 'Pengaturan berhasil disimpan.');
    }

    // ============================================================
    // MANAJEMEN USER
    // ============================================================

    // ✅ FIX: Baca kantor dari pivot kantors, bukan kantor_id legacy
    public function manajemenUser()
    {
        $userList = User::with(['kantor', 'kantors'])->latest()->get()->map(fn($u) => [
            'id'         => $u->id,
            'nama'       => $u->nama,
            'email'      => $u->email,
            'peran'      => ucfirst($u->peran),
            'kantor'     => $u->peran === 'admin'
                ? 'Semua Kantor'
                : ($u->kantors->isNotEmpty()
                    ? $u->kantors->pluck('short_name')->join(', ')
                    : ($u->kantor?->short_name ?? '-')),
            'initials'   => $u->initials ?? strtoupper(substr($u->nama, 0, 2)),
            'color1'     => $u->color1 ?? '#f97316',
            'color2'     => $u->color2 ?? '#c2410c',
            'bergabung'  => $u->created_at?->translatedFormat('M Y') ?? '-',
            'last_login' => $u->last_login_at?->translatedFormat('d M Y, H:i') ?? 'Belum pernah',
        ])->toArray();

        $registerRequests = RegisterRequest::with('kantor')->latest()->get();

        return view('pages.manajemen-user', array_merge($this->sharedViewData(), compact('userList', 'registerRequests')));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'peran'     => 'required|in:admin,operator',
            'kantor_id' => 'nullable|exists:kantors,id',
            'password'  => 'nullable|string|min:6',
        ]);

        $parts    = explode(' ', trim($request->nama));
        $initials = strtoupper(
            substr($parts[0], 0, 1) .
            (count($parts) > 1 ? substr(end($parts), 0, 1) : '')
        );

        $colors = [
            ['#f97316','#c2410c'], ['#2563eb','#1d4ed8'],
            ['#16a34a','#15803d'], ['#7c3aed','#6d28d9'],
            ['#dc2626','#b91c1c'], ['#0891b2','#0e7490'],
        ];
        $c = $colors[array_rand($colors)];

        User::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'password'  => Hash::make($request->password ?: 'password123'),
            'peran'     => $request->peran,
            'kantor_id' => $request->kantor_id ?: null,
            'initials'  => $initials ?: 'US',
            'color1'    => $c[0],
            'color2'    => $c[1],
        ]);

        $this->addAuditLog(
            "Menambahkan pengguna baru: {$request->nama}",
            'User', 'person_add', '#f5f3ff', '#7c3aed'
        );

        return redirect()->route('manajemen-user')
            ->with('success', "Pengguna \"{$request->nama}\" berhasil ditambahkan.");
    }

    public function deleteUser(Request $request)
    {
        $request->validate(['id' => 'required|exists:users,id']);
        $user = User::findOrFail($request->id);

        if ($user->id === Session::get('user_id')) {
            return back()->withErrors(['Tidak dapat menghapus akun yang sedang aktif.']);
        }
        if ($user->peran === 'admin' && User::where('peran', 'admin')->count() <= 1) {
            return back()->withErrors(['Tidak dapat menghapus admin terakhir.']);
        }

        $nama = $user->nama;
        $user->delete();

        $this->addAuditLog("Menghapus pengguna: {$nama}", 'User', 'person_remove', '#fef2f2', '#ef4444');

        return redirect()->route('manajemen-user')
            ->with('success', "Pengguna {$nama} berhasil dihapus.");
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['id' => 'required|exists:users,id']);
        $user = User::findOrFail($request->id);
        $user->update(['password' => Hash::make('password123')]);

        $this->addAuditLog(
            "Reset password untuk: {$user->nama}",
            'User', 'lock_reset', '#eff6ff', '#2563eb'
        );

        return redirect()->route('manajemen-user')
            ->with('success', "Password {$user->nama} direset ke 'password123'.");
    }

    // ============================================================
    // AUDIT LOG
    // ============================================================

    public function auditLog()
    {
        $auditLogs = AuditLog::latest()->paginate(30);
        return view('pages.audit-log', array_merge($this->sharedViewData(), compact('auditLogs')));
    }
}