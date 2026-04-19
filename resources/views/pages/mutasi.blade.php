@extends('layouts.app')

@section('content')
<div style="padding:24px;display:flex;flex-direction:column;gap:16px;">

  @if(session('success'))
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px;">
    <span class="material-symbols-outlined fill-icon" style="color:#16a34a;font-size:18px;">check_circle</span>
    <span style="font-size:13px;font-weight:600;color:#166534;">{{ session('success') }}</span>
  </div>
  @endif

  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:4px;">Mutasi Aset</h2>
      <p style="font-size:13px;color:#64748b;">Kelola perpindahan aset antar kantor</p>
    </div>
    <button onclick="openModal('mutasi-modal')" class="btn-or">
      <span class="material-symbols-outlined" style="font-size:16px;">add</span> Ajukan Mutasi
    </button>
  </div>

  <!-- Tab filter (JS only for display) -->
  <div style="display:flex;gap:4px;background:#f1f5f9;padding:4px;border-radius:12px;width:fit-content;">
    @foreach(['Semua','Menunggu','Disetujui','Ditolak'] as $tab)
    <button onclick="filterMutasi(this,'{{ $tab }}')" data-tab="{{ $tab }}" style="padding:7px 16px;border-radius:9px;border:none;cursor:pointer;font-size:12px;font-weight:600;background:{{ $tab==='Semua'?'#fff':'transparent' }};color:{{ $tab==='Semua'?'#f97316':'#64748b' }};box-shadow:{{ $tab==='Semua'?'0 1px 4px rgba(0,0,0,.08)':'none' }};transition:all .15s;">{{ $tab }}</button>
    @endforeach
  </div>

  <!-- Table -->
  <div class="card" style="overflow:hidden;">
    <table class="tbl" style="width:100%;" id="mutasi-table">
      <thead><tr>
        <th style="padding-left:20px;">ID</th>
        <th>Aset</th>
        <th>Asal → Tujuan</th>
        <th>Pengaju</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr></thead>
      <tbody>
        @forelse($mutasiList as $m)
        <tr data-status="{{ $m->status }}">
          <td style="padding-left:20px;font-size:11px;color:#94a3b8;font-weight:700;">{{ $m->kode }}</td>
          <td>
            <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $m->aset->nama }}</div>
            <div style="font-size:11px;color:#94a3b8;">{{ $m->aset->kode }}</div>
          </td>
          <td>
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;">
              <span style="color:#64748b;">{{ $m->kantorAsal->short_name }}</span>
              <span class="material-symbols-outlined" style="color:#f97316;font-size:16px;">arrow_forward</span>
              <span style="color:#0f172a;font-weight:700;">{{ $m->kantorTujuan->short_name }}</span>
            </div>
          </td>
          <td style="font-size:12px;color:#64748b;">{{ $m->pengaju->nama }}</td>
          <td style="font-size:12px;color:#64748b;">{{ $m->created_at->format('d M Y') }}</td>
          <td>
            @if($m->status === 'Disetujui')
              <span style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Disetujui</span>
            @elseif($m->status === 'Ditolak')
              <span style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Ditolak</span>
            @else
              <span style="background:#fef9c3;color:#854d0e;border:1px solid #fef08a;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Menunggu</span>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:6px;">
              @if($m->status === 'Menunggu' && $isAdmin)
              <form method="POST" action="{{ route('mutasi.approve') }}" style="display:inline;">
                @csrf
                <input type="hidden" name="id" value="{{ $m->id }}"/>
                <button type="submit" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;">✓ Setujui</button>
              </form>
              <form method="POST" action="{{ route('mutasi.reject') }}" style="display:inline;">
                @csrf
                <input type="hidden" name="id" value="{{ $m->id }}"/>
                <button type="submit" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;">✗ Tolak</button>
              </form>
              @else
              <button onclick="showDetailMutasi({{ $m->id }},'{{ addslashes($m->aset->nama) }}','{{ $m->kantorAsal->short_name }}','{{ $m->kantorTujuan->short_name }}','{{ addslashes($m->alasan??'') }}')" style="background:#f8fafc;border:1px solid #e2e8f0;color:#64748b;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;">Detail</button>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="padding:40px;text-align:center;color:#94a3b8;font-size:13px;">Belum ada data mutasi.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Ajukan Mutasi -->
