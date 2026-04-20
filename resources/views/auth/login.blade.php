<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Login | Sistem Inventaris DPU</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
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

html, body { min-height: 100vh; background: #f1f0eb; }

.shell { display: flex; min-height: 100vh; }

/* ── Panel Kiri ── */
.panel-left {
  width: 42%; flex-shrink: 0;
  background: linear-gradient(160deg, #0f172a 0%, #1a2744 50%, #0f172a 100%);
  display: flex; flex-direction: column; justify-content: space-between;
  padding: 44px 48px; position: relative; overflow: hidden; min-height: 100vh;
}
.panel-left::before {
  content: ''; position: absolute; top: -100px; left: -100px;
  width: 500px; height: 500px;
  background: radial-gradient(circle, rgba(249,115,22,.12) 0%, transparent 70%);
  pointer-events: none;
}
.panel-left::after {
  content: ''; position: absolute; bottom: -80px; right: -80px;
  width: 420px; height: 420px;
  background: radial-gradient(circle, rgba(234,88,12,.1) 0%, transparent 70%);
  pointer-events: none;
}
.dot-grid {
  position: absolute; inset: 0; opacity: .025;
  background-image: radial-gradient(#f97316 1px, transparent 1px);
  background-size: 28px 28px; pointer-events: none;
}
.top-bar {
  position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, transparent, #f97316 40%, #ea580c 60%, transparent);
}

/* ── Panel Kanan ── */
.panel-right {
  flex: 1; display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  padding: 40px 28px; position: relative; min-height: 100vh;
  background: #f8f7f3;
}
.panel-right::before {
  content: ''; position: absolute; top: 0; right: 0;
  width: 280px; height: 280px;
  background: radial-gradient(circle at top right, rgba(249,115,22,.06), transparent 70%);
  pointer-events: none;
}
.panel-right::after {
  content: ''; position: absolute; bottom: 0; left: 0;
  width: 220px; height: 220px;
  background: radial-gradient(circle at bottom left, rgba(249,115,22,.04), transparent 70%);
  pointer-events: none;
}

/* ── Form wrap ── */
.form-wrap { width: 100%; max-width: 400px; position: relative; z-index: 1; }

.login-card {
  background: #fff; border-radius: 22px; padding: 28px 26px;
  box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.07), 0 24px 48px rgba(0,0,0,.05);
  border: 1px solid rgba(0,0,0,.06);
}

/* ── Tab Switch ── */
.tab-wrap {
  display: flex; background: #f1f0eb; padding: 4px;
  border-radius: 14px; margin-bottom: 20px; gap: 4px;
}
.tab-btn {
  flex: 1; padding: 9px 16px; font-size: 13px; font-weight: 600;
  border: none; cursor: pointer; border-radius: 11px;
  transition: all .2s cubic-bezier(.34,1.2,.64,1);
  background: transparent; color: #94a3b8; font-family: 'DM Sans', sans-serif;
}
.tab-btn.active { background: #fff; color: #0f172a; box-shadow: 0 2px 8px rgba(0,0,0,.1); }

/* ── Fields ── */
.field-label {
  display: block; font-size: 10px; font-weight: 700; color: #94a3b8;
  text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px;
}
.field {
  width: 100%; background: #f8fafc; border: 1.5px solid #e8eaf0;
  border-radius: 11px; padding: 11px 14px; font-size: 13.5px;
  transition: all .15s; outline: none; color: #0f172a;
  font-family: 'DM Sans', sans-serif;
}
.field:focus { border-color: #f97316; background: #fff; box-shadow: 0 0 0 3px rgba(249,115,22,.1); }
select.field { cursor: pointer; appearance: auto; }

/* ── Buttons ── */
.btn-or {
  width: 100%; background: linear-gradient(135deg, #f97316, #ea580c);
  color: #fff; font-weight: 700; border-radius: 12px; padding: 13px 22px;
  font-size: 14px; transition: all .18s; box-shadow: 0 4px 16px rgba(249,115,22,.3);
  border: none; cursor: pointer; display: flex; align-items: center;
  justify-content: center; gap: 8px; font-family: 'DM Sans', sans-serif;
  letter-spacing: .01em;
}
.btn-or:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(249,115,22,.4); }
.btn-or:active { transform: translateY(0); }
.btn-or:disabled { opacity: .65; cursor: not-allowed; transform: none; }

.btn-ghost {
  background: #f1f5f9; border: 1.5px solid #e2e8f0; border-radius: 10px;
  padding: 11px 14px; cursor: pointer; display: flex; align-items: center;
  justify-content: center; transition: all .15s; color: #64748b;
}
.btn-ghost:hover { background: #e2e8f0; color: #0f172a; }

/* ── Alert ── */
.alert-error {
  background: #fef2f2; border: 1px solid #fecaca; border-radius: 11px;
  padding: 11px 14px; margin-bottom: 16px; font-size: 12.5px; color: #991b1b;
  font-weight: 600; display: flex; align-items: center; gap: 8px;
}

/* ── Demo badge ── */
.demo-badge {
  background: linear-gradient(135deg, #f0fdf4, #dcfce7);
  border: 1px solid #bbf7d0; border-radius: 10px; padding: 10px 14px;
  font-size: 11.5px; color: #166534; display: flex; align-items: center; gap: 7px;
}

/* ── Register strip ── */
.register-strip {
  display: flex; align-items: center; justify-content: center; gap: 6px;
  background: #f8f7f3; border: 1.5px dashed #e2e8f0; border-radius: 12px;
  padding: 11px 14px; margin-top: 12px; text-decoration: none;
  transition: all .18s; cursor: pointer;
}
.register-strip:hover {
  border-color: #f97316; background: #fff7ed;
}
.register-strip:hover .reg-label { color: #ea580c; }
.register-strip:hover .reg-icon  { background: linear-gradient(135deg,#f97316,#ea580c); }
.register-strip:hover .reg-icon span { color: #fff !important; }
.reg-icon {
  width: 28px; height: 28px; border-radius: 8px; background: #f1f5f9;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  transition: all .18s;
}
.reg-text { font-size: 11.5px; color: #94a3b8; font-weight: 500; }
.reg-label { font-size: 12px; font-weight: 700; color: #64748b; transition: color .18s; }

/* ── Step Indicator ── */
.step-indicator {
  display: flex; align-items: center; gap: 0; margin-bottom: 20px;
}
.step-item {
  display: flex; flex-direction: column; align-items: center; gap: 4px;
  flex: 1; position: relative;
}
.step-circle {
  width: 32px; height: 32px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700; font-family: 'Sora', sans-serif;
  transition: all .3s cubic-bezier(.34,1.2,.64,1);
  position: relative; z-index: 1;
}
.step-circle.idle {
  background: #f1f5f9; color: #94a3b8; border: 2px solid #e2e8f0;
}
.step-circle.active {
  background: #fff; color: #3b82f6;
  border: 2px solid #3b82f6;
  box-shadow: 0 0 0 4px rgba(59,130,246,.12);
}
.step-circle.done {
  background: linear-gradient(135deg, #22c55e, #16a34a);
  color: #fff; border: 2px solid transparent;
  box-shadow: 0 2px 8px rgba(34,197,94,.3);
}
.step-label {
  font-size: 10px; font-weight: 600; text-transform: uppercase;
  letter-spacing: .06em; white-space: nowrap;
  transition: color .3s;
}
.step-label.idle { color: #cbd5e1; }
.step-label.active { color: #3b82f6; }
.step-label.done { color: #16a34a; }
.step-line {
  height: 2px; flex: 1; margin: 0 -1px; margin-bottom: 16px;
  transition: background .4s;
}
.step-line.done { background: linear-gradient(90deg, #22c55e, #3b82f6); }
.step-line.idle { background: #e2e8f0; }

/* ── Kantor cards ── */
.kantor-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 4px; }
.kantor-card {
  border: 1.5px solid #e8eaf0; border-radius: 12px; padding: 11px 14px;
  display: flex; align-items: center; justify-content: space-between;
  cursor: pointer; transition: all .18s; background: #f8fafc;
}
.kantor-card:hover { border-color: #93c5fd; background: #eff6ff; }
.kantor-card.selected { border-color: #3b82f6; background: #eff6ff; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
.kantor-card-name { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
.kantor-card-loc { font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 4px; }
.kantor-radio {
  width: 18px; height: 18px; border-radius: 50%; border: 2px solid #cbd5e1;
  display: flex; align-items: center; justify-content: center;
  transition: all .18s; flex-shrink: 0;
}
.kantor-card.selected .kantor-radio { border-color: #3b82f6; background: #3b82f6; }
.kantor-radio-dot {
  width: 7px; height: 7px; border-radius: 50%; background: #fff;
  opacity: 0; transition: opacity .15s;
}
.kantor-card.selected .kantor-radio-dot { opacity: 1; }

/* ── Verify strip ── */
.verify-strip {
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  border: 1px solid #bfdbfe; border-radius: 11px;
  padding: 11px 14px; display: flex; align-items: center; gap: 9px;
  margin-bottom: 14px;
}

/* ── Stat item ── */
.stat-item .num {
  font-family: 'Sora', sans-serif; font-size: 30px; font-weight: 800;
  color: #fff; line-height: 1; margin-bottom: 4px;
}
.stat-item .lbl { font-size: 10px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; }

/* ── Location pill ── */
.loc-pill {
  padding: 5px 14px; background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1); border-radius: 999px;
  color: #94a3b8; font-size: 11.5px; font-weight: 600; transition: all .15s;
}
.loc-pill:hover { background: rgba(249,115,22,.15); border-color: rgba(249,115,22,.3); color: #fb923c; }

/* ── Animations ── */
@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
@keyframes spin { to { transform: rotate(360deg); } }
.fade-up { animation: fadeUp .3s ease forwards; }

.panel-anim { transition: opacity .22s ease, transform .22s ease; }
.panel-anim.out { opacity: 0; transform: translateY(6px); pointer-events: none; }
.panel-anim.in { opacity: 1; transform: translateY(0); }

/* ── Responsive ── */
@media (max-width: 900px) {
  .panel-left { display: none; }
  .panel-right { background: #f8f7f3; justify-content: flex-start; padding: 32px 20px 40px; }
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

    <div style="position:relative;z-index:1;">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:52px;">
        <div style="width:46px;height:46px;border-radius:13px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#fff;box-shadow:0 6px 24px rgba(249,115,22,.45);flex-shrink:0;">DPU</div>
        <div>
          <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#fff;letter-spacing:-.01em;">Dian Pilar Utama</div>
          <div style="font-size:10px;font-weight:700;color:rgba(249,115,22,.75);text-transform:uppercase;letter-spacing:.12em;margin-top:2px;">Sistem Inventaris v1</div>
        </div>
      </div>

      <h1 style="font-weight:800;font-size:44px;color:#fff;line-height:1.1;margin-bottom:18px;letter-spacing:-.02em;">
        Kelola Aset.<br/>
        <span style="color:#f97316;">Efisien.</span><br/>
        Terpusat.
      </h1>
      <p style="color:#94a3b8;font-size:14px;line-height:1.75;max-width:320px;">
        Platform manajemen inventaris terpadu untuk seluruh cabang PT. Dian Pilar Utama — dari Pekanbaru hingga seluruh nusantara.
      </p>

      <div style="margin-top:32px;display:flex;flex-direction:column;gap:10px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:30px;height:30px;border-radius:8px;background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:15px;">qr_code_2</span>
          </div>
          <span style="font-size:13px;color:#94a3b8;font-weight:500;">Scan QR & cetak label aset</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:30px;height:30px;border-radius:8px;background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:15px;">swap_horiz</span>
          </div>
          <span style="font-size:13px;color:#94a3b8;font-weight:500;">Mutasi aset antar kantor</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:30px;height:30px;border-radius:8px;background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:15px;">bar_chart</span>
          </div>
          <span style="font-size:13px;color:#94a3b8;font-weight:500;">Laporan & audit log lengkap</span>
        </div>
      </div>
    </div>

    <div style="position:relative;z-index:1;">
      <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(255,255,255,.08),transparent);margin-bottom:24px;"></div>
      <div style="display:flex;gap:28px;margin-bottom:24px;">
        <div class="stat-item"><div class="num">1.2K+</div><div class="lbl">Aset Terdaftar</div></div>
        <div class="stat-item"><div class="num">4</div><div class="lbl">Kantor Aktif</div></div>
        <div class="stat-item"><div class="num">99.8%</div><div class="lbl">Uptime</div></div>
      </div>
      <div style="display:flex;flex-wrap:wrap;gap:6px;">
        <span class="loc-pill">Pekanbaru</span>
        <span class="loc-pill">Tebet Jakarta</span>
        <span class="loc-pill">Surabaya</span>
        <span class="loc-pill">Bekasi</span>
      </div>
    </div>
  </section>

  <!-- ══ PANEL KANAN ══ -->
  <section class="panel-right">
    <div class="form-wrap fade-up">

      <!-- Logo mobile only -->
      <div class="mobile-logo" style="display:none;">
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

      <!-- Error dari server -->
      @if($errors->any())
      <div class="alert-error">
        <span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#ef4444;flex-shrink:0;">error</span>
        {{ $errors->first() }}
      </div>
      @endif

      <!-- ══ FORM ADMIN ══ -->
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

        <!-- ── Register link (Admin) ── -->
        <a href="{{ route('register') }}" class="register-strip">
          <div class="reg-icon">
            <span class="material-symbols-outlined fill-icon" style="color:#94a3b8;font-size:15px;">how_to_reg</span>
          </div>
          <div>
            <div class="reg-label">Belum punya akun?</div>
            <div class="reg-text">Ajukan registrasi — disetujui Admin</div>
          </div>
          <span class="material-symbols-outlined" style="color:#cbd5e1;font-size:16px;margin-left:auto;">chevron_right</span>
        </a>
      </div>

      <!-- ══ FORM OPERATOR ══ -->
      <div id="form-operator" class="login-card" style="display:none;">

        <!-- Header -->
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
          <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:18px;">badge</span>
          </div>
          <div>
            <h2 style="font-weight:800;font-size:18px;color:#0f172a;line-height:1.2;">Login Operator</h2>
            <p style="font-size:12px;color:#94a3b8;margin-top:1px;">Akses terbatas per kantor.</p>
          </div>
        </div>

        <!-- ── Step Indicator ── -->
        <div class="step-indicator" id="step-indicator">
          <div class="step-item">
            <div class="step-circle active" id="sc-1">1</div>
            <div class="step-label active" id="sl-1">Identitas</div>
          </div>
          <div class="step-line idle" id="line-1" style="margin-bottom:18px;"></div>
          <div class="step-item">
            <div class="step-circle idle" id="sc-2">2</div>
            <div class="step-label idle" id="sl-2">Kantor</div>
          </div>
          <div class="step-line idle" id="line-2" style="margin-bottom:18px;"></div>
          <div class="step-item">
            <div class="step-circle idle" id="sc-3">3</div>
            <div class="step-label idle" id="sl-3">Masuk</div>
          </div>
        </div>

        <!-- ══ STEP 1: Email + Password ══ -->
        <div id="op-step1" class="panel-anim in" style="display:flex;flex-direction:column;gap:14px;">

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

          <!-- ── Register link (Operator) ── -->
          <a href="{{ route('register') }}" class="register-strip">
            <div class="reg-icon">
              <span class="material-symbols-outlined fill-icon" style="color:#94a3b8;font-size:15px;">how_to_reg</span>
            </div>
            <div>
              <div class="reg-label">Belum punya akun?</div>
              <div class="reg-text">Ajukan registrasi — disetujui Admin</div>
            </div>
            <span class="material-symbols-outlined" style="color:#cbd5e1;font-size:16px;margin-left:auto;">chevron_right</span>
          </a>
        </div>

        <!-- ══ STEP 2: Pilih Kantor ══ -->
        <div id="op-step2" class="panel-anim" style="display:none;">
          <div class="verify-strip">
            <span class="material-symbols-outlined fill-icon" style="font-size:17px;color:#2563eb;flex-shrink:0;">verified_user</span>
            <div>
              <div style="font-size:12px;font-weight:700;color:#1e3a8a;">Identitas terverifikasi</div>
              <div id="op-greeting" style="font-size:11px;color:#3b82f6;margin-top:1px;"></div>
            </div>
          </div>
          <div style="margin-bottom:10px;">
            <label class="field-label">Pilih Kantor Anda</label>
            <p id="op-kantor-hint" style="font-size:11px;color:#94a3b8;margin-top:2px;"></p>
          </div>
          <div class="kantor-list" id="op-kantor-list"></div>
          <div style="display:flex;gap:8px;margin-top:16px;">
            <button type="button" onclick="opBackToStep1()" class="btn-ghost">
              <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            </button>
            <button type="button" onclick="opSubmitLogin()" id="op-submit-btn" class="btn-or" style="flex:1;">
              <span class="material-symbols-outlined fill-icon" style="font-size:18px;">login</span>
              Masuk ke Sistem
            </button>
          </div>
          <form id="op-final-form" method="POST" action="{{ route('login.post') }}" style="display:none;">
            @csrf
            <input type="hidden" name="role" value="operator"/>
            <input type="hidden" id="op-email-hidden" name="email"/>
            <input type="hidden" id="op-pass-hidden" name="password"/>
            <input type="hidden" id="op-kantor-hidden" name="kantor"/>
          </form>
        </div>

        <!-- ══ STEP 3: Loading ══ -->
        <div id="op-step3" class="panel-anim" style="display:none;flex-direction:column;align-items:center;justify-content:center;gap:14px;padding:20px 0;text-align:center;">
          <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#22c55e,#16a34a);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(34,197,94,.3);">
            <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:26px;animation:spin .9s linear infinite;" id="step3-spinner">progress_activity</span>
          </div>
          <div>
            <div style="font-weight:700;font-size:15px;color:#0f172a;" id="step3-title">Menyiapkan sesi...</div>
            <div style="font-size:12px;color:#94a3b8;margin-top:4px;" id="step3-sub">Mohon tunggu sebentar</div>
          </div>
        </div>

      </div>
      <!-- /form-operator -->

      <p style="text-align:center;font-size:11px;color:#cbd5e1;margin-top:20px;">
        PT. Dian Pilar Utama &copy; {{ date('Y') }} · Sistem Inventaris v1.0
      </p>
    </div>
  </section>
</div>

<script>
function setStep(step) {
  for (var i = 1; i <= 3; i++) {
    var sc = document.getElementById('sc-' + i);
    var sl = document.getElementById('sl-' + i);
    sc.className = 'step-circle ' + (i < step ? 'done' : i === step ? 'active' : 'idle');
    sl.className = 'step-label ' + (i < step ? 'done' : i === step ? 'active' : 'idle');
    sc.innerHTML = i < step
      ? '<span class="material-symbols-outlined fill-icon" style="font-size:14px;color:#fff;">check</span>'
      : i;
  }
  document.getElementById('line-1').className = 'step-line ' + (step > 1 ? 'done' : 'idle');
  document.getElementById('line-2').className = 'step-line ' + (step > 2 ? 'done' : 'idle');
}

function transitionTo(fromId, toId, afterFn) {
  var from = document.getElementById(fromId);
  var to   = document.getElementById(toId);
  from.classList.add('out');
  setTimeout(function() {
    from.style.display = 'none';
    from.classList.remove('out');
    to.style.display = 'flex';
    to.style.flexDirection = 'column';
    to.style.gap = '14px';
    to.classList.remove('out');
    to.classList.add('in');
    if (afterFn) afterFn();
  }, 220);
}

function opCheckCredentials() {
  var btn    = document.getElementById('op-check-btn');
  var email  = document.getElementById('op-email').value.trim();
  var pass   = document.getElementById('op-pass').value;
  var errEl  = document.getElementById('op-error');
  var errMsg = document.getElementById('op-error-msg');

  if (!email || !pass) {
    errMsg.textContent = 'Email dan kata sandi wajib diisi.';
    errEl.style.display = 'flex';
    return;
  }

  btn.disabled = true;
  btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;animation:spin .8s linear infinite;">progress_activity</span>&nbsp;Memeriksa...';
  errEl.style.display = 'none';

  fetch("{{ route('login.check') }}", {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ email: email, password: pass })
  })
  .then(function(res) { return res.json().then(function(d){ return { ok: res.ok, data: d }; }); })
  .then(function(r) {
    btn.disabled = false;
    btn.innerHTML = '<span class="material-symbols-outlined fill-icon" style="font-size:18px;">arrow_forward</span> Lanjutkan';

    if (!r.ok) {
      errMsg.textContent = r.data.error || 'Terjadi kesalahan.';
      errEl.style.display = 'flex';
      return;
    }

    var kantors = r.data.kantors;

    if (kantors.length === 1) {
      document.getElementById('op-email-hidden').value  = email;
      document.getElementById('op-pass-hidden').value   = pass;
      document.getElementById('op-kantor-hidden').value = kantors[0].value;
      setStep(3);
      transitionTo('op-step1', 'op-step3', function() {
        document.getElementById('step3-title').textContent = 'Masuk ke ' + kantors[0].label + '...';
        document.getElementById('step3-sub').textContent   = 'Halo, ' + r.data.nama + '!';
        setTimeout(function() { document.getElementById('op-final-form').submit(); }, 900);
      });
      return;
    }

    document.getElementById('op-greeting').textContent = 'Halo, ' + r.data.nama + '!';
    document.getElementById('op-kantor-hint').textContent =
      'Anda memiliki akses ke ' + kantors.length + ' kantor. Pilih salah satu.';

    var list = document.getElementById('op-kantor-list');
    list.innerHTML = '';
    var selectedValue = kantors[0].value;

    kantors.forEach(function(k, idx) {
      var card = document.createElement('div');
      card.className = 'kantor-card' + (idx === 0 ? ' selected' : '');
      card.dataset.value = k.value;
      card.innerHTML =
        '<div><div class="kantor-card-name">' + k.label + '</div>' +
        '<div class="kantor-card-loc"><span class="material-symbols-outlined fill-icon" style="font-size:12px;color:#94a3b8;">location_on</span>' + k.label + '</div></div>' +
        '<div class="kantor-radio"><div class="kantor-radio-dot"></div></div>';
      card.addEventListener('click', function() {
        list.querySelectorAll('.kantor-card').forEach(function(c) { c.classList.remove('selected'); });
        card.classList.add('selected');
        selectedValue = k.value;
      });
      list.appendChild(card);
    });

    window._opKantors         = kantors;
    window._opEmail           = email;
    window._opPass            = pass;
    window._getSelectedKantor = function() { return selectedValue; };

    setStep(2);
    transitionTo('op-step1', 'op-step2');
  })
  .catch(function() {
    btn.disabled = false;
    btn.innerHTML = '<span class="material-symbols-outlined fill-icon" style="font-size:18px;">arrow_forward</span> Lanjutkan';
    errMsg.textContent = 'Gagal terhubung ke server.';
    errEl.style.display = 'flex';
  });
}

function opSubmitLogin() {
  var kantor  = window._getSelectedKantor ? window._getSelectedKantor() : '';
  var kantors = window._opKantors || [];
  var label   = '';
  kantors.forEach(function(k) { if (k.value === kantor) label = k.label; });

  document.getElementById('op-email-hidden').value  = window._opEmail || '';
  document.getElementById('op-pass-hidden').value   = window._opPass  || '';
  document.getElementById('op-kantor-hidden').value = kantor;

  setStep(3);
  transitionTo('op-step2', 'op-step3', function() {
    document.getElementById('step3-title').textContent = 'Masuk ke ' + (label || 'sistem') + '...';
    document.getElementById('step3-sub').textContent   = 'Menyiapkan sesi Anda';
    setTimeout(function() { document.getElementById('op-final-form').submit(); }, 900);
  });
}

function opBackToStep1() {
  setStep(1);
  var s2 = document.getElementById('op-step2');
  var s1 = document.getElementById('op-step1');
  s2.classList.add('out');
  setTimeout(function() {
    s2.style.display = 'none';
    s2.classList.remove('out');
    s1.style.display = 'flex';
    s1.style.flexDirection = 'column';
    s1.style.gap = '14px';
    s1.classList.add('in');
  }, 220);
}

function switchTab(tab) {
  var isAdmin = tab === 'admin';
  var fAdmin  = document.getElementById('form-admin');
  var fOp     = document.getElementById('form-operator');
  var tAdmin  = document.getElementById('tab-admin');
  var tOp     = document.getElementById('tab-operator');

  fAdmin.style.display = isAdmin ? 'block' : 'none';
  fOp.style.display    = isAdmin ? 'none'  : 'block';
  tAdmin.className     = 'tab-btn' + (isAdmin ? ' active' : '');
  tOp.className        = 'tab-btn' + (!isAdmin ? ' active' : '');

  var active = isAdmin ? fAdmin : fOp;
  active.style.opacity   = '0';
  active.style.transform = 'translateY(6px)';
  requestAnimationFrame(function() {
    active.style.transition = 'opacity .2s ease, transform .2s ease';
    active.style.opacity    = '1';
    active.style.transform  = 'translateY(0)';
  });
}

function togglePass(id, btn) {
  var inp  = document.getElementById(id);
  var icon = btn.querySelector('.material-symbols-outlined');
  inp.type = inp.type === 'password' ? 'text' : 'password';
  icon.textContent = inp.type === 'password' ? 'visibility' : 'visibility_off';
}

(function() {
  var ml = document.querySelector('.mobile-logo');
  function check() { if (ml) ml.style.display = window.innerWidth <= 900 ? 'block' : 'none'; }
  check();
  window.addEventListener('resize', check);
})();

@if(session('login_tab') === 'operator' || old('role') === 'operator')
switchTab('operator');
@endif
</script>
</body>
</html>