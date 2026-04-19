<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Login | Sistem Inventaris DPU</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
/* ─── Reset ───────────────────────────── */
*, *::before, *::after {
  font-family: 'DM Sans', sans-serif;
  box-sizing: border-box;
  margin: 0; padding: 0;
}
h1, h2, h3, .sora { font-family: 'Sora', sans-serif; }

.material-symbols-outlined {
  font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;
  font-size: 20px; vertical-align: middle; line-height: 1;
}
.fill-icon { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }

/* ─── Layout utama ────────────────────── */
html, body {
  height: 100%;
  /* PERBAIKAN: bukan overflow:hidden — biarkan scroll jika konten tinggi */
  min-height: 100vh;
  background: #f1f0eb;
}

.shell {
  display: flex;
  min-height: 100vh;
}

/* ─── Panel Kiri ──────────────────────── */
.panel-left {
  width: 42%;
  flex-shrink: 0;
  background: linear-gradient(160deg, #0f172a 0%, #1a2744 50%, #0f172a 100%);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 44px 48px;
  position: relative;
  overflow: hidden;
  /* PERBAIKAN: tidak fixed height — mengikuti tinggi shell */
  min-height: 100vh;
}

/* dekorasi background */
.panel-left::before {
  content: '';
  position: absolute;
  top: -100px; left: -100px;
  width: 500px; height: 500px;
  background: radial-gradient(circle, rgba(249,115,22,.12) 0%, transparent 70%);
  pointer-events: none;
}
.panel-left::after {
  content: '';
  position: absolute;
  bottom: -80px; right: -80px;
  width: 420px; height: 420px;
  background: radial-gradient(circle, rgba(234,88,12,.1) 0%, transparent 70%);
  pointer-events: none;
}
.panel-left .dot-grid {
  position: absolute;
  inset: 0;
  opacity: .025;
  background-image: radial-gradient(#f97316 1px, transparent 1px);
  background-size: 28px 28px;
  pointer-events: none;
}
.panel-left .top-bar {
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, transparent, #f97316 40%, #ea580c 60%, transparent);
}

/* ─── Panel Kanan ─────────────────────── */
.panel-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 40px 28px;
  position: relative;
  min-height: 100vh;
  /* subtle texture */
  background: #f8f7f3;
}
.panel-right::before {
  content: '';
  position: absolute;
  top: 0; right: 0;
  width: 280px; height: 280px;
  background: radial-gradient(circle at top right, rgba(249,115,22,.06), transparent 70%);
  pointer-events: none;
}
.panel-right::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0;
  width: 220px; height: 220px;
  background: radial-gradient(circle at bottom left, rgba(249,115,22,.04), transparent 70%);
  pointer-events: none;
}

/* ─── Form card ───────────────────────── */
.form-wrap {
  width: 100%;
  max-width: 400px;
  position: relative;
  z-index: 1;
}

.login-card {
  background: #fff;
  border-radius: 22px;
  padding: 30px 28px;
  box-shadow:
    0 1px 3px rgba(0,0,0,.04),
    0 8px 24px rgba(0,0,0,.07),
    0 24px 48px rgba(0,0,0,.05);
  border: 1px solid rgba(0,0,0,.06);
}

/* ─── Tab Switch ──────────────────────── */
.tab-wrap {
  display: flex;
  background: #f1f0eb;
  padding: 4px;
  border-radius: 14px;
  margin-bottom: 20px;
  gap: 4px;
}
.tab-btn {
  flex: 1;
  padding: 9px 16px;
  font-size: 13px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  border-radius: 11px;
  transition: all .2s cubic-bezier(.34,1.2,.64,1);
  background: transparent;
  color: #94a3b8;
  font-family: 'DM Sans', sans-serif;
}
.tab-btn.active {
  background: #fff;
  color: #0f172a;
  box-shadow: 0 2px 8px rgba(0,0,0,.1);
}

/* ─── Fields ──────────────────────────── */
.field-label {
  display: block;
  font-size: 10px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .08em;
  margin-bottom: 6px;
}
.field {
  width: 100%;
  background: #f8fafc;
  border: 1.5px solid #e8eaf0;
  border-radius: 11px;
  padding: 11px 14px;
  font-size: 13.5px;
  transition: all .15s;
  outline: none;
  color: #0f172a;
  font-family: 'DM Sans', sans-serif;
}
.field:focus {
  border-color: #f97316;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(249,115,22,.1);
}
select.field { cursor: pointer; appearance: auto; }

