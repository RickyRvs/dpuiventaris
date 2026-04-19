@extends('layouts.app')

@section('content')
<style>
#tambah-modal,
#detail-jadwal-modal {
  align-items: flex-start !important;
  padding-top: 40px !important;
  padding-bottom: 40px !important;
  overflow-y: auto !important;
}
</style>
<div style="padding:24px;display:flex;flex-direction:column;gap:16px;">

  @if(session('success'))
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px;">
    <span class="material-symbols-outlined fill-icon" style="color:#16a34a;font-size:18px;">check_circle</span>
    <span style="font-size:13px;font-weight:600;color:#166534;">{{ session('success') }}</span>
  </div>
  @endif

  {{-- ── Header ── --}}
  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:4px;">Jadwal Pemeliharaan</h2>
      <p style="font-size:13px;color:#64748b;">Kelola jadwal servis dan pemeliharaan aset</p>
    </div>
    <button onclick="openJadwalModal('tambah-modal')" class="btn-or">
      <span class="material-symbols-outlined" style="font-size:16px;">add</span> Tambah Jadwal
    </button>
  </div>

  {{-- ── Summary Cards ── --}}
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(175px,1fr));gap:12px;">
    @foreach([
      ['Terjadwal',   $statJadwal['terjadwal'],   '#2563eb','#eff6ff','schedule'],
      ['Dalam Proses',$statJadwal['dalam_proses'],'#f59e0b','#fffbeb','pending'],
      ['Selesai',     $statJadwal['selesai'],      '#16a34a','#f0fdf4','task_alt'],
      ['Terlewat',    $statJadwal['terlewat'],     '#dc2626','#fef2f2','warning'],
    ] as [$l,$n,$c,$bg,$ic])
    <div class="card-stat" style="border-left:3px solid {{ $c }};">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <div style="width:30px;height:30px;background:{{ $bg }};border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined" style="color:{{ $c }};font-size:17px;">{{ $ic }}</span>
        </div>
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">{{ $l }}</span>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;">{{ $n }}</p>
    </div>
    @endforeach
  </div>

  {{-- ── Main Card ── --}}
  <div class="card" style="overflow:hidden;">

    {{-- Card Header with view toggle --}}
    <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
      <h3 id="view-title" style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;">Daftar Jadwal</h3>

      <div style="display:flex;align-items:center;gap:10px;">
        {{-- Calendar nav (hidden when list view) --}}
        <div id="cal-nav-controls" style="display:none;align-items:center;gap:6px;">
          <button onclick="changeMonth(-1)" id="btn-prev" style="width:28px;height:28px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:15px;">&#8249;</button>
          <span id="cal-month-label" style="font-family:'Sora',sans-serif;font-size:12px;font-weight:700;color:#0f172a;min-width:115px;text-align:center;"></span>
          <button onclick="changeMonth(1)" style="width:28px;height:28px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:15px;">&#8250;</button>
        </div>

        {{-- Toggle --}}
        <div style="display:flex;background:#f1f5f9;border-radius:9px;padding:2px;gap:2px;">
          <button id="btn-list-view" onclick="switchView('list')"
            style="padding:5px 13px;border-radius:7px;font-size:11px;font-weight:600;border:none;cursor:pointer;background:#fff;color:#0f172a;box-shadow:0 1px 3px rgba(0,0,0,.1);display:flex;align-items:center;gap:5px;transition:all .15s;">
            <span class="material-symbols-outlined" style="font-size:13px;">format_list_bulleted</span> List
          </button>
          <button id="btn-cal-view" onclick="switchView('calendar')"
            style="padding:5px 13px;border-radius:7px;font-size:11px;font-weight:600;border:none;cursor:pointer;background:transparent;color:#64748b;display:flex;align-items:center;gap:5px;transition:all .15s;">
            <span class="material-symbols-outlined" style="font-size:13px;">calendar_month</span> Kalender
          </button>
        </div>
      </div>
    </div>

    {{-- ── LIST VIEW ── --}}
    <div id="list-view">
      <table class="tbl" style="width:100%;">
        <thead><tr>
          <th style="padding-left:20px;">ID</th>
          <th>Aset</th>
          <th>Jenis Pemeliharaan</th>
          <th>Teknisi</th>
          <th>Tanggal</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr></thead>
        <tbody>
          @forelse($jadwalList as $j)
          @php
            $statusColors = [
              'Selesai'     => ['#dcfce7','#166534','#bbf7d0'],
              'Dalam Proses'=> ['#fef9c3','#854d0e','#fef08a'],
              'Terlewat'    => ['#fee2e2','#991b1b','#fecaca'],
              'Terjadwal'   => ['#eff6ff','#1d4ed8','#bfdbfe'],
            ];
            $sc = $statusColors[$j->status] ?? ['#f1f5f9','#64748b','#e2e8f0'];
          @endphp
          <tr>
            <td style="padding-left:20px;font-size:11px;color:#94a3b8;font-weight:700;">{{ $j->kode }}</td>
            <td>
              <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $j->aset->nama }}</div>
              <div style="font-size:11px;color:#94a3b8;">{{ $j->kantor->short_name }}</div>
            </td>
            <td style="font-size:12px;color:#334155;">{{ $j->jenis }}</td>
            <td style="font-size:12px;color:#64748b;">{{ $j->teknisi }}</td>
            <td>
              <div style="font-size:12px;font-weight:700;color:#0f172a;">{{ $j->tanggal->format('d M Y') }}</div>
              <div style="font-size:11px;color:#94a3b8;">{{ $j->waktu }}</div>
            </td>
            <td>
              <span style="background:{{ $sc[0] }};color:{{ $sc[1] }};border:1px solid {{ $sc[2] }};padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">{{ $j->status }}</span>
            </td>
            <td>
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                @if($j->status === 'Terjadwal')
                <form method="POST" action="{{ route('jadwal.mulai') }}" style="display:inline;">
                  @csrf <input type="hidden" name="id" value="{{ $j->id }}"/>
                  <button type="submit" style="background:#fff7ed;border:1px solid #fed7aa;color:#f97316;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;">Mulai</button>
                </form>
                @elseif($j->status === 'Dalam Proses')
                <form method="POST" action="{{ route('jadwal.selesai') }}" style="display:inline;">
                  @csrf <input type="hidden" name="id" value="{{ $j->id }}"/>
                  <button type="submit" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;">Selesai</button>
                </form>
                @endif
                <button onclick="showDetailJadwal(
                  '{{ addslashes($j->kode) }}',
                  '{{ addslashes($j->aset->nama) }}',
                  '{{ addslashes($j->kantor->short_name) }}',
                  '{{ addslashes($j->jenis) }}',
                  '{{ addslashes($j->teknisi) }}',
                  '{{ $j->tanggal->format('d M Y') }}',
                  '{{ $j->waktu }}',
                  '{{ addslashes($j->catatan ?? '') }}',
                  '{{ $j->status }}',
                  '{{ $j->id }}'
                )" style="background:#f8fafc;border:1px solid #e2e8f0;color:#64748b;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:11px;font-weight:600;">
                  <span class="material-symbols-outlined" style="font-size:12px;vertical-align:middle;">info</span> Detail
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="padding:48px;text-align:center;">
              <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="font-size:36px;color:#cbd5e1;">event_busy</span>
                <p style="color:#94a3b8;font-size:13px;">Belum ada jadwal pemeliharaan.</p>
                <button onclick="openJadwalModal('tambah-modal')" class="btn-or" style="font-size:12px;padding:7px 16px;">
                  <span class="material-symbols-outlined" style="font-size:14px;">add</span> Tambah Sekarang
                </button>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- ── CALENDAR VIEW ── --}}
    <div id="calendar-view" style="display:none;padding:16px;">

      {{-- Day headers --}}
      <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;margin-bottom:4px;">
        @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $d)
        <div style="text-align:center;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;padding:6px 0;">{{ $d }}</div>
        @endforeach
      </div>

      {{-- Days grid (rendered by JS) --}}
      <div id="cal-days-grid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;"></div>
    </div>

  </div>
