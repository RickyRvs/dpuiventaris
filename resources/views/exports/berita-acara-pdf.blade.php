<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }

  body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 12pt;
    color: #000;
    background: #fff;
  }

  .page {
    padding: 2cm 2.5cm 2cm 3cm;
    min-height: 29.7cm;
  }

  /* ── HEADER ── */
  .kop {
    display: table;
    width: 100%;
    border-bottom: 3px double #000;
    padding-bottom: 10px;
    margin-bottom: 16px;
  }
  .kop-logo { display: table-cell; width: 80px; vertical-align: middle; }
  .kop-logo img { width: 70px; height: auto; }
  .kop-logo-placeholder {
    width: 70px; height: 70px;
    border: 2px solid #000;
    display: flex; align-items: center; justify-content: center;
    font-weight: bold; font-size: 14pt; color: #000;
    text-align: center;
    line-height: 1.2;
  }
  .kop-text { display: table-cell; vertical-align: middle; padding-left: 12px; }
  .kop-text .company { font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
  .kop-text .address  { font-size: 9pt; color: #333; margin-top: 2px; line-height: 1.4; }

  /* ── JUDUL DOKUMEN ── */
  .doc-title {
    text-align: center;
    margin: 18px 0 6px;
  }
  .doc-title h1 {
    font-size: 14pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-decoration: underline;
  }
  .doc-nomor {
    text-align: center;
    font-size: 11pt;
    margin-bottom: 16px;
    color: #333;
  }

  /* ── SECTION ── */
  .section-title {
    font-size: 11pt;
    font-weight: bold;
    background: #e8e8e8;
    padding: 4px 8px;
    margin: 14px 0 6px;
    border-left: 4px solid #000;
  }

  /* ── TABLE DATA ── */
  .data-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
  }
  .data-table td {
    padding: 4px 6px;
    vertical-align: top;
    font-size: 11pt;
  }
  .data-table .label {
    width: 200px;
    font-weight: normal;
    color: #333;
  }
  .data-table .sep    { width: 12px; text-align: center; }
  .data-table .value  { font-weight: bold; }

  /* ── ASET TABLE ── */
  .aset-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
    font-size: 10.5pt;
  }
  .aset-table th {
    background: #e8e8e8;
    border: 1px solid #555;
    padding: 5px 8px;
    text-align: center;
    font-weight: bold;
  }
  .aset-table td {
    border: 1px solid #555;
    padding: 5px 8px;
    vertical-align: top;
  }
  .aset-table td.center { text-align: center; }
  .aset-table td.right  { text-align: right; }

  /* ── NARASI ── */
  .narasi {
    font-size: 11pt;
    line-height: 1.7;
    text-align: justify;
    margin: 10px 0;
  }

  /* ── TTD ── */
  .ttd-row {
    display: table;
    width: 100%;
    margin-top: 32px;
  }
  .ttd-col {
    display: table-cell;
    width: 50%;
    text-align: center;
    vertical-align: top;
    padding: 0 20px;
  }
  .ttd-col .title    { font-weight: bold; font-size: 11pt; margin-bottom: 4px; }
  .ttd-col .subtitle { font-size: 10pt; color: #555; margin-bottom: 80px; }
  .ttd-col .sign-line {
    border-bottom: 1px solid #000;
    margin: 0 auto;
    width: 160px;
    margin-bottom: 4px;
  }
  .ttd-col .nama    { font-size: 11pt; font-weight: bold; }
  .ttd-col .jabatan { font-size: 10pt; color: #444; }

  /* ── MATERAI ── */
  .materai-box {
    border: 2px dashed #999;
    width: 120px; height: 80px;
    margin: 0 auto 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 9pt; color: #999; text-align: center;
    line-height: 1.3;
  }

  /* ── FOOTER ── */
  .footer {
    margin-top: 30px;
    border-top: 1px solid #bbb;
    padding-top: 8px;
    font-size: 9pt;
    color: #666;
    text-align: center;
  }
  .footer .highlight { color: #000; font-weight: bold; }

  /* ── STATUS WATERMARK ── */
  .watermark-draft {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 72pt;
    font-weight: bold;
    color: rgba(200,200,200,0.3);
    z-index: -1;
    white-space: nowrap;
    letter-spacing: 8px;
    text-transform: uppercase;
  }
</style>
</head>
<body>
<div class="page">

  {{-- WATERMARK DRAFT --}}
  <div class="watermark-draft">TEMPLATE</div>

  {{-- ── KOP SURAT ── --}}
  <div class="kop">
    <div class="kop-logo">
      <div class="kop-logo-placeholder">DPU</div>
    </div>
    <div class="kop-text">
      <div class="company">PT. Dian Pilar Utama</div>
      <div class="address">
        Jl. Contoh No. 123, Jakarta Selatan 12345<br/>
        Telp: (021) 000-0000 &nbsp;|&nbsp; Email: info@dianpilarutama.co.id<br/>
        Website: www.dianpilarutama.co.id
      </div>
    </div>
  </div>

  {{-- ── JUDUL ── --}}
  <div class="doc-title">
    <h1>Berita Acara Serah Terima Aset</h1>
  </div>
  <div class="doc-nomor">
    Nomor: <strong>{{ $ba->nomor }}</strong>
  </div>

  {{-- ── NARASI PEMBUKA ── --}}
  <p class="narasi">
    Pada hari ini,
    <strong>{{ \Carbon\Carbon::parse($ba->tanggal_serah_terima)->translatedFormat('l') }}</strong>,
    tanggal
    <strong>{{ \Carbon\Carbon::parse($ba->tanggal_serah_terima)->translatedFormat('d F Y') }}</strong>,
    kami yang bertanda tangan di bawah ini telah melaksanakan serah terima aset/barang inventaris
    milik <strong>PT. Dian Pilar Utama</strong> dengan ketentuan sebagaimana tercantum dalam
    berita acara ini.
  </p>

  {{-- ── PIHAK-PIHAK ── --}}
  <div class="section-title">I. Para Pihak</div>

  <table class="data-table">
    <tr>
      <td class="label" colspan="3" style="font-weight:bold;padding-bottom:2px;">Pihak Pertama (Pemberi)</td>
    </tr>
    <tr>
      <td class="label">Nama</td>
      <td class="sep">:</td>
      <td class="value">{{ $ba->pihak_pertama_nama }}</td>
    </tr>
    <tr>
      <td class="label">Jabatan</td>
      <td class="sep">:</td>
      <td class="value">{{ $ba->pihak_pertama_jabatan }}</td>
    </tr>
    <tr>
      <td class="label">Instansi</td>
      <td class="sep">:</td>
      <td class="value">PT. Dian Pilar Utama — {{ $ba->kantor?->nama ?? 'Kantor Pusat' }}</td>
    </tr>
  </table>

  <table class="data-table" style="margin-top:8px;">
    <tr>
      <td class="label" colspan="3" style="font-weight:bold;padding-bottom:2px;">Pihak Kedua (Penerima)</td>
    </tr>
    <tr>
      <td class="label">Nama</td>
      <td class="sep">:</td>
      <td class="value">{{ $ba->pihak_kedua_nama }}</td>
    </tr>
    <tr>
      <td class="label">Jabatan/Fungsi</td>
      <td class="sep">:</td>
      <td class="value">{{ $ba->pihak_kedua_jabatan }}</td>
    </tr>
    <tr>
      <td class="label">Kantor</td>
      <td class="sep">:</td>
      <td class="value">{{ $ba->kantor?->nama ?? '-' }}</td>
    </tr>
  </table>

  {{-- ── DETAIL ASET ── --}}
  <div class="section-title">II. Rincian Aset yang Diserahterimakan</div>

  <table class="aset-table">
    <thead>
      <tr>
        <th style="width:40px;">No.</th>
        <th style="width:100px;">Kode Aset</th>
        <th>Nama / Deskripsi Aset</th>
        <th style="width:120px;">Kategori</th>
        <th style="width:90px;">Kondisi</th>
        <th style="width:130px;">Nilai Perolehan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="center">1</td>
        <td class="center">{{ $ba->aset_kode ?? '-' }}</td>
        <td>{{ $ba->aset_nama ?? '-' }}</td>
        <td class="center">{{ $ba->aset_kategori ?? '-' }}</td>
        <td class="center">{{ $ba->aset_kondisi ?? '-' }}</td>
        <td class="right">{{ $ba->nilai_format }}</td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5" style="text-align:right;font-weight:bold;background:#f0f0f0;">Total Nilai</td>
        <td class="right" style="font-weight:bold;background:#f0f0f0;">{{ $ba->nilai_format }}</td>
      </tr>
    </tfoot>
  </table>

  {{-- ── KETERANGAN TAMBAHAN ── --}}
  @if($ba->keterangan)
  <div class="section-title">III. Keterangan Tambahan</div>
  <p class="narasi">{{ $ba->keterangan }}</p>
  @endif

  {{-- ── PERNYATAAN ── --}}
  <div class="section-title">{{ $ba->keterangan ? 'IV' : 'III' }}. Pernyataan Para Pihak</div>

  <p class="narasi">
    Dengan ditandatanganinya berita acara ini, Pihak Pertama menyatakan telah menyerahkan
    aset/barang inventaris tersebut di atas kepada Pihak Kedua dalam kondisi
    <strong>{{ $ba->aset_kondisi ?? 'sebagaimana adanya' }}</strong>, dan Pihak Kedua
    menyatakan telah menerima aset/barang tersebut dengan baik dan akan bertanggung jawab
    atas pemeliharaan dan penggunaannya sesuai ketentuan yang berlaku di
    <strong>PT. Dian Pilar Utama</strong>.
  </p>

  <p class="narasi" style="margin-top:8px;">
    Berita acara ini dibuat dan ditandatangani di atas materai yang cukup oleh kedua belah pihak
    sebagai bukti sah serah terima, dan masing-masing pihak memegang satu eksemplar.
  </p>

  {{-- ── TANDA TANGAN ── --}}
  <div class="ttd-row">
    {{-- Pihak Pertama --}}
    <div class="ttd-col">
      <div class="title">Pihak Pertama</div>
      <div class="subtitle">Pemberi Aset</div>

      {{-- Kotak materai --}}
      <div class="materai-box">
        Tempel<br/>Materai<br/>Rp 10.000
      </div>

      <div class="sign-line"></div>
      <div class="nama">{{ $ba->pihak_pertama_nama }}</div>
      <div class="jabatan">{{ $ba->pihak_pertama_jabatan }}</div>
      <div class="jabatan">PT. Dian Pilar Utama</div>
    </div>

    {{-- Pihak Kedua --}}
    <div class="ttd-col">
      <div class="title">Pihak Kedua</div>
      <div class="subtitle">Penerima Aset</div>

      {{-- Kotak materai --}}
      <div class="materai-box">
        Tempel<br/>Materai<br/>Rp 10.000
      </div>

      <div class="sign-line"></div>
      <div class="nama">{{ $ba->pihak_kedua_nama }}</div>
      <div class="jabatan">{{ $ba->pihak_kedua_jabatan }}</div>
      <div class="jabatan">{{ $ba->kantor?->short_name ?? 'PT. Dian Pilar Utama' }}</div>
    </div>
  </div>

  {{-- ── FOOTER ── --}}
  <div class="footer">
    Dokumen ini dicetak dari Sistem Inventaris PT. Dian Pilar Utama &nbsp;|&nbsp;
    Dibuat oleh: <span class="highlight">{{ $ba->dibuat_oleh ?? 'System' }}</span> &nbsp;|&nbsp;
    Dicetak pada: <span class="highlight">{{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
    <br/>
    <em style="font-size:8pt;">
      ⚠️ Dokumen ini harus ditandatangani di atas materai Rp 10.000 oleh kedua belah pihak sebelum dianggap sah.
    </em>
  </div>

</div>
</body>
</html>