/* ─── Buttons ─────────────────────────── */
.btn-or {
  width: 100%;
  background: linear-gradient(135deg, #f97316, #ea580c);
  color: #fff;
  font-weight: 700;
  border-radius: 12px;
  padding: 13px 22px;
  font-size: 14px;
  transition: all .18s;
  box-shadow: 0 4px 16px rgba(249,115,22,.3);
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-family: 'DM Sans', sans-serif;
  letter-spacing: .01em;
}
.btn-or:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 24px rgba(249,115,22,.4);
}
.btn-or:active { transform: translateY(0); }

/* ─── Alert ───────────────────────────── */
.alert-error {
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 11px;
  padding: 11px 14px;
  margin-bottom: 16px;
  font-size: 12.5px;
  color: #991b1b;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* ─── Demo badge ──────────────────────── */
.demo-badge {
  background: linear-gradient(135deg, #f0fdf4, #dcfce7);
  border: 1px solid #bbf7d0;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 11.5px;
  color: #166534;
  display: flex;
  align-items: center;
  gap: 7px;
}

/* ─── Stat item ───────────────────────── */
.stat-item .num {
  font-family: 'Sora', sans-serif;
  font-size: 30px;
  font-weight: 800;
  color: #fff;
  line-height: 1;
  margin-bottom: 4px;
}
.stat-item .lbl {
  font-size: 10px;
  color: #475569;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .08em;
}

/* ─── Location pill ───────────────────── */
.loc-pill {
  padding: 5px 14px;
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 999px;
  color: #94a3b8;
  font-size: 11.5px;
  font-weight: 600;
  transition: all .15s;
}
.loc-pill:hover {
  background: rgba(249,115,22,.15);
  border-color: rgba(249,115,22,.3);
  color: #fb923c;
}

/* ─── Animasi ─────────────────────────── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
.fade-up { animation: fadeUp .3s ease forwards; }

/* ─── Responsive: mobile ──────────────── */
@media (max-width: 900px) {
  .panel-left { display: none; }
  .panel-right {
    background: #f8f7f3;
    justify-content: flex-start;
    padding: 32px 20px 40px;
  }
  .form-wrap { max-width: 100%; }
}
</style>
</head>
<body>

<div class="shell">

  <!-- ══ PANEL KIRI ══ -->
  <section class="panel-left">
    <div class="dot-grid"></div>
    <div class="top-bar"></div>

    <!-- Logo + brand -->
    <div style="position:relative;z-index:1;">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:52px;">
        <div style="width:46px;height:46px;border-radius:13px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#fff;box-shadow:0 6px 24px rgba(249,115,22,.45);flex-shrink:0;">DPU</div>
        <div>
          <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#fff;letter-spacing:-.01em;">Dian Pilar Utama</div>
          <div style="font-size:10px;font-weight:700;color:rgba(249,115,22,.75);text-transform:uppercase;letter-spacing:.12em;margin-top:2px;">Sistem Inventaris v1</div>
        </div>
      </div>

      <!-- Headline -->
      <h1 style="font-weight:800;font-size:44px;color:#fff;line-height:1.1;margin-bottom:18px;letter-spacing:-.02em;">
        Kelola Aset.<br/>
        <span style="color:#f97316;">Efisien.</span><br/>
        Terpusat.
      </h1>
      <p style="color:#94a3b8;font-size:14px;line-height:1.75;max-width:320px;">
        Platform manajemen inventaris terpadu untuk seluruh cabang PT. Dian Pilar Utama — dari Pekanbaru hingga seluruh nusantara.
      </p>

      <!-- Feature bullets -->
      <div style="margin-top:32px;display:flex;flex-direction:column;gap:10px;">
        @foreach([
          ['qr_code_2',    'Scan QR & cetak label aset'],
          ['swap_horiz',   'Mutasi aset antar kantor'],
          ['bar_chart',    'Laporan & audit log lengkap'],
        ] as [$icon, $text])
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:30px;height:30px;border-radius:8px;background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:15px;">{{ $icon }}</span>
          </div>
          <span style="font-size:13px;color:#94a3b8;font-weight:500;">{{ $text }}</span>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Statistik + lokasi -->
    <div style="position:relative;z-index:1;">
      <!-- Divider -->
      <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(255,255,255,.08),transparent);margin-bottom:24px;"></div>

      <div style="display:flex;gap:28px;margin-bottom:24px;">
        <div class="stat-item"><div class="num">1.2K+</div><div class="lbl">Aset Terdaftar</div></div>
        <div class="stat-item"><div class="num">4</div><div class="lbl">Kantor Aktif</div></div>
        <div class="stat-item"><div class="num">99.8%</div><div class="lbl">Uptime</div></div>
      </div>

      <div style="display:flex;flex-wrap:wrap;gap:6px;">
        @foreach(['Pekanbaru','Tebet Jakarta','Surabaya','Bekasi'] as $k)
        <span class="loc-pill">{{ $k }}</span>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ══ PANEL KANAN ══ -->
  <section class="panel-right">

    <div class="form-wrap fade-up">

      <!-- Logo mobile only -->
      <div style="display:none;" class="mobile-logo">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:28px;">
          <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:13px;color:#fff;">DPU</div>
          <div>
            <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;">Dian Pilar Utama</div>
            <div style="font-size:10px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.08em;">Sistem Inventaris v1</div>
          </div>
        </div>
      </div>

      <!-- Tab Switch -->
      <div class="tab-wrap">
        <button class="tab-btn active" id="tab-admin" onclick="switchTab('admin')">
          <span class="material-symbols-outlined" style="font-size:15px;vertical-align:-3px;margin-right:4px;">admin_panel_settings</span>Admin
        </button>
        <button class="tab-btn" id="tab-operator" onclick="switchTab('operator')">
          <span class="material-symbols-outlined" style="font-size:15px;vertical-align:-3px;margin-right:4px;">badge</span>Operator
        </button>
      </div>

      <!-- Error -->
      @if($errors->any())
      <div class="alert-error">
        <span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#ef4444;flex-shrink:0;">error</span>
        {{ $errors->first() }}
      </div>
      @endif

      <!-- ── FORM ADMIN ── -->
      <div id="form-admin" class="login-card">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
          <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:18px;">admin_panel_settings</span>
          </div>
          <div>
            <h2 style="font-weight:800;font-size:18px;color:#0f172a;line-height:1.2;">Selamat Datang!</h2>
            <p style="font-size:12px;color:#94a3b8;margin-top:1px;">Akses penuh ke semua kantor & data.</p>
          </div>
        </div>

        <form method="POST" action="{{ route('login.post') }}" style="display:flex;flex-direction:column;gap:14px;">
          @csrf
          <input type="hidden" name="role" value="admin"/>

          <div>
            <label class="field-label">Email</label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">person</span>
              <input type="email" name="email" placeholder="admin@dianpilar.co.id" class="field" style="padding-left:40px;" value="{{ old('email', 'admin@dianpilar.co.id') }}"/>
            </div>
          </div>

          <div>
            <label class="field-label">Kata Sandi</label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">lock</span>
              <input id="admin-pass" type="password" name="password" placeholder="Kata sandi" class="field" style="padding-left:40px;padding-right:42px;" value="admin123"/>
              <button type="button" onclick="togglePass('admin-pass',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:none;cursor:pointer;color:#cbd5e1;padding:4px;display:flex;align-items:center;">
                <span class="material-symbols-outlined" style="font-size:17px;">visibility</span>
              </button>
            </div>
          </div>

          <div class="demo-badge">
            <span class="material-symbols-outlined fill-icon" style="font-size:15px;color:#16a34a;flex-shrink:0;">info</span>
            <span><strong>Demo:</strong> admin@dianpilar.co.id / admin123</span>
          </div>

          <button type="submit" class="btn-or">
            <span class="material-symbols-outlined fill-icon" style="font-size:18px;">login</span>
            Masuk ke Sistem
          </button>
        </form>
      </div>

    <!-- ── FORM OPERATOR ── -->
<div id="form-operator" class="login-card" style="display:none;">
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:18px;">badge</span>
    </div>
    <div>
      <h2 style="font-weight:800;font-size:18px;color:#0f172a;line-height:1.2;">Login Operator</h2>
      <p style="font-size:12px;color:#94a3b8;margin-top:1px;">Akses terbatas per kantor.</p>
    </div>
  </div>

  <!-- STEP 1: Email + Password -->
  <div id="op-step1" style="display:flex;flex-direction:column;gap:14px;">
    <div id="op-error" class="alert-error" style="display:none;">
      <span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#ef4444;flex-shrink:0;">error</span>
      <span id="op-error-msg"></span>
    </div>

    <div>
      <label class="field-label">Email</label>
      <div style="position:relative;">
        <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">person</span>
        <input id="op-email" type="email" placeholder="operator@dianpilar.co.id" class="field" style="padding-left:40px;" value="{{ old('email', 'operator@dianpilar.co.id') }}"/>
      </div>
    </div>

    <div>
      <label class="field-label">Kata Sandi</label>
      <div style="position:relative;">
        <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">lock</span>
        <input id="op-pass" type="password" placeholder="Kata sandi" class="field" style="padding-left:40px;padding-right:42px;" value="operator123"/>
        <button type="button" onclick="togglePass('op-pass',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:none;cursor:pointer;color:#cbd5e1;padding:4px;display:flex;align-items:center;">
          <span class="material-symbols-outlined" style="font-size:17px;">visibility</span>
        </button>
      </div>
    </div>

    <div class="demo-badge">
      <span class="material-symbols-outlined fill-icon" style="font-size:15px;color:#16a34a;flex-shrink:0;">info</span>
      <span><strong>Demo:</strong> operator@dianpilar.co.id / operator123</span>
    </div>

    <button type="button" onclick="opCheckCredentials()" id="op-check-btn" class="btn-or">
      <span class="material-symbols-outlined fill-icon" style="font-size:18px;">arrow_forward</span>
      Lanjutkan
    </button>
  </div>

  <!-- STEP 2: Pilih Kantor (muncul setelah kredensial valid) -->
  <div id="op-step2" style="display:none;">
    <form method="POST" action="{{ route('login.post') }}" style="display:flex;flex-direction:column;gap:14px;">
      @csrf
      <input type="hidden" name="role" value="operator"/>
      <input type="hidden" id="op-email-hidden" name="email"/>
      <input type="hidden" id="op-pass-hidden" name="password"/>

      <!-- Selamat datang strip -->
      <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe;border-radius:11px;padding:11px 14px;display:flex;align-items:center;gap:9px;">
        <span class="material-symbols-outlined fill-icon" style="font-size:17px;color:#2563eb;flex-shrink:0;">verified_user</span>
        <div>
          <div style="font-size:12px;font-weight:700;color:#1e3a8a;">Identitas terverifikasi</div>
          <div id="op-greeting" style="font-size:11px;color:#3b82f6;margin-top:1px;"></div>
        </div>
      </div>

      <!-- Pilih kantor -->
      <div>
        <label class="field-label">Pilih Kantor Anda</label>
        <div style="position:relative;">
          <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;pointer-events:none;z-index:1;">location_on</span>
          <select id="op-kantor-select" name="kantor" class="field" style="padding-left:40px;">
            <!-- diisi oleh JS -->
          </select>
        </div>
        <p id="op-kantor-hint" style="font-size:11px;color:#94a3b8;margin-top:5px;padding-left:2px;"></p>
      </div>

      <div style="display:flex;gap:8px;">
        <button type="button" onclick="opBackToStep1()" class="btn-ghost" style="flex:0 0 auto;">
          <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        </button>
        <button type="submit" class="btn-or" style="flex:1;">
          <span class="material-symbols-outlined fill-icon" style="font-size:18px;">login</span>
          Masuk ke Sistem
        </button>
      </div>
    </form>
  </div>
</div>

      <!-- Footer -->
      <p style="text-align:center;font-size:11px;color:#cbd5e1;margin-top:20px;">
        PT. Dian Pilar Utama &copy; {{ date('Y') }} · Sistem Inventaris v1.0
      </p>
    </div>

  </section>
</div>

<script>

  /* ── Operator 2-step login ── */
function opCheckCredentials() {
  var btn   = document.getElementById('op-check-btn');
  var email = document.getElementById('op-email').value.trim();
  var pass  = document.getElementById('op-pass').value;
  var errEl = document.getElementById('op-error');
  var errMsg= document.getElementById('op-error-msg');

  if (!email || !pass) {
    errMsg.textContent = 'Email dan kata sandi wajib diisi.';
    errEl.style.display = 'flex';
    return;
  }

  // Loading state
  btn.disabled = true;
  btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;animation:spin .8s linear infinite;">progress_activity</span> Memeriksa...';
  errEl.style.display = 'none';

  fetch("{{ route('login.check') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') 
                      ? document.querySelector('meta[name="csrf-token"]').content 
                      : '{{ csrf_token() }}'
    },
    body: JSON.stringify({ email: email, password: pass })
  })
  .then(function(res) { return res.json().then(function(d){ return {ok: res.ok, data: d}; }); })
  .then(function(r) {
    if (!r.ok) {
      errMsg.textContent = r.data.error || 'Terjadi kesalahan.';
      errEl.style.display = 'flex';
      btn.disabled = false;
      btn.innerHTML = '<span class="material-symbols-outlined fill-icon" style="font-size:18px;">arrow_forward</span> Lanjutkan';
      return;
    }

    // Isi step 2
    document.getElementById('op-email-hidden').value = email;
    document.getElementById('op-pass-hidden').value  = pass;
    document.getElementById('op-greeting').textContent = 'Halo, ' + r.data.nama + '!';

    var sel = document.getElementById('op-kantor-select');
    sel.innerHTML = '';
    r.data.kantors.forEach(function(k) {
      var opt = document.createElement('option');
      opt.value = k.value;
      opt.textContent = k.label;
      sel.appendChild(opt);
    });

    var hint = document.getElementById('op-kantor-hint');
    hint.textContent = r.data.kantors.length === 1
      ? 'Anda terdaftar di 1 kantor.'
      : 'Anda memiliki akses ke ' + r.data.kantors.length + ' kantor.';

    // Transisi ke step 2
    var s1 = document.getElementById('op-step1');
    var s2 = document.getElementById('op-step2');
    s1.style.transition = 'opacity .18s';
    s1.style.opacity = '0';
    setTimeout(function() {
      s1.style.display = 'none';
      s2.style.opacity = '0';
      s2.style.display = 'block';
      s2.style.transition = 'opacity .2s';
      requestAnimationFrame(function(){ s2.style.opacity = '1'; });
    }, 180);
  })
  .catch(function() {
    errMsg.textContent = 'Gagal terhubung ke server.';
    errEl.style.display = 'flex';
    btn.disabled = false;
    btn.innerHTML = '<span class="material-symbols-outlined fill-icon" style="font-size:18px;">arrow_forward</span> Lanjutkan';
  });
}