</div>

{{-- ════════════════════════════════════════════
     MODAL: TAMBAH JADWAL
════════════════════════════════════════════ --}}
<div class="modal" id="tambah-modal">
  <div style="background:#fff;border-radius:20px;width:500px;max-width:95vw;box-shadow:0 24px 64px rgba(0,0,0,.2);overflow:hidden;display:flex;flex-direction:column;">

    {{-- Header --}}
    <div style="padding:20px 24px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
      <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:34px;height:34px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined" style="color:#2563eb;font-size:18px;">event_note</span>
        </div>
        <div>
          <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;">Tambah Jadwal Pemeliharaan</h3>
          <p style="font-size:11px;color:#94a3b8;margin-top:1px;">Isi form di bawah untuk menambah jadwal</p>
        </div>
      </div>
      <button onclick="closeJadwalModal('tambah-modal')" style="border:none;background:#f1f5f9;border-radius:9px;width:32px;height:32px;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">×</button>
    </div>

    {{-- Body --}}
    <form method="POST" action="{{ route('jadwal.store') }}" style="overflow-y:auto;flex:1;display:flex;flex-direction:column;">
      @csrf
      <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;flex:1;">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Aset <span style="color:#ef4444;">*</span></label>
            <select name="aset_id" class="field" required>
              <option value="">-- Pilih aset --</option>
              @foreach($asetList as $a)
              <option value="{{ $a->id }}">{{ $a->kode }} — {{ $a->nama }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kantor <span style="color:#ef4444;">*</span></label>
            <select name="kantor_id" class="field" required>
              <option value="">-- Pilih kantor --</option>
              @foreach($kantorList as $k)
              <option value="{{ $k->id }}" {{ !$isAdmin && session('kantor_db_id')==$k->id ? 'selected' : '' }}>{{ $k->short_name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Jenis Pemeliharaan <span style="color:#ef4444;">*</span></label>
          <select name="jenis" class="field" required>
            <option value="">-- Pilih jenis --</option>
            @foreach(['Servis Rutin','Penggantian Part','Kalibrasi','Inspeksi','Pembersihan','Perbaikan Darurat','Penggantian Baterai','Pemeriksaan Tahunan'] as $jenis)
            <option>{{ $jenis }}</option>
            @endforeach
          </select>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Tanggal <span style="color:#ef4444;">*</span></label>
            <input type="date" name="tanggal" class="field" required/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Waktu <span style="color:#ef4444;">*</span></label>
            <input type="text" name="waktu" class="field" placeholder="09:00 - 12:00" required/>
          </div>
        </div>

        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Nama Teknisi <span style="color:#ef4444;">*</span></label>
          <input type="text" name="teknisi" class="field" placeholder="Nama teknisi atau vendor" required/>
        </div>

        <div>
          <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Catatan</label>
          <textarea name="catatan" class="field" placeholder="Instruksi khusus atau catatan tambahan..." style="height:75px;resize:vertical;"></textarea>
        </div>

      </div>

      {{-- Footer --}}
      <div style="padding:16px 24px 20px;display:flex;gap:10px;flex-shrink:0;border-top:1px solid #f1f5f9;">
        <button type="button" onclick="closeJadwalModal('tambah-modal')" class="btn-ghost" style="flex:1;">Batal</button>
        <button type="submit" class="btn-or" style="flex:1;justify-content:center;">
          <span class="material-symbols-outlined" style="font-size:15px;">save</span> Simpan Jadwal
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ════════════════════════════════════════════
     MODAL: DETAIL JADWAL
════════════════════════════════════════════ --}}
<div class="modal" id="detail-jadwal-modal">
  <div style="background:#fff;border-radius:20px;width:460px;max-width:95vw;box-shadow:0 24px 64px rgba(0,0,0,.2);overflow:hidden;display:flex;flex-direction:column;">

    {{-- Header --}}
    <div style="padding:20px 24px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:flex-start;justify-content:space-between;">
      <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
          <span class="material-symbols-outlined fill-icon" style="color:#2563eb;font-size:18px;">event_note</span>
          <h3 id="detail-kode" style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;">Detail Jadwal</h3>
        </div>
        <div id="detail-status-badge"></div>
      </div>
      <button onclick="closeJadwalModal('detail-jadwal-modal')" style="border:none;background:#f1f5f9;border-radius:9px;width:32px;height:32px;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">×</button>
    </div>

    {{-- Body --}}
    <div id="detail-jadwal-content" style="padding:18px 24px;display:flex;flex-direction:column;gap:8px;overflow-y:auto;max-height:60vh;"></div>

    {{-- Footer --}}
    <div id="detail-jadwal-actions" style="padding:14px 24px 20px;display:flex;gap:10px;border-top:1px solid #f1f5f9;flex-shrink:0;"></div>
  </div>
</div>

@push('scripts')
<script>
// ── Calendar Data (PHP → JS) ──────────────────────────────
const jadwalEvents = [
  @foreach($jadwalList as $j)
  {
    id: {{ $j->id }},
    kode: '{{ addslashes($j->kode) }}',
    aset: '{{ addslashes($j->aset->nama) }}',
    kantor: '{{ addslashes($j->kantor->short_name) }}',
    jenis: '{{ addslashes($j->jenis) }}',
    teknisi: '{{ addslashes($j->teknisi) }}',
    tanggal: '{{ $j->tanggal->format('Y-m-d') }}',
    waktu: '{{ addslashes($j->waktu) }}',
    catatan: '{{ addslashes($j->catatan ?? '') }}',
    status: '{{ $j->status }}',
  },
  @endforeach
];

// ── Status config ─────────────────────────────────────────
const statusCfg = {
  'Selesai':      { bg:'#dcfce7', color:'#166534', border:'#bbf7d0', evtBg:'#bbf7d0', evtColor:'#166534' },
  'Dalam Proses': { bg:'#fef9c3', color:'#854d0e', border:'#fef08a', evtBg:'#fef08a', evtColor:'#854d0e' },
  'Terlewat':     { bg:'#fee2e2', color:'#991b1b', border:'#fecaca', evtBg:'#fecaca', evtColor:'#991b1b' },
  'Terjadwal':    { bg:'#eff6ff', color:'#1d4ed8', border:'#bfdbfe', evtBg:'#bfdbfe', evtColor:'#1d4ed8' },
};

function getBadge(status) {
  const s = statusCfg[status] || { bg:'#f1f5f9', color:'#64748b', border:'#e2e8f0' };
  return `<span style="background:${s.bg};color:${s.color};border:1px solid ${s.border};padding:3px 10px;border-radius:999px;font-size:10px;font-weight:700;">${status}</span>`;
}

// ── Modal helpers ─────────────────────────────────────────
function openJadwalModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.add('open'); }
}
function closeJadwalModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.remove('open'); }
}

