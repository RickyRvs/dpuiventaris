@extends('layouts.app')

@section('content')
<div style="padding:24px;display:flex;flex-direction:column;gap:16px;">

  <!-- ══ HEADER ══ -->
  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:4px;">Manajemen Pengguna</h2>
      <p style="font-size:13px;color:#64748b;">Kelola akun, hak akses, dan pengajuan registrasi</p>
    </div>
    <button onclick="openModal('user-modal')" class="btn-or">
      <span class="material-symbols-outlined" style="font-size:16px;">person_add</span> Tambah Pengguna
    </button>
  </div>

  <!-- ══ STATS ══ -->
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;">
    @php
      $totalUser   = count($userList);
      $totalAdmin  = collect($userList)->where('peran','Admin')->count();
      $totalOp     = collect($userList)->where('peran','Operator')->count();
      $totalReq    = isset($registerRequests) ? $registerRequests->where('status','Menunggu')->count() : 0;
    @endphp
    @foreach([
      ['Total User',   $totalUser,  '#f97316','#fff7ed','group'],
      ['Admin',        $totalAdmin, '#2563eb','#eff6ff','admin_panel_settings'],
      ['Operator',     $totalOp,    '#16a34a','#f0fdf4','badge'],
      ['Menunggu ACC', $totalReq,   '#f59e0b','#fffbeb','hourglass_top'],
    ] as [$l,$n,$c,$bg,$ic])
    <div class="card-stat" style="border-left:3px solid {{ $c }};">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">{{ $l }}</span>
        <div style="width:30px;height:30px;background:{{ $bg }};border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:{{ $c }};font-size:17px;">{{ $ic }}</span>
        </div>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;">{{ $n }}</p>
    </div>
    @endforeach
  </div>

  <!-- ══ PENGAJUAN REGISTRASI ══ -->
  @if(isset($registerRequests) && $registerRequests->count() > 0)
  <div class="card" style="overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:#fffbeb;border-radius:9px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:#f59e0b;font-size:17px;">how_to_reg</span>
        </div>
        <div>
          <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:14px;color:#0f172a;">Pengajuan Registrasi</div>
          <div style="font-size:11px;color:#94a3b8;">Akun baru yang menunggu persetujuan</div>
        </div>
      </div>
      @if($totalReq > 0)
      <span style="background:#f59e0b;color:#fff;font-size:10px;font-weight:800;padding:2px 10px;border-radius:999px;">{{ $totalReq }} Menunggu</span>
      @endif
    </div>

    <table class="tbl" style="width:100%;">
      <thead><tr>
        <th style="padding-left:20px;">Pemohon</th>
        <th>Email</th>
        <th>Peran</th>
        <th>Kantor</th>
        <th>Alasan</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr></thead>
      <tbody>
        @foreach($registerRequests as $reg)
        <tr style="{{ $reg->status === 'Menunggu' ? 'background:#fffbeb08;' : '' }}">
          <!-- Pemohon -->
          <td style="padding-left:20px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,{{ $reg->color1 }},{{ $reg->color2 }});display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:12px;color:#fff;flex-shrink:0;">{{ $reg->initials }}</div>
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $reg->nama }}</div>
                <div style="font-size:11px;color:#94a3b8;">{{ $reg->created_at->diffForHumans() }}</div>
              </div>
            </div>
          </td>
          <!-- Email -->
          <td style="font-size:12px;color:#64748b;">{{ $reg->email }}</td>
          <!-- Peran -->
          <td>
            @if($reg->peran === 'admin')
              <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Admin</span>
            @else
              <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Operator</span>
            @endif
          </td>
          <!-- Kantor -->
          <td style="font-size:12px;color:#64748b;">
            {{ $reg->peran === 'admin' ? 'Semua Kantor' : ($reg->kantor?->short_name ?? '-') }}
          </td>
          <!-- Alasan -->
          <td style="font-size:12px;color:#64748b;max-width:180px;">
            <span title="{{ $reg->alasan }}" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
              {{ $reg->alasan }}
            </span>
          </td>
          <!-- Tanggal -->
          <td style="font-size:11px;color:#94a3b8;white-space:nowrap;">
            {{ $reg->created_at->format('d M Y') }}<br/>
            <span style="font-size:10px;">{{ $reg->created_at->format('H:i') }}</span>
          </td>
          <!-- Status -->
          <td>
            @if($reg->status === 'Menunggu')
              <span style="background:#fffbeb;color:#92400e;border:1px solid #fde68a;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Menunggu</span>
            @elseif($reg->status === 'Disetujui')
              <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Disetujui</span>
            @else
              <span style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Ditolak</span>
            @endif
          </td>
          <!-- Aksi -->
          <td>
            @if($reg->status === 'Menunggu')
            <div style="display:flex;gap:6px;">
              <!-- Approve -->
              <form method="POST" action="{{ route('register-requests.approve') }}" onsubmit="return confirm('Setujui dan buat akun untuk {{ $reg->nama }}?')">
                @csrf
                <input type="hidden" name="id" value="{{ $reg->id }}"/>
                <button type="submit" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:700;display:flex;align-items:center;gap:4px;">
                  <span class="material-symbols-outlined fill-icon" style="font-size:13px;">check_circle</span> ACC
                </button>
              </form>
              <!-- Reject -->
              <button onclick="openRejectModal({{ $reg->id }}, '{{ addslashes($reg->nama) }}')"
                style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:700;display:flex;align-items:center;gap:4px;">
                <span class="material-symbols-outlined fill-icon" style="font-size:13px;">cancel</span> Tolak
              </button>
            </div>
            @elseif($reg->status === 'Disetujui')
            <span style="font-size:11px;color:#16a34a;font-weight:600;">
              <span class="material-symbols-outlined fill-icon" style="font-size:13px;vertical-align:-2px;">check</span>
              oleh {{ $reg->approved_by ?? 'Admin' }}
            </span>
            @else
            <span style="font-size:11px;color:#ef4444;font-weight:600;">
              <span class="material-symbols-outlined fill-icon" style="font-size:13px;vertical-align:-2px;">close</span>
              Ditolak
            </span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  <!-- ══ TABEL USER AKTIF ══ -->
  <div class="card" style="overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:#fff7ed;border-radius:9px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:17px;">group</span>
        </div>
        <div>
          <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:14px;color:#0f172a;">Pengguna Aktif</div>
          <div style="font-size:11px;color:#94a3b8;">{{ count($userList) }} akun terdaftar</div>
        </div>
      </div>
    </div>

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
          <!-- Pengguna -->
          <td style="padding-left:20px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,{{ $u['color1'] }},{{ $u['color2'] }});display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:12px;color:#fff;flex-shrink:0;">{{ $u['initials'] }}</div>
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $u['nama'] }}</div>
                <div style="font-size:11px;color:#94a3b8;">Bergabung {{ $u['bergabung'] }}</div>
              </div>
            </div>
          </td>
          <!-- Email -->
          <td style="font-size:12px;color:#64748b;">{{ $u['email'] }}</td>
          <!-- Peran -->
          <td>
            @if($u['peran'] === 'Admin')
              <span style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Admin</span>
            @else
              <span style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Operator</span>
            @endif
          </td>
          <!-- Kantor -->
          <td style="font-size:12px;color:#64748b;">{{ $u['kantor'] }}</td>
          <!-- Login Terakhir -->
          <td style="font-size:12px;color:#94a3b8;">{{ $u['last_login'] }}</td>
          <!-- Status -->
          <td>
            <span style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Aktif</span>
          </td>
          <!-- Aksi -->
          <td>
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
              <!-- Reset Password -->
              <form method="POST" action="{{ route('manajemen-user.reset') }}" onsubmit="return confirm('Reset password {{ $u['nama'] }} ke password123?')">
                @csrf
                <input type="hidden" name="id" value="{{ $u['id'] }}"/>
                <button type="submit" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px;">
                  <span class="material-symbols-outlined" style="font-size:13px;">lock_reset</span>
                </button>
              </form>
              <!-- Hapus -->
              @if($u['peran'] !== 'Admin' || count(array_filter($userList, fn($x) => $x['peran'] === 'Admin')) > 1)
              <form method="POST" action="{{ route('manajemen-user.delete') }}" onsubmit="return confirm('Hapus akun {{ $u['nama'] }}? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                <input type="hidden" name="id" value="{{ $u['id'] }}"/>
                <button type="submit" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;display:flex;align-items:center;gap:4px;">
                  <span class="material-symbols-outlined fill-icon" style="font-size:13px;">delete</span>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>

