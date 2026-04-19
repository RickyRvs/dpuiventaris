@extends('layouts.app')

@section('content')
<div style="padding:24px;display:flex;flex-direction:column;gap:16px;">
  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:4px;">Manajemen Pengguna</h2>
      <p style="font-size:13px;color:#64748b;">Kelola akun dan hak akses pengguna sistem</p>
    </div>
    <button onclick="openModal('user-modal')" class="btn-or">
      <span class="material-symbols-outlined" style="font-size:16px;">person_add</span> Tambah Pengguna
    </button>
  </div>

  <!-- Stats -->
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;">
    @foreach([['Total User','6','#f97316','#fff7ed','group'],['Admin','1','#2563eb','#eff6ff','admin_panel_settings'],['Operator','5','#16a34a','#f0fdf4','badge'],['Aktif','6','#10b981','#f0fdf4','check_circle']] as [$l,$n,$c,$bg,$ic])
    <div class="card-stat" style="border-left:3px solid {{ $c }};">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">{{ $l }}</span>
        <div style="width:30px;height:30px;background:{{ $bg }};border-radius:8px;display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined" style="color:{{ $c }};font-size:17px;">{{ $ic }}</span></div>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;">{{ $n }}</p>
    </div>
    @endforeach
  </div>

  <!-- User Table -->
  <div class="card" style="overflow:hidden;">
    <table class="tbl" style="width:100%;">
      <thead><tr>
        <th style="padding-left:20px;">Pengguna</th>
        <th>Email</th>
        <th>Peran</th>
        <th>Kantor</th>
        <th>Login Terakhir</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr></thead>
      <tbody>
        @foreach($userList as $u)
        <tr>
          <td style="padding-left:20px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,{{ $u['color1'] }},{{ $u['color2'] }});display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:12px;color:#fff;flex-shrink:0;">{{ $u['initials'] }}</div>
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $u['nama'] }}</div>
                <div style="font-size:11px;color:#94a3b8;">Bergabung {{ $u['bergabung'] }}</div>
              </div>
            </div>
          </td>
          <td style="font-size:12px;color:#64748b;">{{ $u['email'] }}</td>
          <td>
            @if($u['peran'] === 'Admin')
              <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Admin</span>
            @else
              <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Operator</span>
            @endif
          </td>
          <td style="font-size:12px;color:#64748b;">{{ $u['kantor'] }}</td>
          <td style="font-size:12px;color:#94a3b8;">{{ $u['last_login'] }}</td>
          <td>
            <span style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Aktif</span>
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              <button onclick="editUser('{{ $u['nama'] }}')" style="background:#f8fafc;border:1px solid #e2e8f0;color:#64748b;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px;"><span class="material-symbols-outlined" style="font-size:13px;">edit</span> Edit</button>
              @if($u['peran'] !== 'Admin')
              <button onclick="showToast('{{ $u['nama'] }} dinonaktifkan!','warning')" style="background:#fef9c3;border:1px solid #fef08a;color:#854d0e;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px;"><span class="material-symbols-outlined" style="font-size:13px;">block</span></button>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah/Edit User -->
<div class="modal" id="user-modal">
  <div style="background:#fff;border-radius:20px;width:480px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.18);">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;" id="user-modal-title">Tambah Pengguna Baru</h3>
      <button onclick="closeModal('user-modal')" style="border:none;background:#f1f5f9;border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:18px;color:#64748b;">×</button>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Nama Lengkap *</label><input type="text" class="field" id="user-nama" placeholder="Nama pengguna"/></div>
        <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Peran *</label>
          <select class="field"><option>Operator</option><option>Admin</option></select>
        </div>
      </div>
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Email *</label><input type="email" class="field" placeholder="email@dianpilar.co.id"/></div>
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kantor</label>
        <select class="field">@foreach($kantorList as $k)<option>{{ $k['short'] }}</option>@endforeach</select>
      </div>
      <div><label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Password Sementara</label><input type="password" class="field" placeholder="Min. 8 karakter"/></div>
    </div>
    <div style="padding:0 24px 20px;display:flex;gap:10px;">
      <button onclick="closeModal('user-modal')" class="btn-ghost" style="flex:1;">Batal</button>
      <button onclick="closeModal('user-modal');showToast('Pengguna berhasil ditambahkan!')" class="btn-or" style="flex:1;justify-content:center;">Simpan</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
function editUser(nama) {
  document.getElementById('user-modal-title').textContent = 'Edit Pengguna: ' + nama;
  document.getElementById('user-nama').value = nama;
  openModal('user-modal');
}
</script>
@endpush
@endsection
