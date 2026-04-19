@extends('layouts.app')

@section('content')
<div style="padding:24px;display:flex;flex-direction:column;gap:20px;">

  {{-- ── HEADER ── --}}
  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:4px;">Laporan & Analitik</h2>
      <p style="font-size:13px;color:#64748b;">Ringkasan data inventaris seluruh kantor</p>
    </div>
    <div style="display:flex;gap:8px;">
      <a href="{{ route('laporan.ekspor.pdf') }}" target="_blank" class="btn-ghost">
        <span class="material-symbols-outlined" style="font-size:16px;">picture_as_pdf</span> Ekspor PDF
      </a>
      <a href="{{ route('laporan.ekspor.excel') }}" class="btn-or">
        <span class="material-symbols-outlined" style="font-size:16px;">table_view</span> Ekspor Excel
      </a>
    </div>
  </div>

  {{-- ── FILTER PERIODE (UI only) ── --}}
  <div class="card" style="padding:16px 20px;">
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
      <span style="font-size:12px;font-weight:700;color:#0f172a;">Periode:</span>
      @foreach(['Bulan Ini','3 Bulan','6 Bulan','Tahun Ini','Custom'] as $p)
      <button onclick="selectPeriode(this,'{{ $p }}')"
        style="padding:6px 16px;border-radius:8px;
               border:1.5px solid {{ $p==='Bulan Ini'?'#f97316':'#e2e8f0' }};
               background:{{ $p==='Bulan Ini'?'#fff7ed':'#fff' }};
               color:{{ $p==='Bulan Ini'?'#f97316':'#64748b' }};
               font-size:12px;font-weight:600;cursor:pointer;transition:all .15s;">
        {{ $p }}
      </button>
      @endforeach
      <input type="date" class="field" style="width:auto;padding:7px 12px;font-size:12px;" value="{{ now()->startOfMonth()->format('Y-m-d') }}"/>
      <span style="font-size:12px;color:#94a3b8;">s/d</span>
      <input type="date" class="field" style="width:auto;padding:7px 12px;font-size:12px;" value="{{ now()->format('Y-m-d') }}"/>
    </div>
  </div>

  {{-- ── KPI CARDS (data real dari controller) ── --}}
  @php
    $totalAset      = collect($kantorList)->sum(fn($k) => $k['stat']['total']);
    $totalBaik      = collect($kantorList)->sum(fn($k) => $k['stat']['baik']);
    $totalPerbaikan = collect($kantorList)->sum(fn($k) => $k['stat']['perbaikan']);
    $totalRusak     = collect($kantorList)->sum(fn($k) => $k['stat']['rusak']);
    $totalNilaiRaw  = \App\Models\Aset::sum('nilai');
    $totalNilai     = $totalNilaiRaw >= 1_000_000_000
                        ? 'Rp ' . number_format($totalNilaiRaw/1_000_000_000,1) . 'M'
                        : 'Rp ' . number_format($totalNilaiRaw/1_000_000,0) . 'jt';
    $pctBaik        = $totalAset > 0 ? round($totalBaik/$totalAset*100) : 0;
  @endphp

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
    <div class="card-stat" style="border-left:3px solid #f97316;">
      <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Total Aset Terdaftar</span>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;margin:8px 0 4px;">{{ number_format($totalAset) }}</p>
      <p style="font-size:11px;font-weight:700;color:#64748b;">Dari {{ count($kantorList) }} kantor aktif</p>
    </div>
    <div class="card-stat" style="border-left:3px solid #2563eb;">
      <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Total Nilai Aset</span>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;margin:8px 0 4px;">{{ $totalNilai }}</p>
      <p style="font-size:11px;font-weight:700;color:#64748b;">Berdasarkan nilai perolehan</p>
    </div>
    <div class="card-stat" style="border-left:3px solid #16a34a;">
      <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Kondisi Baik</span>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;margin:8px 0 4px;">{{ $totalBaik }}</p>
      <p style="font-size:11px;font-weight:700;color:#16a34a;">{{ $pctBaik }}% dari total aset</p>
    </div>
    <div class="card-stat" style="border-left:3px solid #ca8a04;">
      <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Dalam Perbaikan</span>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;margin:8px 0 4px;">{{ $totalPerbaikan }}</p>
      <p style="font-size:11px;font-weight:700;color:#ca8a04;">Perlu perhatian</p>
    </div>
    <div class="card-stat" style="border-left:3px solid #dc2626;">
      <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Aset Rusak</span>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;margin:8px 0 4px;">{{ $totalRusak }}</p>
      <p style="font-size:11px;font-weight:700;color:#dc2626;">Perlu tindak lanjut</p>
    </div>
  </div>

  {{-- ── CHARTS ROW ── --}}
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

    {{-- Kondisi per Kantor — pakai data real stat dari controller --}}
    <div class="card" style="padding:20px;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;margin-bottom:18px;">Kondisi Aset per Kantor</h3>
      @foreach($kantorList as $kantor)
      @php
        $total = $kantor['stat']['total'];
        $baik  = $kantor['stat']['baik'];
        $prb   = $kantor['stat']['perbaikan'];
        $rsk   = $kantor['stat']['rusak'];
        $pctB  = $total > 0 ? round($baik/$total*100) : 0;
        $pctP  = $total > 0 ? round($prb/$total*100)  : 0;
        $pctR  = $total > 0 ? round($rsk/$total*100)  : 0;
      @endphp
      <div style="margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
          <span style="font-size:12px;font-weight:700;color:#0f172a;">{{ $kantor['short'] }}</span>
          <span style="font-size:11px;color:#94a3b8;">{{ $total }} aset</span>
        </div>
        @if($total > 0)
        <div style="display:flex;height:10px;border-radius:999px;overflow:hidden;gap:1px;">
          @if($pctB > 0)<div style="width:{{ $pctB }}%;background:linear-gradient(90deg,#16a34a,#22c55e);border-radius:999px 0 0 999px;"></div>@endif
          @if($pctP > 0)<div style="width:{{ $pctP }}%;background:linear-gradient(90deg,#ca8a04,#eab308);"></div>@endif
          @if($pctR > 0)<div style="width:{{ $pctR }}%;background:linear-gradient(90deg,#dc2626,#ef4444);border-radius:0 999px 999px 0;"></div>@endif
        </div>
        @else
        <div style="height:10px;border-radius:999px;background:#f1f5f9;"></div>
        @endif
        <div style="display:flex;gap:12px;margin-top:5px;">
          <span style="font-size:10px;color:#16a34a;font-weight:600;">Baik: {{ $baik }}</span>
          <span style="font-size:10px;color:#ca8a04;font-weight:600;">Perbaikan: {{ $prb }}</span>
          <span style="font-size:10px;color:#dc2626;font-weight:600;">Rusak: {{ $rsk }}</span>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Distribusi Kategori — data real dari DB --}}
    <div class="card" style="padding:20px;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;margin-bottom:18px;">Distribusi Kategori</h3>
      @php
        $kategoriData = \App\Models\Aset::selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();
        $maxKat = $kategoriData->max('total') ?: 1;
      @endphp
      @forelse($kategoriData as $kat)
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
        <span style="font-size:12px;color:#334155;font-weight:600;width:140px;flex-shrink:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
              title="{{ $kat->kategori }}">{{ $kat->kategori }}</span>
        <div class="progress-track" style="flex:1;">
          <div class="progress-fill" style="width:{{ round($kat->total/$maxKat*100) }}%;"></div>
        </div>
        <span style="font-size:12px;font-weight:700;color:#0f172a;width:32px;text-align:right;">{{ $kat->total }}</span>
      </div>
      @empty
      <p style="font-size:13px;color:#94a3b8;text-align:center;padding:20px 0;">Belum ada data aset.</p>
      @endforelse
    </div>
  </div>

  {{-- ── TABEL RINGKASAN PER KANTOR — data real ── --}}
  <div class="card" style="overflow:hidden;">
    <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;">Ringkasan per Kantor</h3>
      <span style="font-size:11px;color:#94a3b8;">Data per {{ now()->translatedFormat('d F Y') }}</span>
    </div>
    <table class="tbl" style="width:100%;">
      <thead><tr>
        <th style="padding-left:20px;">Kantor</th>
        <th>Total Aset</th>
        <th>Baik</th>
        <th>Perbaikan</th>
        <th>Rusak</th>
        <th>Nilai Aset</th>
        <th>% Kondisi Baik</th>
      </tr></thead>
      <tbody>
        @foreach($kantorList as $kantor)
        @php
          $tot  = $kantor['stat']['total'];
          $bk   = $kantor['stat']['baik'];
          $pb   = $kantor['stat']['perbaikan'];
          $rk   = $kantor['stat']['rusak'];
          $pct  = $tot > 0 ? round($bk/$tot*100) : 0;
        @endphp
        <tr>
          <td style="padding-left:20px;font-size:13px;font-weight:700;color:#0f172a;">{{ $kantor['nama'] }}</td>
          <td style="font-size:13px;font-weight:700;color:#0f172a;">{{ $tot }}</td>
          <td style="color:#16a34a;font-weight:700;font-size:13px;">{{ $bk }}</td>
          <td style="color:#ca8a04;font-weight:700;font-size:13px;">{{ $pb }}</td>
          <td style="color:#dc2626;font-weight:700;font-size:13px;">{{ $rk }}</td>
          <td style="font-size:13px;font-weight:700;color:#0f172a;">{{ $kantor['stat']['nilai'] }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="progress-track" style="width:60px;">
                <div class="progress-fill" style="width:{{ $pct }}%;"></div>
              </div>
              <span style="font-size:12px;font-weight:700;color:{{ $pct>=80?'#16a34a':($pct>=50?'#ca8a04':'#dc2626') }};">{{ $pct }}%</span>
            </div>
          </td>
        </tr>
        @endforeach

        {{-- Baris Total --}}
        @php $pctTotal = $totalAset > 0 ? round($totalBaik/$totalAset*100) : 0; @endphp
        <tr style="background:#f8fafc;border-top:2px solid #e2e8f0;">
          <td style="padding-left:20px;font-size:13px;font-weight:800;color:#0f172a;">TOTAL</td>
          <td style="font-size:13px;font-weight:800;color:#0f172a;">{{ $totalAset }}</td>
          <td style="color:#16a34a;font-weight:800;font-size:13px;">{{ $totalBaik }}</td>
          <td style="color:#ca8a04;font-weight:800;font-size:13px;">{{ $totalPerbaikan }}</td>
          <td style="color:#dc2626;font-weight:800;font-size:13px;">{{ $totalRusak }}</td>
          <td style="font-size:13px;font-weight:800;color:#0f172a;">{{ $totalNilai }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="progress-track" style="width:60px;">
                <div class="progress-fill" style="width:{{ $pctTotal }}%;"></div>
              </div>
              <span style="font-size:12px;font-weight:800;color:#f97316;">{{ $pctTotal }}%</span>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

@push('scripts')
<script>
function selectPeriode(btn, p) {
  document.querySelectorAll('[onclick^="selectPeriode"]').forEach(b => {
    b.style.borderColor='#e2e8f0'; b.style.background='#fff'; b.style.color='#64748b';
  });
  btn.style.borderColor='#f97316'; btn.style.background='#fff7ed'; btn.style.color='#f97316';
  showToast('Periode: ' + p);
}
</script>
@endpush
@endsection