<!-- ══ MODAL TAMBAH USER ══ -->
<div class="modal" id="user-modal">
  <div style="background:#fff;border-radius:20px;width:480px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.18);">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;">Tambah Pengguna Baru</h3>
      <button onclick="closeModal('user-modal')" style="border:none;background:#f1f5f9;border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:18px;color:#64748b;">×</button>
    </div>
    <form method="POST" action="{{ route('manajemen-user.store') }}">
      @csrf
      <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Nama Lengkap *</label>
            <input type="text" name="nama" class="field" placeholder="Nama pengguna" required value="{{ old('nama') }}"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Peran *</label>
            <select name="peran" class="field" id="modal-peran" onchange="toggleKantorModal(this.value)" required>
              <option value="operator" {{ old('peran') === 'operator' ? 'selected' : '' }}>Operator</option>
              <option value="admin"    {{ old('peran') === 'admin'    ? 'selected' : '' }}>Admin</option>
            </select>
          </div>
        </div>

        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Email *</label>
          <input type="email" name="email" class="field" placeholder="email@dianpilar.co.id" required value="{{ old('email') }}"/>
        </div>

        <div id="modal-kantor-wrap">
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kantor</label>
          <select name="kantor_id" class="field">
            <option value="">— Pilih Kantor —</option>
            @foreach($kantorList as $k)
            <option value="{{ $k->id }}" {{ old('kantor_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Password Sementara</label>
          <input type="password" name="password" class="field" placeholder="Kosongkan = pakai 'password123'"/>
          <div style="font-size:11px;color:#94a3b8;margin-top:4px;">Kosongkan untuk menggunakan password default: <strong>password123</strong></div>
        </div>

      </div>
      <div style="padding:0 24px 20px;display:flex;gap:10px;">
        <button type="button" onclick="closeModal('user-modal')" class="btn-ghost" style="flex:1;">Batal</button>
        <button type="submit" class="btn-or" style="flex:1;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="font-size:16px;">person_add</span> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ══ MODAL TOLAK REGISTRASI ══ -->
<div class="modal" id="reject-modal">
  <div style="background:#fff;border-radius:20px;width:420px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.18);">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;">Tolak Pengajuan</h3>
      <button onclick="closeModal('reject-modal')" style="border:none;background:#f1f5f9;border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:18px;color:#64748b;">×</button>
    </div>
    <form method="POST" action="{{ route('register-requests.reject') }}">
      @csrf
      <input type="hidden" name="id" id="reject-id"/>
      <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 14px;font-size:12.5px;color:#991b1b;font-weight:600;">
          <span class="material-symbols-outlined fill-icon" style="font-size:15px;vertical-align:-3px;margin-right:4px;">warning</span>
          Pengajuan dari <strong id="reject-nama"></strong> akan ditolak.
        </div>
        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Catatan (opsional)</label>
          <textarea name="catatan_admin" class="field" placeholder="Alasan penolakan..." style="resize:vertical;min-height:70px;"></textarea>
        </div>
      </div>
      <div style="padding:0 24px 20px;display:flex;gap:10px;">
        <button type="button" onclick="closeModal('reject-modal')" class="btn-ghost" style="flex:1;">Batal</button>
        <button type="submit" style="flex:1;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;font-weight:700;border-radius:12px;padding:13px;font-size:13px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
          <span class="material-symbols-outlined fill-icon" style="font-size:16px;">cancel</span> Tolak Pengajuan
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function openRejectModal(id, nama) {
  document.getElementById('reject-id').value  = id;
  document.getElementById('reject-nama').textContent = nama;
  openModal('reject-modal');
}

function toggleKantorModal(peran) {
  document.getElementById('modal-kantor-wrap').style.display =
    peran === 'admin' ? 'none' : 'block';
}

// Init
toggleKantorModal(document.getElementById('modal-peran').value);

// Buka modal otomatis kalau ada error dari server
@if($errors->any())
  openModal('user-modal');
@endif
</script>
@endpush
@endsection