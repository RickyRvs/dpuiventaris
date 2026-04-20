<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Pengajuan Terkirim | Sistem Inventaris DPU</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
*, *::before, *::after {
  font-family: 'DM Sans', sans-serif;
  box-sizing: border-box;
  margin: 0; padding: 0;
}
h1, h2, h3 { font-family: 'Sora', sans-serif; }
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24;
  font-size: 20px; vertical-align: middle; line-height: 1;
}
.fill-icon { font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24; }

html, body {
  min-height: 100vh;
  background: linear-gradient(160deg, #0f172a 0%, #1a2744 50%, #0f172a 100%);
  display: flex; align-items: center; justify-content: center;
  padding: 32px 20px;
}

.card {
  background: #fff;
  border-radius: 24px;
  padding: 48px 40px;
  max-width: 460px;
  width: 100%;
  text-align: center;
  box-shadow: 0 24px 64px rgba(0,0,0,.3);
  position: relative;
  overflow: hidden;
}
.card::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 4px;
  background: linear-gradient(90deg, #f97316, #ea580c);
}

.icon-wrap {
  width: 80px; height: 80px; border-radius: 50%;
  background: linear-gradient(135deg, #f0fdf4, #dcfce7);
  border: 3px solid #86efac;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 24px;
  animation: popIn .4s cubic-bezier(.34,1.4,.64,1) forwards;
}

@keyframes popIn {
  from { transform: scale(0); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

.content { animation: fadeUp .4s ease .15s both; }

.badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: #fff7ed; border: 1px solid #fed7aa;
  border-radius: 999px; padding: 5px 14px;
  font-size: 12px; font-weight: 700; color: #c2410c;
  margin-bottom: 20px;
}

.info-box {
  background: #f8fafc; border: 1.5px solid #e2e8f0;
  border-radius: 14px; padding: 16px 20px;
  margin: 20px 0; text-align: left;
}
.info-row {
  display: flex; align-items: center; gap: 10px;
  font-size: 13px; color: #64748b; padding: 6px 0;
}
.info-row:not(:last-child) { border-bottom: 1px solid #f1f5f9; }
.info-row strong { color: #0f172a; }

.btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 13px 22px; border-radius: 12px;
  font-size: 14px; font-weight: 700; border: none; cursor: pointer;
  text-decoration: none; transition: all .18s;
}
.btn-primary {
  background: linear-gradient(135deg, #f97316, #ea580c);
  color: #fff;
  box-shadow: 0 4px 16px rgba(249,115,22,.3);
}
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(249,115,22,.4); }
</style>
</head>
<body>

<div class="card">
  <div class="icon-wrap">
    <span class="material-symbols-outlined fill-icon" style="font-size:40px;color:#16a34a;">check_circle</span>
  </div>

  <div class="content">
    <div class="badge">
      <span class="material-symbols-outlined fill-icon" style="font-size:13px;">schedule</span>
      Menunggu Verifikasi Admin
    </div>

    <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin-bottom:10px;">
      Pengajuan Berhasil Dikirim!
    </h2>
    <p style="font-size:13.5px;color:#64748b;line-height:1.7;">
      Pengajuan akun kamu sudah kami terima. Admin akan meninjau dan mengaktifkan akun setelah diverifikasi.
    </p>

    <div class="info-box">
      <div class="info-row">
        <span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#f97316;">person</span>
        <span>Nama: <strong>{{ $reg_nama }}</strong></span>
      </div>
      <div class="info-row">
        <span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#f97316;">mail</span>
        <span>Email: <strong>{{ $reg_email }}</strong></span>
      </div>
      <div class="info-row">
        <span class="material-symbols-outlined fill-icon" style="font-size:16px;color:#f97316;">info</span>
        <span>Status: <strong style="color:#f97316;">Menunggu Persetujuan</strong></span>
      </div>
    </div>

    <p style="font-size:12px;color:#94a3b8;margin-bottom:20px;">
      Jika sudah disetujui, kamu bisa login menggunakan email dan password yang sudah didaftarkan.
    </p>

    <a href="{{ route('login') }}" class="btn btn-primary">
      <span class="material-symbols-outlined fill-icon" style="font-size:18px;">login</span>
      Kembali ke Halaman Login
    </a>
  </div>
</div>

</body>
</html>