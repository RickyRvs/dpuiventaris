<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<style>
* { font-family: 'DejaVu Sans', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
body { font-size: 10px; color: #1e293b; background: #fff; }

/* ── Header ── */
.header-wrap {
  background: #0f172a;
  margin-bottom: 0;
}
.header-accent {
  height: 4px;
  background: #f97316;
}
.header-body {
  padding: 20px 28px 18px;
}
.header-table { width: 100%; border-collapse: collapse; }
.header-table td { vertical-align: middle; padding: 0; }
.logo-box {
  width: 48px; height: 48px;
  background: #f97316;
  border-radius: 10px;
  text-align: center;
  line-height: 48px;
  font-size: 13px;
  font-weight: bold;
  color: #fff;
  display: inline-block;
}
.company-name  { font-size: 17px; font-weight: bold; color: #fff; }
.company-sub   { font-size: 10px; color: #f97316; margin-top: 2px; letter-spacing: 1px; text-transform: uppercase; }
.report-title  { font-size: 12px; color: #94a3b8; text-align: right; }
.report-date   { font-size: 10px; color: #64748b; text-align: right; margin-top: 4px; }

/* ── Sub-header strip ── */
.subheader {
  background: #1e293b;
  padding: 8px 28px;
  margin-bottom: 20px;
}
.subheader-table { width: 100%; border-collapse: collapse; }
.subheader-table td { color: #94a3b8; font-size: 9px; padding: 0; }
.subheader-table .val { color: #e2e8f0; font-weight: bold; font-size: 9px; }

/* ── Section heading ── */
.section-head {
  background: #f97316;
  padding: 6px 28px;
  margin-bottom: 14px;
}
.section-head span {
  color: #fff;
  font-size: 10px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* ── KPI row ── */
.kpi-wrap { padding: 0 28px; margin-bottom: 20px; }
.kpi-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
.kpi-cell {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 10px 12px;
  text-align: center;
}
.kpi-label { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
.kpi-val   { font-size: 20px; font-weight: bold; color: #0f172a; margin: 4px 0 2px; line-height: 1; }
.kpi-sub   { font-size: 8px; font-weight: bold; }
.kpi-orange { border-top: 3px solid #f97316; }
.kpi-blue   { border-top: 3px solid #2563eb; }
.kpi-green  { border-top: 3px solid #16a34a; }
.kpi-yellow { border-top: 3px solid #ca8a04; }
.kpi-red    { border-top: 3px solid #dc2626; }

/* ── Kantor cards ── */
.kantor-wrap { padding: 0 28px; margin-bottom: 20px; }
.kantor-table { width: 100%; border-collapse: separate; border-spacing: 8px; }
.kantor-cell {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 12px;
  vertical-align: top;
  width: 50%;
  border-left: 3px solid #f97316;
}
.kantor-name   { font-size: 11px; font-weight: bold; color: #0f172a; margin-bottom: 8px; }
.kantor-stat   { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
.kantor-stat td { font-size: 9px; text-align: center; padding: 0 4px; }
.ks-num        { font-size: 15px; font-weight: bold; display: block; }
.ks-lbl        { color: #94a3b8; display: block; margin-top: 1px; }
.bar-bg { background: #f1f5f9; border-radius: 4px; height: 5px; margin-top: 6px; }
.bar-fill { background: #f97316; border-radius: 4px; height: 5px; }
.bar-label { font-size: 8px; color: #94a3b8; margin-top: 2px; }

/* ── Tabel ringkasan ── */
.summary-wrap { padding: 0 28px; margin-bottom: 24px; }
.summary-table { width: 100%; border-collapse: collapse; }
.summary-table thead tr { background: #0f172a; }
.summary-table thead th {
  padding: 9px 10px;
  text-align: left;
  font-size: 8px;
  font-weight: bold;
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.summary-table tbody tr:nth-child(even) { background: #f8fafc; }
.summary-table tbody tr:nth-child(odd)  { background: #fff; }
.summary-table tfoot tr { background: #1e293b; }
.summary-table tfoot td {
  padding: 9px 10px;
  font-size: 9px;
  font-weight: bold;
  color: #fff;
}
.summary-table tbody td {
  padding: 9px 10px;
  font-size: 10px;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
}
.pct-bar-bg   { background: #e2e8f0; border-radius: 3px; height: 4px; width: 50px; display: inline-block; }
.pct-bar-fill { border-radius: 3px; height: 4px; display: block; }

/* ── Page 2: detail aset ── */
.detail-table { width: 100%; border-collapse: collapse; }
.detail-wrap  { padding: 0 28px; margin-bottom: 20px; }
.detail-table thead tr { background: #f97316; }
.detail-table thead th {
  padding: 8px 8px;
  text-align: left;
  font-size: 8px;
  font-weight: bold;
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}
.detail-table tbody tr:nth-child(even) { background: #fff7ed; }
.detail-table tbody tr:nth-child(odd)  { background: #fff; }
.detail-table tbody td {
  padding: 7px 8px;
  font-size: 9px;
  color: #334155;
  border-bottom: 1px solid #fef3e2;
}
.badge {
  padding: 2px 7px;
  border-radius: 999px;
  font-size: 8px;
  font-weight: bold;
  display: inline-block;
}
.badge-baik      { background: #dcfce7; color: #15803d; }
.badge-perbaikan { background: #fef9c3; color: #92400e; }
.badge-rusak     { background: #fee2e2; color: #991b1b; }
.kode-pill {
  background: #fff7ed;
  color: #c2410c;
  border: 1px solid #fed7aa;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 8px;
  font-weight: bold;
}

/* ── Footer ── */
.footer-wrap {
  background: #f8fafc;
  border-top: 2px solid #f97316;
  padding: 10px 28px;
  margin-top: 8px;
}
.footer-table { width: 100%; border-collapse: collapse; }
.footer-table td { font-size: 8px; color: #94a3b8; padding: 0; }

/* ── Page break ── */
.page-break { page-break-after: always; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════════ --}}
{{-- HALAMAN 1 --}}
{{-- ══════════════════════════════════════════ --}}

<!-- HEADER -->
<div class="header-wrap">
  <div class="header-accent"></div>
  <div class="header-body">
    <table class="header-table">
      <tr>
        <td style="width:56px;">
          <div class="logo-box">DPU</div>
        </td>
        <td style="padding-left:12px;">
          <div class="company-name">PT. Dian Bangun Sejahtera</div>
          <div class="company-sub">Sistem Inventaris v1.0</div>
        </td>
        <td style="text-align:right;">
          <div class="report-title">Laporan Inventaris Aset</div>
          <div class="report-date">{{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </td>
      </tr>
    </table>
  </div>
</div>

<!-- SUB-HEADER -->
<div class="subheader">
  <table class="subheader-table">
    <tr>
      <td>Dicetak oleh: <span class="val">{{ $generatedBy }}</span></td>
      <td style="text-align:center;">Periode: <span class="val">{{ $periode }}</span></td>
      <td style="text-align:right;">Dokumen: <span class="val">LAP-{{ now()->format('Ymd-His') }}</span></td>
    </tr>
  </table>
</div>

<!-- SECTION: KPI -->
<div class="section-head"><span>Ringkasan Eksekutif</span></div>
<div class="kpi-wrap">
  <table class="kpi-table">
    <tr>
      <td class="kpi-cell kpi-orange">
        <div class="kpi-label">Total Aset</div>
        <div class="kpi-val">{{ number_format($totalAset) }}</div>
        <div class="kpi-sub" style="color:#f97316;">Seluruh kantor</div>
      </td>
      <td class="kpi-cell kpi-green">
        <div class="kpi-label">Kondisi Baik</div>
        <div class="kpi-val" style="color:#16a34a;">{{ number_format($totalBaik) }}</div>
        <div class="kpi-sub" style="color:#16a34a;">
          {{ $totalAset > 0 ? round($totalBaik/$totalAset*100) : 0 }}% dari total
        </div>
      </td>
      <td class="kpi-cell kpi-yellow">
        <div class="kpi-label">Dalam Perbaikan</div>
        <div class="kpi-val" style="color:#ca8a04;">{{ number_format($totalPerbaikan) }}</div>
        <div class="kpi-sub" style="color:#ca8a04;">Perlu perhatian</div>
      </td>
      <td class="kpi-cell kpi-red">
        <div class="kpi-label">Rusak</div>
        <div class="kpi-val" style="color:#dc2626;">{{ number_format($totalRusak) }}</div>
        <div class="kpi-sub" style="color:#dc2626;">Perlu tindak lanjut</div>
      </td>
      <td class="kpi-cell kpi-blue">
        <div class="kpi-label">Total Nilai Aset</div>
        <div class="kpi-val" style="font-size:14px;color:#2563eb;">{{ $totalNilai }}</div>
        <div class="kpi-sub" style="color:#64748b;">Nilai perolehan</div>
      </td>
    </tr>
  </table>
</div>

<!-- SECTION: RINGKASAN PER KANTOR (cards) -->
<div class="section-head"><span>Kondisi per Kantor</span></div>
<div class="kantor-wrap">
  @php $chunks = array_chunk($kantorList, 2); @endphp
  @foreach($chunks as $row)
  <table class="kantor-table" style="margin-bottom:0;">
    <tr>
      @foreach($row as $k)
      @php
        $tot  = $k['stat']['total'];
        $bk   = $k['stat']['baik'];
        $pb   = $k['stat']['perbaikan'];
        $rk   = $k['stat']['rusak'];
        $pct  = $tot > 0 ? round($bk/$tot*100) : 0;
      @endphp
      <td class="kantor-cell">
        <div class="kantor-name">{{ $k['nama'] }}</div>
        <table class="kantor-stat">
          <tr>
            <td><span class="ks-num" style="color:#0f172a;">{{ $tot }}</span><span class="ks-lbl">Total</span></td>
            <td><span class="ks-num" style="color:#16a34a;">{{ $bk }}</span><span class="ks-lbl">Baik</span></td>
            <td><span class="ks-num" style="color:#ca8a04;">{{ $pb }}</span><span class="ks-lbl">Perbaikan</span></td>
            <td><span class="ks-num" style="color:#dc2626;">{{ $rk }}</span><span class="ks-lbl">Rusak</span></td>
            <td><span class="ks-num" style="color:#2563eb;font-size:11px;">{{ $k['stat']['nilai'] }}</span><span class="ks-lbl">Nilai</span></td>
          </tr>
        </table>
        <div class="bar-bg">
          <div class="bar-fill" style="width:{{ $pct }}%;"></div>
        </div>
        <div class="bar-label">{{ $pct }}% kondisi baik</div>
      </td>
      @endforeach
      @if(count($row) === 1)
      <td style="width:50%;"></td>
      @endif
    </tr>
  </table>
  @endforeach
</div>

<!-- SECTION: TABEL RINGKASAN -->
<div class="section-head"><span>Tabel Ringkasan per Kantor</span></div>
<div class="summary-wrap">
  <table class="summary-table">
    <thead>
      <tr>
        <th>Kantor</th>
        <th style="text-align:center;">Total</th>
        <th style="text-align:center;">Baik</th>
        <th style="text-align:center;">Perbaikan</th>
        <th style="text-align:center;">Rusak</th>
        <th>Nilai Aset</th>
        <th style="text-align:center;">% Baik</th>
      </tr>
    </thead>
    <tbody>
      @foreach($kantorList as $k)
      @php
        $tot = $k['stat']['total'];
        $bk  = $k['stat']['baik'];
        $pb  = $k['stat']['perbaikan'];
        $rk  = $k['stat']['rusak'];
        $pct = $tot > 0 ? round($bk/$tot*100) : 0;
        $barColor = $pct >= 80 ? '#16a34a' : ($pct >= 50 ? '#ca8a04' : '#dc2626');
      @endphp
      <tr>
        <td style="font-weight:bold;color:#0f172a;">{{ $k['nama'] }}</td>
        <td style="text-align:center;font-weight:bold;color:#0f172a;">{{ $tot }}</td>
        <td style="text-align:center;font-weight:bold;color:#16a34a;">{{ $bk }}</td>
        <td style="text-align:center;font-weight:bold;color:#ca8a04;">{{ $pb }}</td>
        <td style="text-align:center;font-weight:bold;color:#dc2626;">{{ $rk }}</td>
        <td style="font-weight:bold;color:#0f172a;">{{ $k['stat']['nilai'] }}</td>
        <td style="text-align:center;">
          <div class="pct-bar-bg">
            <div class="pct-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
          </div>
          <span style="font-size:9px;font-weight:bold;color:{{ $barColor }};">{{ $pct }}%</span>
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      @php
        $gtot  = collect($kantorList)->sum(fn($k) => $k['stat']['total']);
        $gbk   = collect($kantorList)->sum(fn($k) => $k['stat']['baik']);
        $gpb   = collect($kantorList)->sum(fn($k) => $k['stat']['perbaikan']);
        $grk   = collect($kantorList)->sum(fn($k) => $k['stat']['rusak']);
        $gpct  = $gtot > 0 ? round($gbk/$gtot*100) : 0;
      @endphp
      <tr>
        <td>TOTAL KESELURUHAN</td>
        <td style="text-align:center;">{{ $gtot }}</td>
        <td style="text-align:center;">{{ $gbk }}</td>
        <td style="text-align:center;">{{ $gpb }}</td>
        <td style="text-align:center;">{{ $grk }}</td>
        <td>{{ $totalNilai }}</td>
        <td style="text-align:center;color:#f97316;">{{ $gpct }}%</td>
      </tr>
    </tfoot>
  </table>
</div>

<!-- FOOTER HALAMAN 1 -->
<div class="footer-wrap">
  <table class="footer-table">
    <tr>
      <td>PT. Dian Bangun Sejahtera &copy; {{ date('Y') }} — Sistem Inventaris v1.0</td>
      <td style="text-align:right;">Halaman 1 dari 2 — Dokumen ini digenerate otomatis oleh sistem</td>
    </tr>
  </table>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- PAGE BREAK → HALAMAN 2 --}}
{{-- ══════════════════════════════════════════ --}}
<div class="page-break"></div>

<!-- HEADER HALAMAN 2 -->
<div class="header-wrap">
  <div class="header-accent"></div>
  <div class="header-body">
    <table class="header-table">
      <tr>
        <td style="width:56px;"><div class="logo-box">DPU</div></td>
        <td style="padding-left:12px;">
          <div class="company-name">PT. Dian Bangun Sejahtera</div>
          <div class="company-sub">Detail Data Aset</div>
        </td>
        <td style="text-align:right;">
          <div class="report-title">Laporan Inventaris — Halaman 2</div>
          <div class="report-date">{{ now()->translatedFormat('d F Y') }}</div>
        </td>
      </tr>
    </table>
  </div>
</div>
<div class="subheader" style="margin-bottom:16px;">
  <table class="subheader-table">
    <tr>
      <td>Total data: <span class="val">{{ $asetList->count() }} aset</span></td>
      <td style="text-align:right;">Dicetak: <span class="val">{{ now()->translatedFormat('d F Y, H:i') }} WIB</span></td>
    </tr>
  </table>
</div>

<!-- SECTION: DETAIL ASET -->
<div class="section-head"><span>Daftar Seluruh Aset</span></div>
<div class="detail-wrap">
  <table class="detail-table">
    <thead>
      <tr>
        <th style="width:20px;text-align:center;">No</th>
        <th style="width:65px;">Kode</th>
        <th>Nama Aset</th>
        <th style="width:85px;">Kategori</th>
        <th style="width:62px;">Kantor</th>
        <th style="width:65px;">Ruangan</th>
        <th style="width:58px;text-align:center;">Kondisi</th>
        <th style="width:75px;">Penanggung Jawab</th>
        <th style="width:65px;text-align:right;">Nilai</th>
      </tr>
    </thead>
    <tbody>
      @foreach($asetList as $i => $a)
      <tr>
        <td style="text-align:center;color:#94a3b8;font-size:8px;">{{ $i + 1 }}</td>
        <td><span class="kode-pill">{{ $a->kode }}</span></td>
        <td style="font-weight:bold;color:#0f172a;font-size:9px;">{{ $a->nama }}</td>
        <td style="color:#64748b;font-size:8px;">{{ $a->kategori }}</td>
        <td style="font-size:8px;">{{ $a->kantor?->short_name ?? '-' }}</td>
        <td style="color:#64748b;font-size:8px;">{{ $a->ruangan ?? '-' }}</td>
        <td style="text-align:center;">
          @if($a->kondisi === 'Baik')
            <span class="badge badge-baik">Baik</span>
          @elseif($a->kondisi === 'Dalam Perbaikan')
            <span class="badge badge-perbaikan">Perbaikan</span>
          @else
            <span class="badge badge-rusak">Rusak</span>
          @endif
        </td>
        <td style="font-size:8px;color:#334155;">{{ $a->penanggung_jawab ?? '-' }}</td>
        <td style="text-align:right;font-size:8px;font-weight:bold;color:#0f172a;">
          @if($a->nilai >= 1_000_000_000)
            Rp {{ number_format($a->nilai/1_000_000_000, 1) }}M
          @elseif($a->nilai >= 1_000_000)
            Rp {{ number_format($a->nilai/1_000_000, 0) }}jt
          @else
            Rp {{ number_format($a->nilai, 0, ',', '.') }}
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- FOOTER HALAMAN 2 -->
<div class="footer-wrap">
  <table class="footer-table">
    <tr>
      <td>PT. Dian Bangun Sejahtera &copy; {{ date('Y') }} — Sistem Inventaris v1.0</td>
      <td style="text-align:right;">Halaman 2 dari 2 — Dokumen ini digenerate otomatis oleh sistem</td>
    </tr>
  </table>
</div>

</body>
</html>
