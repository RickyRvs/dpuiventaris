@extends('layouts.app')

@section('content')
<div style="padding:24px;max-width:640px;display:flex;flex-direction:column;gap:16px;">
  <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;">Pengaturan Sistem</h2>

  <!-- Profil -->
  <div class="card" style="padding:20px;">
    <h3 style="font-size:13px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;margin-bottom:16px;"><span class="material-symbols-outlined" style="color:#f97316;font-size:18px;">person</span> Profil Pengguna</h3>
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
      <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Sora',sans-serif;font-weight:800;font-size:18px;flex-shrink:0;">{{ strtoupper(substr(session('user_name','U'),0,2)) }}</div>
      <div style="flex:1;display:flex;flex-direction:column;gap:8px;">
        <input type="text" value="{{ session('user_name','Ahmad Santoso') }}" class="field"/>
        <input type="email" value="{{ session('user_email','admin@dianbangun.co.id') }}" class="field"/>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">Jabatan</label><input type="text" class="field" value="System Administrator"/></div>
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:5px;">No. Telepon</label><input type="text" class="field" value="+62 812 3456 7890"/></div>
    </div>
  </div>

  <!-- Notifikasi -->
  <div class="card" style="padding:20px;">
    <h3 style="font-size:13px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;margin-bottom:14px;"><span class="material-symbols-outlined" style="color:#f97316;font-size:18px;">notifications</span> Preferensi Notifikasi</h3>
    @foreach([['Stok Kritis','Notifikasi saat stok di bawah batas',true],['Jadwal Pemeliharaan','Pengingat 3 hari sebelum jadwal',true],['Mutasi Disetujui','Notifikasi saat mutasi disetujui',false],['Login Baru','Peringatan login dari perangkat baru',true],['Kondisi Aset Berubah','Notifikasi saat kondisi diperbarui',true]] as [$t,$d,$c])
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f8fafc;">
      <div>
        <p style="font-size:13px;font-weight:700;color:#0f172a;">{{ $t }}</p>
        <p style="font-size:11px;color:#94a3b8;margin-top:1px;">{{ $d }}</p>
      </div>
      <button onclick="toggleSwitch(this)" data-on="{{ $c?'1':'0' }}" style="width:44px;height:24px;border-radius:999px;border:none;cursor:pointer;position:relative;background:{{ $c?'linear-gradient(135deg,#f97316,#ea580c)':'#e2e8f0' }};transition:all .2s;flex-shrink:0;">
        <span style="position:absolute;top:3px;{{ $c?'right:3px':'left:3px' }};width:18px;height:18px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.2);transition:all .2s;"></span>
      </button>
    </div>
    @endforeach
  </div>

  <!-- Keamanan -->
  <div class="card" style="padding:20px;">
    <h3 style="font-size:13px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;margin-bottom:14px;"><span class="material-symbols-outlined" style="color:#f97316;font-size:18px;">security</span> Keamanan Akun</h3>
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kata Sandi Lama</label><input type="password" placeholder="••••••••" class="field"/></div>
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kata Sandi Baru</label><input type="password" placeholder="Minimal 8 karakter" class="field"/></div>
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Konfirmasi Kata Sandi</label><input type="password" placeholder="Ulangi kata sandi baru" class="field"/></div>
      <button onclick="showToast('Kata sandi berhasil diperbarui!')" class="btn-or" style="align-self:flex-start;">Perbarui Kata Sandi</button>
    </div>
  </div>

  <!-- Tampilan -->
  <div class="card" style="padding:20px;">
    <h3 style="font-size:13px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:8px;margin-bottom:14px;"><span class="material-symbols-outlined" style="color:#f97316;font-size:18px;">tune</span> Preferensi Tampilan</h3>
    @foreach([['Tampilan Tabel Kompak','Tampilkan lebih banyak baris sekaligus',false],['Auto-refresh Data','Perbarui data setiap 5 menit',true]] as [$t,$d,$c])
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f8fafc;">
      <div><p style="font-size:13px;font-weight:700;color:#0f172a;">{{ $t }}</p><p style="font-size:11px;color:#94a3b8;">{{ $d }}</p></div>
      <button onclick="toggleSwitch(this)" data-on="{{ $c?'1':'0' }}" style="width:44px;height:24px;border-radius:999px;border:none;cursor:pointer;position:relative;background:{{ $c?'linear-gradient(135deg,#f97316,#ea580c)':'#e2e8f0' }};transition:all .2s;flex-shrink:0;">
        <span style="position:absolute;top:3px;{{ $c?'right:3px':'left:3px' }};width:18px;height:18px;background:#fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.2);transition:all .2s;"></span>
      </button>
    </div>
    @endforeach
  </div>

  <button onclick="showToast('Semua pengaturan tersimpan!')" class="btn-or" style="display:flex;align-items:center;justify-content:center;gap:8px;">
    <span class="material-symbols-outlined" style="font-size:17px;">save</span> Simpan Semua Perubahan
  </button>
</div>

@push('scripts')
<script>
function toggleSwitch(btn) {
  const on = btn.dataset.on === '1';
  btn.dataset.on = on ? '0' : '1';
  btn.style.background = !on ? 'linear-gradient(135deg,#f97316,#ea580c)' : '#e2e8f0';
  const dot = btn.querySelector('span');
  dot.style.left = !on ? 'auto' : '3px';
  dot.style.right = !on ? '3px' : 'auto';
}
</script>
@endpush
@endsection