<div class="modal" id="mutasi-modal">
  <div style="background:#fff;border-radius:20px;width:500px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.18);">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <div>
        <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;">Ajukan Mutasi Aset</h3>
        <p style="font-size:12px;color:#64748b;">Isi formulir perpindahan aset</p>
      </div>
      <button onclick="closeModal('mutasi-modal')" style="border:none;background:#f1f5f9;border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:18px;color:#64748b;">×</button>
    </div>
    <form method="POST" action="{{ route('mutasi.store') }}">
      @csrf
      <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Aset yang Dimutasi <span style="color:#ef4444;">*</span></label>
          <select name="aset_id" class="field" required>
            <option value="">-- Pilih aset --</option>
            @foreach($asetList as $a)
            <option value="{{ $a->id }}">{{ $a->kode }} — {{ $a->nama }}</option>
            @endforeach
          </select>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kantor Asal <span style="color:#ef4444;">*</span></label>
            <select name="kantor_asal_id" class="field" required>
              <option value="">-- Pilih kantor --</option>
              @foreach($kantorList as $k)
              <option value="{{ $k->id }}">{{ $k->short_name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kantor Tujuan <span style="color:#ef4444;">*</span></label>
            <select name="kantor_tujuan_id" class="field" required>
              <option value="">-- Pilih kantor --</option>
              @foreach($kantorList as $k)
              <option value="{{ $k->id }}">{{ $k->short_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Alasan Mutasi <span style="color:#ef4444;">*</span></label>
          <textarea name="alasan" class="field" placeholder="Jelaskan alasan perpindahan aset ini (min. 5 karakter)..." style="height:80px;resize:vertical;" required></textarea>
        </div>
      </div>
      <div style="padding:0 24px 20px;display:flex;gap:10px;">
        <button type="button" onclick="closeModal('mutasi-modal')" class="btn-ghost" style="flex:1;">Batal</button>
        <button type="submit" class="btn-or" style="flex:1;justify-content:center;">Ajukan Mutasi</button>
      </div>
    </form>
  </div>
</div>

<!-- Detail Modal -->
<div class="modal" id="detail-mutasi-modal">
  <div style="background:#fff;border-radius:20px;width:420px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.18);">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;">Detail Mutasi</h3>
      <button onclick="closeModal('detail-mutasi-modal')" style="border:none;background:#f1f5f9;border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:18px;color:#64748b;">×</button>
    </div>
    <div style="padding:20px 24px;" id="detail-mutasi-content"></div>
  </div>
</div>

@push('scripts')
<script>
function filterMutasi(btn, tab) {
  document.querySelectorAll('[data-tab]').forEach(b => {
    b.style.background = 'transparent'; b.style.color = '#64748b'; b.style.boxShadow = 'none';
  });
  btn.style.background = '#fff'; btn.style.color = '#f97316'; btn.style.boxShadow = '0 1px 4px rgba(0,0,0,.08)';
  document.querySelectorAll('#mutasi-table tbody tr[data-status]').forEach(row => {
    row.style.display = (tab === 'Semua' || row.dataset.status === tab) ? '' : 'none';
  });
}
function showDetailMutasi(id, nama, asal, tujuan, alasan) {
  document.getElementById('detail-mutasi-content').innerHTML = `
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="background:#f8fafc;border-radius:10px;padding:12px 14px;">
        <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;">Aset</p>
        <p style="font-size:13px;font-weight:700;color:#0f172a;">${nama}</p>
      </div>
      <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:8px;align-items:center;">
        <div style="background:#f8fafc;border-radius:10px;padding:12px 14px;text-align:center;">
          <p style="font-size:10px;font-weight:700;color:#94a3b8;margin-bottom:4px;">ASAL</p>
          <p style="font-size:13px;font-weight:700;color:#0f172a;">${asal}</p>
        </div>
        <span class="material-symbols-outlined" style="color:#f97316;font-size:22px;">arrow_forward</span>
        <div style="background:#fff7ed;border-radius:10px;padding:12px 14px;text-align:center;border:1px solid #fed7aa;">
          <p style="font-size:10px;font-weight:700;color:#94a3b8;margin-bottom:4px;">TUJUAN</p>
          <p style="font-size:13px;font-weight:700;color:#f97316;">${tujuan}</p>
        </div>
      </div>
      <div style="background:#f8fafc;border-radius:10px;padding:12px 14px;">
        <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;">Alasan</p>
        <p style="font-size:13px;color:#334155;">${alasan || '—'}</p>
      </div>
    </div>`;
  openModal('detail-mutasi-modal');
}
</script>
@endpush
@endsection