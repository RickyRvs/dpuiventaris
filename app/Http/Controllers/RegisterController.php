<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\AuditLog;
use App\Models\Kantor;
use App\Models\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    // ============================================================
    // HELPER: Audit Log
    // ============================================================

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

    // ============================================================
    // FORM REGISTRASI (publik)
    // ============================================================

    public function showForm()
    {
        if (Session::has('user_name')) {
            return redirect()->route('dashboard');
        }

        $kantorList = Kantor::all();
        return view('auth.register', compact('kantorList'));
    }

    // ============================================================
    // SUBMIT REGISTRASI
    // ============================================================

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'email'        => [
                'required', 'email',
                'unique:register_requests,email',
                'unique:users,email',
            ],
            'password'     => 'required|string|min:8|confirmed',
            'peran'        => 'required|in:admin,operator',
            'kantor_ids'   => $request->peran === 'operator' ? 'required|array|min:1' : 'nullable|array',
            'kantor_ids.*' => 'exists:kantors,id',
            'alasan'       => 'required|string|min:10|max:500',
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar atau sudah mengajukan registrasi.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'peran.required'     => 'Pilih peran akun.',
            'kantor_ids.required'=> 'Operator wajib memilih minimal 1 kantor.',
            'kantor_ids.min'     => 'Operator wajib memilih minimal 1 kantor.',
            'alasan.required'    => 'Alasan pengajuan wajib diisi.',
            'alasan.min'         => 'Alasan minimal 10 karakter.',
        ]);

        // Generate initials & random color
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

        RegisterRequest::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'peran'      => $request->peran,
            'kantor_id'  => null, // legacy, tidak dipakai lagi
            'kantor_ids' => $request->peran === 'operator' ? $request->kantor_ids : null,
            'alasan'     => $request->alasan,
            'status'     => 'Menunggu',
            'initials'   => $initials ?: 'US',
            'color1'     => $c[0],
            'color2'     => $c[1],
        ]);

        return redirect()->route('register.success')
            ->with('reg_nama',  $request->nama)
            ->with('reg_email', $request->email);
    }

    // ============================================================
    // HALAMAN SUKSES
    // ============================================================

    public function success()
    {
        if (!session('reg_email')) {
            return redirect()->route('register');
        }

        return view('auth.register-success', [
            'reg_nama'  => session('reg_nama'),
            'reg_email' => session('reg_email'),
        ]);
    }

    // ============================================================
    // ADMIN: APPROVE → Buat user baru + attach kantors
    // ============================================================

    public function approve(Request $request)
    {
        $request->validate(['id' => 'required|exists:register_requests,id']);

        $reg = RegisterRequest::with('kantor')->findOrFail($request->id);

        if ($reg->status !== 'Menunggu') {
            return back()->withErrors(['Pengajuan ini sudah diproses.']);
        }

        // Cek duplikat email
        if (User::where('email', $reg->email)->exists()) {
            $reg->update([
                'status'        => 'Ditolak',
                'catatan_admin' => 'Email sudah terdaftar di sistem.',
            ]);
            return back()->with('success', 'Pengajuan ditolak otomatis karena email sudah ada di sistem.');
        }

        // Buat user baru
        $user = User::create([
            'nama'      => $reg->nama,
            'email'     => $reg->email,
            'password'  => $reg->password, // sudah di-hash
            'peran'     => $reg->peran,
            'kantor_id' => null, // pakai pivot
            'initials'  => $reg->initials,
            'color1'    => $reg->color1,
            'color2'    => $reg->color2,
        ]);

        // ✅ Attach kantors ke pivot table user_kantors
        if ($reg->peran === 'operator' && !empty($reg->kantor_ids)) {
            $user->kantors()->attach($reg->kantor_ids);
        }

        // Update status pengajuan
        $reg->update([
            'status'      => 'Disetujui',
            'approved_at' => now(),
            'approved_by' => Session::get('user_name', 'Admin'),
        ]);

        $this->addAuditLog(
            "Menyetujui registrasi: {$reg->nama} ({$reg->email})",
            'User', 'how_to_reg', '#f0fdf4', '#16a34a'
        );

        return back()->with('success', "✓ Akun \"{$reg->nama}\" berhasil dibuat. User dapat login sekarang.");
    }

    // ============================================================
    // ADMIN: REJECT
    // ============================================================

    public function reject(Request $request)
    {
        $request->validate([
            'id'            => 'required|exists:register_requests,id',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $reg = RegisterRequest::findOrFail($request->id);

        if ($reg->status !== 'Menunggu') {
            return back()->withErrors(['Pengajuan ini sudah diproses.']);
        }

        $reg->update([
            'status'        => 'Ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        $this->addAuditLog(
            "Menolak registrasi: {$reg->nama} ({$reg->email})",
            'User', 'person_remove', '#fef2f2', '#ef4444'
        );

        return back()->with('success', "Pengajuan \"{$reg->nama}\" berhasil ditolak.");
    }
}