function opBackToStep1() {
  var s1 = document.getElementById('op-step1');
  var s2 = document.getElementById('op-step2');
  s2.style.opacity = '0';
  setTimeout(function() {
    s2.style.display = 'none';
    s1.style.display = 'flex';
    s1.style.flexDirection = 'column';
    s1.style.gap = '14px';
    requestAnimationFrame(function(){ s1.style.opacity = '1'; });
  }, 180);
}

/* animasi spinner */
var styleEl = document.createElement('style');
styleEl.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(styleEl);
/* ── Tab switch ── */
function switchTab(tab) {
  var isAdmin = tab === 'admin';
  var fAdmin  = document.getElementById('form-admin');
  var fOp     = document.getElementById('form-operator');
  var tAdmin  = document.getElementById('tab-admin');
  var tOp     = document.getElementById('tab-operator');

  fAdmin.style.display = isAdmin ? 'block' : 'none';
  fOp.style.display    = isAdmin ? 'none' : 'block';
  tAdmin.className     = 'tab-btn' + (isAdmin ? ' active' : '');
  tOp.className        = 'tab-btn' + (!isAdmin ? ' active' : '');

  /* Animasi ringan saat switch */
  var active = isAdmin ? fAdmin : fOp;
  active.style.opacity = '0';
  active.style.transform = 'translateY(6px)';
  requestAnimationFrame(function() {
    active.style.transition = 'opacity .2s ease, transform .2s ease';
    active.style.opacity = '1';
    active.style.transform = 'translateY(0)';
  });
}

/* ── Toggle password ── */
function togglePass(id, btn) {
  var inp  = document.getElementById(id);
  var icon = btn.querySelector('.material-symbols-outlined');
  if (inp.type === 'password') {
    inp.type = 'text';
    icon.textContent = 'visibility_off';
  } else {
    inp.type = 'password';
    icon.textContent = 'visibility';
  }
}

/* ── Mobile logo visibility ── */
(function() {
  var ml = document.querySelector('.mobile-logo');
  if (ml && window.innerWidth <= 900) ml.style.display = 'block';
  window.addEventListener('resize', function() {
    if (!ml) return;
    ml.style.display = window.innerWidth <= 900 ? 'block' : 'none';
  });
})();

/* ── Auto-switch tab jika ada error dari server ── */
@if(session('login_tab') === 'operator' || old('role') === 'operator')
switchTab('operator');
@endif
</script>
</body>
</html>