// ── Detail modal ──────────────────────────────────────────
function showDetailJadwal(kode, aset, kantor, jenis, teknisi, tanggal, waktu, catatan, status, jadwalId) {
  document.getElementById('detail-kode').textContent = kode;
  document.getElementById('detail-status-badge').innerHTML = getBadge(status);

  const fields = [
    ['Aset', aset],
    ['Kantor', kantor],
    ['Jenis Pemeliharaan', jenis],
    ['Teknisi / Vendor', teknisi],
    ['Tanggal', tanggal],
    ['Waktu', waktu],
    ['Catatan', catatan || '—'],
  ];

  document.getElementById('detail-jadwal-content').innerHTML = fields.map(([l, v]) => `
    <div style="background:#f8fafc;border-radius:10px;padding:11px 14px;">
      <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">${l}</p>
      <p style="font-size:13px;font-weight:600;color:#0f172a;">${v}</p>
    </div>`).join('');

  // Action buttons
  let actions = `<button type="button" onclick="closeJadwalModal('detail-jadwal-modal')" class="btn-ghost" style="flex:1;">Tutup</button>`;

  if (status === 'Terjadwal') {
    actions += `
      <form method="POST" action="{{ route('jadwal.mulai') }}" style="flex:1;">
        @csrf
        <input type="hidden" name="id" value="${jadwalId}"/>
        <button type="submit" style="width:100%;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;font-weight:700;border-radius:12px;padding:9px 20px;font-size:13px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
          <span class="material-symbols-outlined" style="font-size:15px;">play_circle</span> Mulai Pemeliharaan
        </button>
      </form>`;
  } else if (status === 'Dalam Proses') {
    actions += `
      <form method="POST" action="{{ route('jadwal.selesai') }}" style="flex:1;">
        @csrf
        <input type="hidden" name="id" value="${jadwalId}"/>
        <button type="submit" style="width:100%;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-weight:700;border-radius:12px;padding:9px 20px;font-size:13px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
          <span class="material-symbols-outlined" style="font-size:15px;">task_alt</span> Tandai Selesai
        </button>
      </form>`;
  }

  document.getElementById('detail-jadwal-actions').innerHTML = actions;
  openJadwalModal('detail-jadwal-modal');
}

