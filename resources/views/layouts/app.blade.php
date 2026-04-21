<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Sistem Inventaris | PT. Dian Pilar Utama</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
/* ─── Reset ─────────────────────────────────────────── */
*, *::before, *::after {
  font-family: 'DM Sans', sans-serif;
  box-sizing: border-box;
  margin: 0; padding: 0;
}
/* ✅ FIX: hapus overflow:hidden dari html/body supaya modal bisa scroll */
html { height: 100%; }
body { height: 100%; }
h1,h2,h3,.font-head { font-family: 'Sora', sans-serif; }
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;
  font-size: 20px; vertical-align: middle; line-height: 1;
}
.fill-icon { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }

/* ─── App Shell ─────────────────────────────────────── */
.app-shell {
  display: flex;
  height: 100vh;
  /* overflow DIHAPUS — overflow:hidden pada parent bisa clip position:fixed child */
}

/* ─── Sidebar ────────────────────────────────────────── */
.sidebar {
  width: 220px;
  flex-shrink: 0;
  height: 100vh;
  background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
nav::-webkit-scrollbar { width: 3px; }
nav::-webkit-scrollbar-thumb { background: rgba(249,115,22,.25); border-radius: 8px; }

.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 12px; border-radius: 10px;
  color: #64748b; font-size: 12.5px; font-weight: 500;
  transition: all .15s; border: 1px solid transparent; text-decoration: none;
}
.nav-item:hover { background: #fff7ed; color: #ea580c; border-color: #fed7aa; }
.nav-item.active {
  background: linear-gradient(135deg,#f97316,#ea580c);
  color: #fff; font-weight: 700;
  box-shadow: 0 4px 14px rgba(249,115,22,.3);
}
.nav-item.active .material-symbols-outlined { color: #fff; }
.nav-group-label {
  font-size: 10px; font-weight: 700; letter-spacing: .12em;
  text-transform: uppercase; color: #cbd5e1; padding: 12px 12px 4px;
}

/* ─── Main Column ───────────────────────────────────── */
.main-area {
  flex: 1;
  min-width: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.top-header {
  flex-shrink: 0;
  background: #fff;
  border-bottom: 1px solid #f1f5f9;
  padding: 10px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 1px 4px rgba(0,0,0,.04);
  z-index: 10;
}

.page-content {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
}
.page-content::-webkit-scrollbar { width: 5px; }
.page-content::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 8px; }
.page-content::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

/* ─── Modal ─────────────────────────────────────────── */
/*
 * ROOT CAUSE yang sebenernya:
 * position:fixed TIDAK terpengaruh overflow:hidden parent — ini mitos lama.
 * Yang bikin modal kepotong adalah kombinasi:
 * 1. align-items:center → modal di-center, kalau tinggi > viewport = kepotong atas
 * 2. height:100% pada overlay tanpa overflow → isi ga bisa scroll
 *
 * Fix: overlay pakai overflow-y:auto + align-items:flex-start + padding atas-bawah
 * Ini cukup, TIDAK perlu hapus overflow dari parent manapun.
 */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(15,23,42,.65);
  z-index: 9999;
  overflow-y: auto;          /* overlay scroll sendiri */
  padding: 48px 20px;        /* ruang atas-bawah cukup */
}
.modal.open {
  display: flex;
  flex-direction: column;
  align-items: center;
}
/* Inner box default — bisa di-override per modal */
.modal > div {
  width: 100%;
  max-width: 520px;
  flex-shrink: 0;   /* penting: jangan dikecilkan oleh flex parent */
  margin-left: 220px; /* kompensasi lebar sidebar supaya modal center di area konten */
}
.modal-box {
  position: relative;
  z-index: 1;
}

/* ─── Toast ─────────────────────────────────────────── */
.toast {
  position: fixed;
  bottom: 24px; right: 24px;
  z-index: 99999;
  transform: translateY(80px); opacity: 0;
  transition: all .3s cubic-bezier(.34,1.56,.64,1);
  pointer-events: none;
}
.toast.show { transform: translateY(0); opacity: 1; pointer-events: auto; }

/* ─── Animations ────────────────────────────────────── */
@keyframes fadeUp { from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);} }
.fade-up { animation: fadeUp .2s ease forwards; }

