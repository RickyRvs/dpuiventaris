<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Daftar Akun | Sistem Inventaris DPU</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
*, *::before, *::after { font-family: 'DM Sans', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
h1, h2, h3, .sora { font-family: 'Sora', sans-serif; }
.material-symbols-outlined { font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; font-size: 20px; vertical-align: middle; line-height: 1; }
.fill-icon { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }
html, body { min-height: 100vh; background: #f1f0eb; }
.shell { display: flex; min-height: 100vh; }

.panel-left {
  width: 42%; flex-shrink: 0;
  background: linear-gradient(160deg, #0f172a 0%, #1a2744 50%, #0f172a 100%);
  display: flex; flex-direction: column; justify-content: space-between;
  padding: 44px 48px; position: relative; overflow: hidden; min-height: 100vh;
}
.panel-left::before { content: ''; position: absolute; top: -100px; left: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(249,115,22,.12) 0%, transparent 70%); pointer-events: none; }
.panel-left::after  { content: ''; position: absolute; bottom: -80px; right: -80px; width: 420px; height: 420px; background: radial-gradient(circle, rgba(234,88,12,.1) 0%, transparent 70%); pointer-events: none; }
.dot-grid { position: absolute; inset: 0; opacity: .025; background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 28px 28px; pointer-events: none; }
.top-bar { position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, transparent, #f97316 40%, #ea580c 60%, transparent); }

.panel-right { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px 28px; position: relative; min-height: 100vh; background: #f8f7f3; }
.form-wrap { width: 100%; max-width: 420px; position: relative; z-index: 1; }
.reg-card { background: #fff; border-radius: 22px; padding: 28px 26px; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.07), 0 24px 48px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.06); }

.step-indicator { display: flex; align-items: center; gap: 0; margin-bottom: 22px; }
.step-item { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; }
.step-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; font-family: 'Sora', sans-serif; transition: all .3s cubic-bezier(.34,1.2,.64,1); position: relative; z-index: 1; }
.step-circle.idle   { background: #f1f5f9; color: #94a3b8; border: 2px solid #e2e8f0; }
.step-circle.active { background: #fff; color: #f97316; border: 2px solid #f97316; box-shadow: 0 0 0 4px rgba(249,115,22,.12); }
.step-circle.done   { background: linear-gradient(135deg,#f97316,#ea580c); color: #fff; border: 2px solid transparent; box-shadow: 0 2px 8px rgba(249,115,22,.3); }
.step-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; transition: color .3s; }
.step-label.idle   { color: #cbd5e1; }
.step-label.active { color: #f97316; }
.step-label.done   { color: #ea580c; }
.step-line { height: 2px; flex: 1; margin: 0 -1px; margin-bottom: 16px; transition: background .4s; }
.step-line.done { background: linear-gradient(90deg,#f97316,#ea580c); }
.step-line.idle { background: #e2e8f0; }

.field-label { display: block; font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px; }
.field { width: 100%; background: #f8fafc; border: 1.5px solid #e8eaf0; border-radius: 11px; padding: 11px 14px; font-size: 13.5px; transition: all .15s; outline: none; color: #0f172a; font-family: 'DM Sans', sans-serif; }
.field:focus { border-color: #f97316; background: #fff; box-shadow: 0 0 0 3px rgba(249,115,22,.1); }
.field.error { border-color: #ef4444; background: #fef2f2; }

.strength-bar { display: flex; gap: 4px; margin-top: 6px; }
.strength-seg { height: 3px; flex: 1; border-radius: 999px; background: #e2e8f0; transition: background .3s; }
.strength-seg.weak   { background: #ef4444; }
.strength-seg.medium { background: #f59e0b; }
.strength-seg.strong { background: #22c55e; }
.strength-label { font-size: 10px; font-weight: 600; margin-top: 4px; transition: color .3s; }

.peran-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.peran-card { border: 1.5px solid #e8eaf0; border-radius: 12px; padding: 12px; cursor: pointer; transition: all .18s; background: #f8fafc; text-align: left; }
.peran-card:hover { border-color: #fdba74; background: #fff7ed; }
.peran-card.selected { border-color: #f97316; background: #fff7ed; box-shadow: 0 0 0 3px rgba(249,115,22,.1); }
.peran-icon { width: 32px; height: 32px; border-radius: 9px; margin-bottom: 7px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; transition: all .18s; }
.peran-card.selected .peran-icon { background: linear-gradient(135deg,#f97316,#ea580c); }
.peran-card.selected .peran-icon .material-symbols-outlined { color: #fff !important; }
.peran-title { font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
.peran-desc  { font-size: 10.5px; color: #94a3b8; line-height: 1.4; }

/* ✅ Kantor multi-pilih */
.kantor-list { display: flex; flex-direction: column; gap: 7px; }
.kantor-card { border: 1.5px solid #e8eaf0; border-radius: 11px; padding: 10px 13px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all .18s; background: #f8fafc; }
.kantor-card:hover    { border-color: #fdba74; background: #fff7ed; }
.kantor-card.selected { border-color: #f97316; background: #fff7ed; box-shadow: 0 0 0 3px rgba(249,115,22,.1); }
.kantor-card-name { font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 1px; }
.kantor-card-loc  { font-size: 10.5px; color: #94a3b8; display: flex; align-items: center; gap: 3px; }
.kantor-check { width: 20px; height: 20px; border-radius: 6px; border: 2px solid #cbd5e1; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all .18s; background: #fff; }
.kantor-card.selected .kantor-check { border-color: #f97316; background: #f97316; }
.kantor-check-icon { display: none; color: #fff; font-size: 14px !important; }
.kantor-card.selected .kantor-check-icon { display: block; }
.kantor-count-badge { font-size: 11px; color: #f97316; font-weight: 700; margin-top: 6px; display: none; }

.btn-or { width: 100%; background: linear-gradient(135deg,#f97316,#ea580c); color: #fff; font-weight: 700; border-radius: 12px; padding: 13px 22px; font-size: 14px; transition: all .18s; box-shadow: 0 4px 16px rgba(249,115,22,.3); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-family: 'DM Sans', sans-serif; }
.btn-or:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(249,115,22,.4); }
.btn-or:disabled { opacity: .65; cursor: not-allowed; transform: none; }
.btn-ghost { background: #f1f5f9; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 11px 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .15s; color: #64748b; }
.btn-ghost:hover { background: #e2e8f0; color: #0f172a; }

.alert-error { background: #fef2f2; border: 1px solid #fecaca; border-radius: 11px; padding: 11px 14px; margin-bottom: 14px; font-size: 12.5px; color: #991b1b; font-weight: 600; display: flex; align-items: flex-start; gap: 8px; }
.field-error { font-size: 11px; color: #ef4444; font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.summary-strip { background: linear-gradient(135deg,#fff7ed,#ffedd5); border: 1px solid #fed7aa; border-radius: 11px; padding: 11px 14px; display: flex; align-items: flex-start; gap: 9px; margin-bottom: 14px; font-size: 12px; }
.info-badge { background: linear-gradient(135deg,#eff6ff,#dbeafe); border: 1px solid #bfdbfe; border-radius: 10px; padding: 10px 13px; font-size: 11.5px; color: #1e40af; display: flex; align-items: center; gap: 7px; }

.panel-anim { transition: opacity .22s ease, transform .22s ease; }
.panel-anim.out { opacity: 0; transform: translateY(8px); pointer-events: none; }
.panel-anim.in  { opacity: 1; transform: translateY(0); }

.stat-item .num { font-family: 'Sora', sans-serif; font-size: 30px; font-weight: 800; color: #fff; line-height: 1; margin-bottom: 4px; }
.stat-item .lbl { font-size: 10px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; }
.loc-pill { padding: 5px 14px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); border-radius: 999px; color: #94a3b8; font-size: 11.5px; font-weight: 600; }

@keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
@keyframes spin   { to { transform: rotate(360deg); } }
.fade-up { animation: fadeUp .35s ease forwards; }
textarea.field { resize: vertical; min-height: 76px; line-height: 1.5; }

@media (max-width: 900px) {
  .panel-left { display: none; }
  .panel-right { justify-content: flex-start; padding: 32px 20px 40px; }
  .form-wrap { max-width: 100%; }
}
</style>
</head>
<body>
<div class="shell">

  <section class="panel-left">
    <div class="dot-grid"></div>
    <div class="top-bar"></div>
    <div style="position:relative;z-index:1;">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:52px;">
        <div style="width:46px;height:46px;border-radius:13px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#fff;box-shadow:0 6px 24px rgba(249,115,22,.45);flex-shrink:0;">DPU</div>
        <div>
          <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#fff;letter-spacing:-.01em;">Dian Bangun Sejahtera</div>
          <div style="font-size:10px;font-weight:700;color:rgba(249,115,22,.75);text-transform:uppercase;letter-spacing:.12em;margin-top:2px;">Sistem Inventaris v1</div>
        </div>
      </div>
      <h1 style="font-weight:800;font-size:40px;color:#fff;line-height:1.1;margin-bottom:18px;letter-spacing:-.02em;">
        Bergabung.<br/><span style="color:#f97316;">Daftarkan</span><br/>Akunmu.
      </h1>
      <p style="color:#94a3b8;font-size:14px;line-height:1.75;max-width:320px;">Ajukan akses ke sistem inventaris PT. Dian Bangun Sejahtera. Pengajuan akan ditinjau oleh Admin sebelum akun diaktifkan.</p>
      <div style="margin-top:32px;display:flex;flex-direction:column;gap:10px;">
        @foreach([['verified_user','Pengajuan diverifikasi Admin'],['lock','Data aman & terenkripsi'],['apartment','Akses sesuai kantor yang dipilih']] as [$ic,$tx])
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:30px;height:30px;border-radius:8px;background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:15px;">{{ $ic }}</span>
          </div>
          <span style="font-size:13px;color:#94a3b8;font-weight:500;">{{ $tx }}</span>
        </div>
        @endforeach
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
        <span class="loc-pill">Pekanbaru</span><span class="loc-pill">Tebet Jakarta</span>
        <span class="loc-pill">Surabaya</span><span class="loc-pill">Bekasi</span>
      </div>
    </div>
  </section>

  <section class="panel-right">
    <div class="form-wrap fade-up">

      <div id="mobile-logo" style="display:none;margin-bottom:28px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:13px;color:#fff;">DPU</div>
          <div>
            <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;">Dian Bangun Sejahtera</div>
            <div style="font-size:10px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.08em;">Sistem Inventaris v1</div>
          </div>
        </div>
      </div>

      <div class="reg-card">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
          <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#f97316,#c2410c);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:18px;">how_to_reg</span>
          </div>
          <div>
            <h2 style="font-weight:800;font-size:18px;color:#0f172a;line-height:1.2;">Daftar Akun Baru</h2>
            <p style="font-size:12px;color:#94a3b8;margin-top:1px;">Pengajuan ditinjau oleh Admin.</p>
          </div>
        </div>

        <div class="step-indicator">
          <div class="step-item"><div class="step-circle active" id="sc-1">1</div><div class="step-label active" id="sl-1">Identitas</div></div>
          <div class="step-line idle" id="line-1" style="margin-bottom:18px;"></div>
          <div class="step-item"><div class="step-circle idle" id="sc-2">2</div><div class="step-label idle" id="sl-2">Peran</div></div>
          <div class="step-line idle" id="line-2" style="margin-bottom:18px;"></div>
          <div class="step-item"><div class="step-circle idle" id="sc-3">3</div><div class="step-label idle" id="sl-3">Detail</div></div>
        </div>

        @if($errors->any())
        <div class="alert-error">
          <span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#ef4444;flex-shrink:0;margin-top:1px;">error</span>
          <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
        </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" id="reg-form">
          @csrf
          <input type="hidden" name="peran" id="f-peran"/>
          <div id="kantor-hidden-wrap"></div>

          <!-- STEP 1 -->
          <div id="step1" class="panel-anim in" style="display:flex;flex-direction:column;gap:14px;">
            <div>
              <label class="field-label">Nama Lengkap</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">person</span>
                <input id="inp-nama" name="nama" type="text" placeholder="Nama lengkap Anda" class="field {{ $errors->has('nama') ? 'error' : '' }}" style="padding-left:40px;" value="{{ old('nama') }}"/>
              </div>
              @error('nama')<div class="field-error"><span class="material-symbols-outlined fill-icon" style="font-size:12px;">error</span>{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="field-label">Email</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">mail</span>
                <input id="inp-email" name="email" type="email" placeholder="email@dianbangun.co.id" class="field {{ $errors->has('email') ? 'error' : '' }}" style="padding-left:40px;" value="{{ old('email') }}"/>
              </div>
              @error('email')<div class="field-error"><span class="material-symbols-outlined fill-icon" style="font-size:12px;">error</span>{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="field-label">Kata Sandi</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">lock</span>
                <input id="inp-pass" type="password" name="password" placeholder="Min. 8 karakter" class="field {{ $errors->has('password') ? 'error' : '' }}" style="padding-left:40px;padding-right:42px;" oninput="checkStrength(this.value)"/>
                <button type="button" onclick="togglePass('inp-pass',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:none;cursor:pointer;color:#cbd5e1;padding:4px;display:flex;align-items:center;">
                  <span class="material-symbols-outlined" style="font-size:17px;">visibility</span>
                </button>
              </div>
              <div class="strength-bar">
                <div class="strength-seg" id="seg1"></div><div class="strength-seg" id="seg2"></div>
                <div class="strength-seg" id="seg3"></div><div class="strength-seg" id="seg4"></div>
              </div>
              <div class="strength-label" id="str-label" style="color:#cbd5e1;">Masukkan kata sandi</div>
              @error('password')<div class="field-error"><span class="material-symbols-outlined fill-icon" style="font-size:12px;">error</span>{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="field-label">Konfirmasi Kata Sandi</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#cbd5e1;font-size:17px;">lock_clock</span>
                <input id="inp-pass2" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" class="field" style="padding-left:40px;padding-right:42px;" oninput="checkMatch()"/>
                <button type="button" onclick="togglePass('inp-pass2',this)" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:none;cursor:pointer;color:#cbd5e1;padding:4px;display:flex;align-items:center;">
                  <span class="material-symbols-outlined" style="font-size:17px;">visibility</span>
                </button>
              </div>
              <div id="match-label" class="field-error" style="display:none;">
                <span class="material-symbols-outlined fill-icon" style="font-size:12px;">error</span>Kata sandi tidak cocok
              </div>
            </div>
            <button type="button" onclick="goStep2()" class="btn-or">
              <span class="material-symbols-outlined fill-icon" style="font-size:18px;">arrow_forward</span>Lanjutkan
            </button>
          </div>

          <!-- STEP 2 -->
          <div id="step2" class="panel-anim" style="display:none;flex-direction:column;gap:14px;">
            <div>
              <label class="field-label" style="margin-bottom:10px;">Pilih Peran Akun</label>
              <div class="peran-grid">
                <div class="peran-card" id="card-operator" onclick="selectPeran('operator')">
                  <div class="peran-icon"><span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#64748b;">badge</span></div>
                  <div class="peran-title">Operator</div>
                  <div class="peran-desc">Akses per kantor. Bisa pilih lebih dari 1.</div>
                </div>
                <div class="peran-card" id="card-admin" onclick="selectPeran('admin')">
                  <div class="peran-icon"><span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#64748b;">admin_panel_settings</span></div>
                  <div class="peran-title">Admin</div>
                  <div class="peran-desc">Akses penuh semua kantor & manajemen user.</div>
                </div>
              </div>
              <div id="peran-error" class="field-error" style="display:none;margin-top:6px;">
                <span class="material-symbols-outlined fill-icon" style="font-size:12px;">error</span>Pilih salah satu peran.
              </div>
            </div>
            <div class="info-badge">
              <span class="material-symbols-outlined fill-icon" style="font-size:15px;color:#2563eb;flex-shrink:0;">info</span>
              <span>Pengajuan Admin akan melalui verifikasi tambahan oleh super-admin.</span>
            </div>
            <div style="display:flex;gap:8px;">
              <button type="button" onclick="goStep1()" class="btn-ghost"><span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span></button>
              <button type="button" onclick="goStep3()" class="btn-or" style="flex:1;">
                <span class="material-symbols-outlined fill-icon" style="font-size:18px;">arrow_forward</span>Lanjutkan
              </button>
            </div>
          </div>

          <!-- STEP 3 -->
          <div id="step3" class="panel-anim" style="display:none;flex-direction:column;gap:14px;">
            <div class="summary-strip">
              <span class="material-symbols-outlined fill-icon" style="font-size:17px;color:#ea580c;flex-shrink:0;">person_check</span>
              <div>
                <div id="sum-nama" style="font-size:12.5px;font-weight:700;color:#9a3412;"></div>
                <div id="sum-peran" style="font-size:11px;color:#c2410c;margin-top:1px;"></div>
              </div>
            </div>

            {{-- ✅ KANTOR: multi-checkbox --}}
            <div id="kantor-section" style="display:none;">
              <label class="field-label" style="margin-bottom:2px;">Pilih Kantor</label>
              <p style="font-size:11px;color:#94a3b8;margin-bottom:8px;">Bisa pilih lebih dari 1 kantor.</p>
              <div class="kantor-list">
                @foreach($kantorList as $k)
                <div class="kantor-card" data-id="{{ $k->id }}" onclick="toggleKantor(this)">
                  <div>
                    <div class="kantor-card-name">{{ $k->nama }}</div>
                    <div class="kantor-card-loc">
                      <span class="material-symbols-outlined fill-icon" style="font-size:12px;color:#94a3b8;">location_on</span>
                      {{ $k->short_name }}
                    </div>
                  </div>
                  <div class="kantor-check">
                    <span class="material-symbols-outlined fill-icon kantor-check-icon">check</span>
                  </div>
                </div>
                @endforeach
              </div>
              <div id="kantor-count" class="kantor-count-badge"></div>
              <div id="kantor-error" class="field-error" style="display:none;margin-top:6px;">
                <span class="material-symbols-outlined fill-icon" style="font-size:12px;">error</span>Pilih minimal 1 kantor.
              </div>
            </div>

            <div>
              <label class="field-label">Alasan Pengajuan</label>
              <textarea id="inp-alasan" name="alasan" placeholder="Jelaskan alasan Anda membutuhkan akses sistem inventaris ini..."
                class="field {{ $errors->has('alasan') ? 'error' : '' }}">{{ old('alasan') }}</textarea>
              @error('alasan')<div class="field-error"><span class="material-symbols-outlined fill-icon" style="font-size:12px;">error</span>{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:8px;">
              <button type="button" onclick="goStep2b()" class="btn-ghost"><span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span></button>
              <button type="submit" class="btn-or" style="flex:1;" id="submit-btn">
                <span class="material-symbols-outlined fill-icon" style="font-size:18px;">how_to_reg</span>Kirim Pengajuan
              </button>
            </div>
          </div>

        </form>
      </div>

      <p style="text-align:center;font-size:11.5px;color:#94a3b8;margin-top:16px;">
        Sudah punya akun? <a href="{{ route('login') }}" style="color:#f97316;font-weight:700;text-decoration:none;">Masuk di sini</a>
      </p>
      <p style="text-align:center;font-size:11px;color:#cbd5e1;margin-top:8px;">
        PT. Dian Bangun Sejahtera &copy; {{ date('Y') }} · Sistem Inventaris v1.0
      </p>
    </div>
  </section>

</div>
<script>
var selectedPeran   = null;
var selectedKantors = new Set(); // ✅ multi-pilih

function setStep(s) {
  for (var i = 1; i <= 3; i++) {
    var sc = document.getElementById('sc-' + i);
    var sl = document.getElementById('sl-' + i);
    sc.className = 'step-circle ' + (i < s ? 'done' : i === s ? 'active' : 'idle');
    sl.className = 'step-label '  + (i < s ? 'done' : i === s ? 'active' : 'idle');
    sc.innerHTML = i < s ? '<span class="material-symbols-outlined fill-icon" style="font-size:14px;color:#fff;">check</span>' : i;
  }
  document.getElementById('line-1').className = 'step-line ' + (s > 1 ? 'done' : 'idle');
  document.getElementById('line-2').className = 'step-line ' + (s > 2 ? 'done' : 'idle');
}

function transition(fromId, toId, cb) {
  var from = document.getElementById(fromId);
  var to   = document.getElementById(toId);
  from.classList.add('out');
  setTimeout(function() {
    from.style.display = 'none'; from.classList.remove('out');
    to.style.display = 'flex'; to.style.flexDirection = 'column'; to.style.gap = '14px';
    to.classList.remove('out'); to.classList.add('in');
    if (cb) cb();
  }, 220);
}

function goStep2() {
  var nama  = document.getElementById('inp-nama').value.trim();
  var email = document.getElementById('inp-email').value.trim();
  var pass  = document.getElementById('inp-pass').value;
  var pass2 = document.getElementById('inp-pass2').value;
  if (!nama || !email || !pass || !pass2) { alert('Semua field wajib diisi.'); return; }
  if (pass !== pass2) {
    document.getElementById('match-label').style.display = 'flex';
    document.getElementById('inp-pass2').classList.add('error');
    return;
  }
  if (pass.length < 8) { alert('Kata sandi minimal 8 karakter.'); return; }
  setStep(2); transition('step1', 'step2');
}
function goStep1()  { setStep(1); transition('step2', 'step1'); }
function goStep2b() { setStep(2); transition('step3', 'step2'); }

function goStep3() {
  if (!selectedPeran) { document.getElementById('peran-error').style.display = 'flex'; return; }
  document.getElementById('peran-error').style.display = 'none';
  document.getElementById('f-peran').value = selectedPeran;
  document.getElementById('sum-nama').textContent  = document.getElementById('inp-nama').value.trim();
  document.getElementById('sum-peran').textContent =
    selectedPeran === 'admin' ? 'Peran: Admin · Akses semua kantor' : 'Peran: Operator · Pilih kantor di bawah';
  document.getElementById('kantor-section').style.display = selectedPeran === 'operator' ? 'block' : 'none';
  setStep(3); transition('step2', 'step3');
}

function selectPeran(p) {
  selectedPeran = p;
  document.getElementById('card-admin').classList.toggle('selected', p === 'admin');
  document.getElementById('card-operator').classList.toggle('selected', p === 'operator');
  document.getElementById('peran-error').style.display = 'none';
}

// ✅ Toggle kantor multi-pilih
function toggleKantor(el) {
  var id = el.dataset.id;
  if (selectedKantors.has(id)) {
    selectedKantors.delete(id);
    el.classList.remove('selected');
  } else {
    selectedKantors.add(id);
    el.classList.add('selected');
  }
  // Rebuild hidden inputs
  var wrap = document.getElementById('kantor-hidden-wrap');
  wrap.innerHTML = '';
  selectedKantors.forEach(function(kid) {
    var inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'kantor_ids[]'; inp.value = kid;
    wrap.appendChild(inp);
  });
  // Update badge count
  var badge = document.getElementById('kantor-count');
  if (selectedKantors.size > 0) {
    badge.style.display = 'block';
    badge.textContent = selectedKantors.size + ' kantor dipilih';
  } else {
    badge.style.display = 'none';
  }
  document.getElementById('kantor-error').style.display = 'none';
}

// Submit
document.getElementById('reg-form').addEventListener('submit', function(e) {
  if (selectedPeran === 'operator' && selectedKantors.size === 0) {
    e.preventDefault();
    document.getElementById('kantor-error').style.display = 'flex';
    return;
  }
  var alasan = document.getElementById('inp-alasan').value.trim();
  if (!alasan || alasan.length < 10) {
    e.preventDefault();
    alert('Alasan pengajuan minimal 10 karakter.');
    return;
  }
  setTimeout(function() {
    var btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:18px;animation:spin .8s linear infinite;">progress_activity</span>&nbsp;Mengirim...';
  }, 0);
});

function checkStrength(val) {
  var segs  = ['seg1','seg2','seg3','seg4'].map(function(id){ return document.getElementById(id); });
  var label = document.getElementById('str-label');
  segs.forEach(function(s){ s.className = 'strength-seg'; });
  if (!val) { label.textContent = 'Masukkan kata sandi'; label.style.color = '#cbd5e1'; return; }
  var score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  var cls = score <= 1 ? 'weak' : score <= 2 ? 'medium' : 'strong';
  var txt = score <= 1 ? 'Lemah' : score <= 2 ? 'Sedang' : 'Kuat';
  var clr = score <= 1 ? '#ef4444' : score <= 2 ? '#f59e0b' : '#22c55e';
  for (var i = 0; i < score; i++) segs[i].classList.add(cls);
  label.textContent = txt; label.style.color = clr;
}
function checkMatch() {
  var p1 = document.getElementById('inp-pass').value;
  var p2 = document.getElementById('inp-pass2').value;
  var ml = document.getElementById('match-label');
  var f  = document.getElementById('inp-pass2');
  if (p2 && p1 !== p2) { ml.style.display = 'flex'; f.classList.add('error'); }
  else { ml.style.display = 'none'; f.classList.remove('error'); }
}
function togglePass(id, btn) {
  var inp  = document.getElementById(id);
  var icon = btn.querySelector('.material-symbols-outlined');
  inp.type = inp.type === 'password' ? 'text' : 'password';
  icon.textContent = inp.type === 'password' ? 'visibility' : 'visibility_off';
}
(function() {
  var ml = document.getElementById('mobile-logo');
  function check() { if (ml) ml.style.display = window.innerWidth <= 900 ? 'block' : 'none'; }
  check(); window.addEventListener('resize', check);
})();

// Restore dari old() kalau server error
@if(old('peran'))
  selectPeran('{{ old('peran') }}');
  setStep(3);
  document.getElementById('step1').style.display = 'none';
  document.getElementById('step2').style.display = 'none';
  document.getElementById('step3').style.display = 'flex';
  document.getElementById('step3').style.flexDirection = 'column';
  document.getElementById('step3').style.gap = '14px';
  document.getElementById('step3').classList.add('in');
  document.getElementById('sum-nama').textContent  = '{{ old('nama') }}';
  document.getElementById('sum-peran').textContent = '{{ old('peran') === 'admin' ? 'Peran: Admin · Akses semua kantor' : 'Peran: Operator · Pilih kantor di bawah' }}';
  document.getElementById('kantor-section').style.display = '{{ old('peran') === 'operator' ? 'block' : 'none' }}';
  @if(old('kantor_ids'))
    var oldIds = @json(old('kantor_ids', []));
    oldIds.forEach(function(id) {
      var el = document.querySelector('.kantor-card[data-id="' + id + '"]');
      if (el) toggleKantor(el);
    });
  @endif
@endif
</script>
</body>
</html>