// ── Calendar ──────────────────────────────────────────────
const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const TODAY = new Date();
let calYear  = TODAY.getFullYear();
let calMonth = TODAY.getMonth();

function changeMonth(delta) {
  calMonth += delta;
  if (calMonth > 11) { calMonth = 0; calYear++; }
  if (calMonth < 0)  { calMonth = 11; calYear--; }
  renderCalendar();
}

function renderCalendar() {
  document.getElementById('cal-month-label').textContent = `${MONTHS_ID[calMonth]} ${calYear}`;

  const grid = document.getElementById('cal-days-grid');
  const firstDay = new Date(calYear, calMonth, 1).getDay();
  const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
  const daysInPrev  = new Date(calYear, calMonth, 0).getDate();

  let html = '';

  // Prev month padding
  for (let i = firstDay - 1; i >= 0; i--) {
    html += `<div style="min-height:68px;border-radius:10px;padding:6px;border:1px solid #f1f5f9;background:#fafafa;opacity:.45;">
      <div style="font-size:11px;font-weight:600;color:#94a3b8;">${daysInPrev - i}</div>
    </div>`;
  }

  // Current month
  for (let d = 1; d <= daysInMonth; d++) {
    const dateStr = `${calYear}-${String(calMonth + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    const isToday = d === TODAY.getDate() && calMonth === TODAY.getMonth() && calYear === TODAY.getFullYear();
    const events  = jadwalEvents.filter(e => e.tanggal === dateStr);

    const todayStyle = isToday
      ? 'background:#fff7ed;border-color:#fed7aa;'
      : 'background:#fff;border-color:#f1f5f9;';

    const numStyle = isToday
      ? 'font-size:11px;font-weight:800;color:#ea580c;background:#fed7aa;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:4px;'
      : 'font-size:11px;font-weight:600;color:#64748b;margin-bottom:4px;';

    const eventsHtml = events.map(e => {
      const s = statusCfg[e.status] || { evtBg:'#e2e8f0', evtColor:'#334155' };
      const escapedAset = e.aset.replace(/'/g, "\\'");
      const escapedKantor = e.kantor.replace(/'/g, "\\'");
      const escapedJenis = e.jenis.replace(/'/g, "\\'");
      const escapedTeknisi = e.teknisi.replace(/'/g, "\\'");
      const escapedCatatan = e.catatan.replace(/'/g, "\\'");
      return `<div
        onclick="showDetailJadwal('${e.kode}','${escapedAset}','${escapedKantor}','${escapedJenis}','${escapedTeknisi}','${formatDateDisplay(e.tanggal)}','${e.waktu}','${escapedCatatan}','${e.status}','${e.id}')"
        style="font-size:9px;font-weight:600;padding:2px 5px;border-radius:4px;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;cursor:pointer;background:${s.evtBg};color:${s.evtColor};"
        title="${e.aset} — ${e.jenis}">
        ${e.aset}
      </div>`;
    }).join('');

    html += `<div style="min-height:68px;border-radius:10px;padding:6px;border:1px solid;${todayStyle}">
      <div style="${numStyle}">${d}</div>
      ${eventsHtml}
    </div>`;
  }

  // Next month padding
  const totalCells = firstDay + daysInMonth;
  const remaining  = (7 - (totalCells % 7)) % 7;
  for (let i = 1; i <= remaining; i++) {
    html += `<div style="min-height:68px;border-radius:10px;padding:6px;border:1px solid #f1f5f9;background:#fafafa;opacity:.45;">
      <div style="font-size:11px;font-weight:600;color:#94a3b8;">${i}</div>
    </div>`;
  }

  grid.innerHTML = html;
}

function formatDateDisplay(dateStr) {
  const [y, m, d] = dateStr.split('-');
  const mn = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  return `${d} ${mn[parseInt(m)-1]} ${y}`;
}

// ── View toggle ───────────────────────────────────────────
function switchView(view) {
  const listView = document.getElementById('list-view');
  const calView  = document.getElementById('calendar-view');
  const calNav   = document.getElementById('cal-nav-controls');
  const title    = document.getElementById('view-title');
  const btnList  = document.getElementById('btn-list-view');
  const btnCal   = document.getElementById('btn-cal-view');

  if (view === 'list') {
    listView.style.display = 'block';
    calView.style.display  = 'none';
    calNav.style.display   = 'none';
    title.textContent      = 'Daftar Jadwal';
    btnList.style.background   = '#fff';
    btnList.style.color        = '#0f172a';
    btnList.style.boxShadow    = '0 1px 3px rgba(0,0,0,.1)';
    btnCal.style.background    = 'transparent';
    btnCal.style.color         = '#64748b';
    btnCal.style.boxShadow     = 'none';
  } else {
    listView.style.display = 'none';
    calView.style.display  = 'block';
    calNav.style.display   = 'flex';
    title.textContent      = 'Kalender Jadwal';
    btnCal.style.background    = '#fff';
    btnCal.style.color         = '#0f172a';
    btnCal.style.boxShadow     = '0 1px 3px rgba(0,0,0,.1)';
    btnList.style.background   = 'transparent';
    btnList.style.color        = '#64748b';
    btnList.style.boxShadow    = 'none';
    renderCalendar();
  }
}

// ── Close on backdrop / Escape ────────────────────────────
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal')) {
    closeJadwalModal(e.target.id);
  }
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal.open').forEach(m => closeJadwalModal(m.id));
  }
});
</script>
@endpush
@endsection