/* ─── Buttons ───────────────────────────────────────── */
.btn-or {
  background: linear-gradient(135deg,#f97316,#ea580c); color: #fff;
  font-weight: 700; border-radius: 12px; padding: 9px 20px; font-size: 13px;
  box-shadow: 0 4px 14px rgba(249,115,22,.25); border: none; cursor: pointer;
  display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
  transition: all .18s;
}
.btn-or:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(249,115,22,.35); color: #fff; }
.btn-ghost {
  background: #f1f5f9; color: #475569; font-weight: 600;
  border-radius: 12px; padding: 9px 20px; font-size: 13px;
  border: 1px solid #e2e8f0; cursor: pointer;
  display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
  transition: all .15s;
}
.btn-ghost:hover { background: #e2e8f0; color: #334155; }

/* ─── Cards ─────────────────────────────────────────── */
.card      { background:#fff;border-radius:16px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05); }
.card-stat { background:#fff;border-radius:16px;border:1px solid #f1f5f9;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,.05); }
.stat-accent-border { border-left: 3px solid #f97316; }

/* ─── Fields ────────────────────────────────────────── */
.field {
  width:100%;background:#f8fafc;border:1.5px solid #e2e8f0;
  border-radius:10px;padding:9px 12px;font-size:13px;
  transition:all .15s;outline:none;color:#0f172a;
}
.field:focus { border-color:#f97316;background:#fff;box-shadow:0 0 0 3px rgba(249,115,22,.1); }
.field:disabled { background:#f1f5f9;cursor:not-allowed;opacity:.7; }
select.field { cursor:pointer; }

/* ─── Badges ────────────────────────────────────────── */
.badge-baik      { background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:3px 10px;border-radius:999px;font-size:10px;font-weight:700; }
.badge-rusak     { background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:3px 10px;border-radius:999px;font-size:10px;font-weight:700; }
.badge-perbaikan { background:#fef9c3;color:#854d0e;border:1px solid #fef08a;padding:3px 10px;border-radius:999px;font-size:10px;font-weight:700; }

/* ─── Table ─────────────────────────────────────────── */
.tbl { width:100%;border-collapse:collapse; }
.tbl th { padding:10px 12px;text-align:left;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;background:#f8fafc;border-bottom:1px solid #f1f5f9; }
.tbl td { padding:11px 12px;font-size:13px;border-bottom:1px solid #f8fafc;color:#334155; }
.tbl tr:hover td { background:#fff7ed; }
.tbl tr:last-child td { border-bottom:none; }

/* ─── Progress ──────────────────────────────────────── */
.progress-track { height:6px;background:#f1f5f9;border-radius:999px;overflow:hidden; }
.progress-fill  { height:100%;border-radius:999px;background:linear-gradient(90deg,#f97316,#ea580c); }
</style>
</head>
<body>

<div class="app-shell">

  <!-- ── SIDEBAR ── -->
  <aside class="sidebar">

    <div style="flex-shrink:0;padding:16px;border-bottom:1px solid rgba(249,115,22,.15);">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:12px;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(249,115,22,.4);">DPU</div>
        <div>
          <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:12.5px;color:#fff;line-height:1.2;">Dian Pilar Utama</div>
          <div style="font-size:10px;color:rgba(249,115,22,.7);font-weight:600;text-transform:uppercase;letter-spacing:.08em;">Inventaris v1</div>
        </div>
      </div>
      <div style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:8px 10px;display:flex;align-items:center;gap:8px;">
        <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:11px;color:#fff;flex-shrink:0;">
          {{ strtoupper(substr(session('user_name', 'U'), 0, 2)) }}
        </div>
        <div style="min-width:0;flex:1;">
          <div style="font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ session('user_name', 'User') }}</div>
          <div style="font-size:10px;color:#64748b;font-weight:500;">{{ session('user_role', 'Operator') }}</div>
        </div>
      </div>
      @if(session('user_type') === 'operator')
      <div style="margin-top:6px;background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.2);border-radius:8px;padding:5px 8px;display:flex;align-items:center;gap:5px;">
        <span class="material-symbols-outlined" style="color:#f97316;font-size:12px;">location_on</span>
        <span style="font-size:11px;color:#fb923c;font-weight:600;">{{ session('kantor_name', '') }}</span>
      </div>
      @endif
    </div>

    <nav style="flex:1;overflow-y:auto;padding:10px 8px;display:flex;flex-direction:column;gap:1px;">
      <div class="nav-group-label">Menu Utama</div>
      <a href="{{ route('dashboard') }}"  class="nav-item {{ request()->routeIs('dashboard')  ? 'active':'' }}"><span class="material-symbols-outlined fill-icon">dashboard</span> Dashboard</a>
      <a href="{{ route('inventaris') }}" class="nav-item {{ request()->routeIs('inventaris') ? 'active':'' }}"><span class="material-symbols-outlined">inventory_2</span> Inventaris</a>
      <a href="{{ route('mutasi') }}"     class="nav-item {{ request()->routeIs('mutasi')     ? 'active':'' }}"><span class="material-symbols-outlined">swap_horiz</span> Mutasi Aset</a>
      <a href="{{ route('jadwal') }}"     class="nav-item {{ request()->routeIs('jadwal')     ? 'active':'' }}"><span class="material-symbols-outlined">event</span> Jadwal Pemeliharaan</a>
      <a href="{{ route('stok') }}"       class="nav-item {{ request()->routeIs('stok')       ? 'active':'' }}"><span class="material-symbols-outlined">shelves</span> Manajemen Stok</a>
      <a href="{{ route('qr-label') }}"   class="nav-item {{ request()->routeIs('qr-label')   ? 'active':'' }}"><span class="material-symbols-outlined">qr_code_2</span> QR & Label</a>
      <a href="{{ route('berita-acara') }}"
   class="nav-item {{ request()->routeIs('berita-acara*') ? 'active' : '' }}">
    <span class="material-symbols-outlined">description</span>
    Berita Acara
</a>
 
      <a href="{{ route('laporan') }}"    class="nav-item {{ request()->routeIs('laporan')    ? 'active':'' }}"><span class="material-symbols-outlined">bar_chart</span> Laporan</a>
      @if(session('user_type') === 'admin')
      <div class="nav-group-label" style="margin-top:4px;">Admin</div>
      <a href="{{ route('manajemen-user') }}" class="nav-item {{ request()->routeIs('manajemen-user') ? 'active':'' }}"><span class="material-symbols-outlined">manage_accounts</span> Manajemen User</a>
      <a href="{{ route('audit-log') }}"      class="nav-item {{ request()->routeIs('audit-log')      ? 'active':'' }}"><span class="material-symbols-outlined">history</span> Audit Log</a>
      @endif
    </nav>

    <div style="flex-shrink:0;padding:8px;border-top:1px solid rgba(255,255,255,.08);">
      <a href="{{ route('pengaturan') }}" class="nav-item {{ request()->routeIs('pengaturan') ? 'active':'' }}">
        <span class="material-symbols-outlined">settings</span> Pengaturan
      </a>
      <a href="#" class="nav-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="color:#ef4444;margin-top:2px;">
        <span class="material-symbols-outlined" style="color:#ef4444;">logout</span> Keluar
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
      <div style="font-size:10px;color:#475569;text-align:center;margin-top:8px;">Sistem Inventaris DPU v1.0</div>
    </div>

  </aside>
  <!-- ── END SIDEBAR ── -->

  <!-- ── MAIN AREA ── -->
  <div class="main-area">

    <header class="top-header">
      <div style="position:relative;">
        <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:15px;">search</span>
        <input type="text" placeholder="Cari aset, kode, kategori..." class="field"
          style="padding-left:34px;width:260px;font-size:12px;background:#f8fafc;"
          onkeydown="if(event.key==='Enter'){window.location.href='{{ route('inventaris') }}?q='+encodeURIComponent(this.value)}"/>
      </div>
      <div style="display:flex;align-items:center;gap:10px;">
        <button onclick="showToast('Tidak ada notifikasi baru')"
          style="position:relative;width:34px;height:34px;border-radius:9px;background:#f8fafc;border:1px solid #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;">
          <span class="material-symbols-outlined" style="font-size:17px;">notifications</span>
          <span style="position:absolute;top:6px;right:6px;width:6px;height:6px;background:#f97316;border-radius:50%;border:1.5px solid #fff;"></span>
        </button>
        <div style="display:flex;align-items:center;gap:8px;cursor:pointer;" onclick="window.location.href='{{ route('pengaturan') }}'">
          <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:11px;color:#fff;">
            {{ strtoupper(substr(session('user_name', 'U'), 0, 2)) }}
          </div>
          <div>
            <div style="font-size:12px;font-weight:700;color:#0f172a;">{{ session('user_name', 'User') }}</div>
            <div style="font-size:10px;color:#94a3b8;">{{ session('user_role', '') }}</div>
          </div>
        </div>
      </div>
    </header>

    <main class="page-content fade-up">
      @yield('content')
    </main>

  </div>
  <!-- ── END MAIN ── -->

</div>
<!-- ── END APP-SHELL ── -->

<!-- Toast -->
<div class="toast" id="toast">
  <div style="background:#fff;border-radius:14px;padding:13px 16px;box-shadow:0 8px 32px rgba(0,0,0,.14);display:flex;align-items:center;gap:10px;border-left:3px solid #f97316;min-width:240px;">
    <span class="material-symbols-outlined fill-icon" id="toast-icon" style="color:#10b981;font-size:18px;">check_circle</span>
    <span id="toast-msg" style="font-size:13px;font-weight:600;color:#0f172a;"></span>
  </div>
</div>

<script>
function showToast(msg, type) {
  var toast = document.getElementById('toast');
  var icon  = document.getElementById('toast-icon');
  document.getElementById('toast-msg').textContent = msg;
  if (type==='delete')       { icon.textContent='delete';       icon.style.color='#ef4444'; }
  else if (type==='warning') { icon.textContent='warning';      icon.style.color='#f59e0b'; }
  else                       { icon.textContent='check_circle'; icon.style.color='#10b981'; }
  toast.classList.add('show');
  setTimeout(function(){ toast.classList.remove('show'); }, 3000);
}

function openModal(id) {
  var m = document.getElementById(id);
  if (m) { m.classList.add('open'); }
}
function closeModal(id) {
  var m = document.getElementById(id);
  if (m) { m.classList.remove('open'); }
}

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal')) {
    closeModal(e.target.id);
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    var open = document.querySelector('.modal.open');
    if (open) closeModal(open.id);
  }
});
</script>

@stack('scripts')
</body>
</html>