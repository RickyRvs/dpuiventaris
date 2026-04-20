@extends('layouts.app')

@section('content')
<div style="padding:24px;display:flex;flex-direction:column;gap:20px;">

  {{-- ── HEADER ── --}}
  <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:4px;">Laporan & Analitik</h2>
      <p style="font-size:13px;color:#64748b;">
        @if($isAdmin)
          Ringkasan data inventaris seluruh kantor
        @else
          Laporan inventaris — <strong style="color:#f97316;">{{ $kantorName }}</strong>
        @endif
      </p>
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

  {{-- ── BADGE KANTOR untuk Operator ── --}}
  @if(!$isAdmin)
  <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1.5px solid #fed7aa;border-radius:14px;padding:14px 18px;display:flex;align-items:center;gap:12px;">
    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:18px;">apartment</span>
    </div>
    <div>
      <div style="font-size:13px;font-weight:800;color:#9a3412;">{{ $kantorName }}</div>
      <div style="font-size:11px;color:#c2410c;margin-top:2px;">Data laporan hanya mencakup kantor Anda. Hubungi Admin untuk laporan lintas kantor.</div>
    </div>
  </div>
  @endif

  {{-- ── FILTER PERIODE ── --}}
  <div class="card" style="padding:16px 20px;">
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
      <span style="font-size:12px;font-weight:700;color:#0f172a;">Periode:</span>
      @foreach(['Bulan Ini','3 Bulan','6 Bulan','Tahun Ini','Custom'] as $p)
      <button onclick="selectPeriode(this,'{{ $p }}')"
        style="padding:6px 16px;border-radius:8px;
               border:1.5px solid {{ $p==='Bulan Ini'?'#f97316':'#e2e8f0' }};
               background:{{ $p==='Bulan Ini'?'#fff7ed':'#fff' }};
               color:{{ $p==='Bulan Ini'?'#f97316':'#64748b' }};
               font-size:12px;font-weight:600;cursor:pointer;transition:all .15s;">{{ $p }}</button>
      @endforeach
      <input type="date" class="field" style="width:auto;padding:7px 12px;font-size:12px;" value="{{ now()->startOfMonth()->format('Y-m-d') }}"/>
      <span style="font-size:12px;color:#94a3b8;">s/d</span>
      <input type="date" class="field" style="width:auto;padding:7px 12px;font-size:12px;" value="{{ now()->format('Y-m-d') }}"/>
    </div>
  </div>

  {{-- ── KPI CARDS ── --}}
  @php
    $totalAset      = collect($kantorList)->sum(fn($k) => $k['stat']['total']);
    $totalBaik      = collect($kantorList)->sum(fn($k) => $k['stat']['baik']);
    $totalPerbaikan = collect($kantorList)->sum(fn($k) => $k['stat']['perbaikan']);
    $totalRusak     = collect($kantorList)->sum(fn($k) => $k['stat']['rusak']);
    $totalNilaiRaw  = $totalNilaiRaw ?? 0;
    $totalNilai     = $totalNilaiRaw >= 1_000_000_000
                        ? 'Rp '.number_format($totalNilaiRaw/1_000_000_000,1).'M'
                        : 'Rp '.number_format($totalNilaiRaw/1_000_000,0).'jt';
    $pctBaik        = $totalAset > 0 ? round($totalBaik/$totalAset*100) : 0;
  @endphp

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
    <div class="card-stat" style="border-left:3px solid #f97316;">
      <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Total Aset</span>
      <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#0f172a;margin:8px 0 4px;">{{ number_format($totalAset) }}</p>
      <p style="font-size:11px;font-weight:700;color:#64748b;">@if($isAdmin)Dari {{ count($kantorList) }} kantor aktif@else Di {{ $kantorName }}@endif</p>
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

   {{-- ✅ GRAFIK BATANG TEGAK --}}
<div class="card" style="padding:20px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
    <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;">
      {{ $isAdmin ? 'Kondisi Aset per Kantor' : 'Kondisi Aset — '.$kantorName }}
    </h3>
    <div style="display:flex;gap:10px;flex-shrink:0;">
      <div style="display:flex;align-items:center;gap:4px;">
        <div style="width:9px;height:9px;border-radius:2px;background:#22c55e;"></div>
        <span style="font-size:10px;font-weight:600;color:#64748b;">Baik</span>
      </div>
      <div style="display:flex;align-items:center;gap:4px;">
        <div style="width:9px;height:9px;border-radius:2px;background:#eab308;"></div>
        <span style="font-size:10px;font-weight:600;color:#64748b;">Perbaikan</span>
      </div>
      <div style="display:flex;align-items:center;gap:4px;">
        <div style="width:9px;height:9px;border-radius:2px;background:#ef4444;"></div>
        <span style="font-size:10px;font-weight:600;color:#64748b;">Rusak</span>
      </div>
    </div>
  </div>

  @if(!$isAdmin)
  {{-- OPERATOR: 3 batang terpisah --}}
  @php
    $stat    = $kantorList[0]['stat'] ?? ['baik'=>0,'perbaikan'=>0,'rusak'=>0];
    $maxGrup = max($stat['baik'], $stat['perbaikan'], $stat['rusak'], 1);
    $chartH  = 180;
  @endphp
  <div style="display:flex;gap:6px;margin-top:16px;">
    <div style="display:flex;flex-direction:column;justify-content:space-between;align-items:flex-end;height:{{ $chartH }}px;padding-bottom:2px;flex-shrink:0;">
      @foreach([100,75,50,25,0] as $tick)
      <span style="font-size:9px;font-weight:600;color:#cbd5e1;line-height:1;">{{ round($maxGrup*$tick/100) }}</span>
      @endforeach
    </div>
    <div style="flex:1;position:relative;">
      @foreach([0,25,50,75,100] as $gl)
      <div style="position:absolute;left:0;right:0;bottom:{{ round($gl/100*$chartH) }}px;border-top:1px dashed {{ $gl===0?'#cbd5e1':'#f1f5f9' }};"></div>
      @endforeach
      <div style="display:flex;align-items:flex-end;justify-content:center;height:{{ $chartH }}px;gap:32px;position:relative;z-index:1;">
@php $hBaik = round($stat['baik'] / $maxGrup * $chartH); @endphp
<div style="display:flex;flex-direction:column;align-items:center;">
  <div class="bar-seg" data-h="{{ $hBaik }}"
       style="position:relative;width:52px;height:0;background:#22c55e;border-radius:7px 7px 0 0;">
    <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                 font-family:'Sora',sans-serif;font-size:13px;font-weight:800;color:#fff;">{{ $stat['baik'] }}</span>
  </div>
</div>

@php $hPrb = round($stat['perbaikan'] / $maxGrup * $chartH); @endphp
<div style="display:flex;flex-direction:column;align-items:center;">
  <div class="bar-seg" data-h="{{ $hPrb }}"
       style="position:relative;width:52px;height:0;background:#eab308;border-radius:7px 7px 0 0;">
    <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                 font-family:'Sora',sans-serif;font-size:13px;font-weight:800;color:#fff;">{{ $stat['perbaikan'] }}</span>
  </div>
</div>

@php $hRsk = round($stat['rusak'] / $maxGrup * $chartH); @endphp
<div style="display:flex;flex-direction:column;align-items:center;">
  <div class="bar-seg" data-h="{{ $hRsk }}"
       style="position:relative;width:52px;height:0;background:#ef4444;border-radius:7px 7px 0 0;">
    <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                 font-family:'Sora',sans-serif;font-size:13px;font-weight:800;color:#fff;">{{ $stat['rusak'] }}</span>
  </div>
</div>
      </div>
    </div>
  </div>
  <div style="display:flex;justify-content:center;gap:32px;margin-top:8px;padding-left:28px;">
    <span style="font-size:11px;font-weight:700;color:#16a34a;width:52px;text-align:center;">Baik</span>
    <span style="font-size:11px;font-weight:700;color:#ca8a04;width:52px;text-align:center;">Perbaikan</span>
    <span style="font-size:11px;font-weight:700;color:#dc2626;width:52px;text-align:center;">Rusak</span>
  </div>

  @else
  {{-- ADMIN: stacked per kantor (tetap sama) --}}
  @php
    $maxTotal = max(collect($kantorList)->max(fn($k) => $k['stat']['total']), 1);
    $chartH   = 180;
  @endphp
  <div style="display:flex;gap:6px;margin-top:16px;">
    <div style="display:flex;flex-direction:column;justify-content:space-between;align-items:flex-end;height:{{ $chartH }}px;padding-bottom:2px;flex-shrink:0;">
      @foreach([100,75,50,25,0] as $tick)
      <span style="font-size:9px;font-weight:600;color:#cbd5e1;line-height:1;">{{ round($maxTotal*$tick/100) }}</span>
      @endforeach
    </div>
    <div style="flex:1;position:relative;">
      @foreach([0,25,50,75,100] as $gl)
      <div style="position:absolute;left:0;right:0;bottom:{{ round($gl/100*$chartH) }}px;border-top:1px dashed {{ $gl===0?'#cbd5e1':'#f1f5f9' }};"></div>
      @endforeach
      <div style="display:flex;align-items:flex-end;justify-content:space-around;height:{{ $chartH }}px;gap:10px;position:relative;z-index:1;padding:0 4px;">
        @foreach($kantorList as $kantor)
        @php
          $tot  = $kantor['stat']['total'];
          $baik = $kantor['stat']['baik'];
          $prb  = $kantor['stat']['perbaikan'];
          $rsk  = $kantor['stat']['rusak'];
          $hTotal = $tot > 0 ? round($tot / $maxTotal * $chartH) : 0;
          $hBaik  = $hTotal > 0 && $tot > 0 ? round($baik / $tot * $hTotal) : 0;
          $hPrb   = $hTotal > 0 && $tot > 0 ? round($prb  / $tot * $hTotal) : 0;
          $hRsk   = max($hTotal - $hBaik - $hPrb, 0);
        @endphp
        <div style="display:flex;flex-direction:column;align-items:center;flex:1;gap:2px;">
          <span style="font-family:'Sora',sans-serif;font-size:11px;font-weight:800;color:#0f172a;margin-bottom:3px;">{{ $tot > 0 ? $tot : '' }}</span>
          <div class="bar-wrap" style="width:100%;max-width:52px;display:flex;flex-direction:column;border-radius:7px 7px 0 0;overflow:hidden;cursor:default;"
               title="{{ $kantor['nama'] }}: {{ $baik }} Baik · {{ $prb }} Perbaikan · {{ $rsk }} Rusak">
            @if($hRsk > 0)<div class="bar-seg" data-h="{{ $hRsk }}" style="height:0;background:linear-gradient(180deg,#f87171,#dc2626);"></div>@endif
            @if($hPrb > 0)<div class="bar-seg" data-h="{{ $hPrb }}" style="height:0;background:linear-gradient(180deg,#fbbf24,#ca8a04);"></div>@endif
            @if($hBaik > 0)<div class="bar-seg" data-h="{{ $hBaik }}" style="height:0;background:linear-gradient(180deg,#4ade80,#16a34a);"></div>@endif
            @if($tot === 0)<div style="height:6px;background:#f1f5f9;border-radius:7px 7px 0 0;"></div>@endif
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  <div style="display:flex;justify-content:space-around;gap:10px;margin-top:8px;padding-left:28px;">
    @foreach($kantorList as $kantor)
    <div style="flex:1;text-align:center;max-width:52px;">
      <span style="font-size:10px;font-weight:700;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;" title="{{ $kantor['nama'] }}">{{ $kantor['short'] }}</span>
    </div>
    @endforeach
  </div>
  @endif

</div>

    {{-- Distribusi Kategori --}}
    <div class="card" style="padding:20px;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;margin-bottom:18px;">Distribusi Kategori</h3>
      @php
        $kategoriQuery = \App\Models\Aset::selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')->orderByDesc('total');
        if (!$isAdmin && ($kantorDbId ?? null)) {
            $kategoriQuery->where('kantor_id', $kantorDbId);
        }
        $kategoriData = $kategoriQuery->get();
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

  {{-- ── TABEL RINGKASAN ── --}}
  <div class="card" style="overflow:hidden;">
    <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;">
        {{ $isAdmin ? 'Ringkasan per Kantor' : 'Ringkasan — '.$kantorName }}
      </h3>
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
          $tot = $kantor['stat']['total'];
          $bk  = $kantor['stat']['baik'];
          $pb  = $kantor['stat']['perbaikan'];
          $rk  = $kantor['stat']['rusak'];
          $pct = $tot > 0 ? round($bk/$tot*100) : 0;
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

  {{-- ── DAFTAR ASET DETAIL (Operator only) ── --}}
  @if(!$isAdmin)
  <div class="card" style="overflow:hidden;">
    <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;">
        Daftar Aset — {{ $kantorName }}
      </h3>
    </div>
    <table class="tbl" style="width:100%;">
      <thead><tr>
        <th style="padding-left:20px;">Kode</th>
        <th>Nama Aset</th>
        <th>Kategori</th>
        <th>Ruangan</th>
        <th>Kondisi</th>
        <th>Nilai</th>
      </tr></thead>
      <tbody>
        @forelse($asetList as $a)
        <tr>
          <td style="padding-left:20px;font-size:12px;font-weight:700;color:#f97316;font-family:'Sora',sans-serif;">{{ $a->kode }}</td>
          <td style="font-size:13px;font-weight:600;color:#0f172a;">{{ $a->nama }}</td>
          <td style="font-size:12px;color:#64748b;">{{ $a->kategori }}</td>
          <td style="font-size:12px;color:#64748b;">{{ $a->ruangan ?? '-' }}</td>
          <td>
            @if($a->kondisi === 'Baik')
              <span style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Baik</span>
            @elseif($a->kondisi === 'Dalam Perbaikan')
              <span style="background:#fef9c3;color:#854d0e;border:1px solid #fde68a;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Perbaikan</span>
            @else
              <span style="background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:2px 10px;border-radius:999px;font-size:10px;font-weight:700;">Rusak</span>
            @endif
          </td>
          <td style="font-size:12px;font-weight:700;color:#0f172a;">Rp {{ number_format($a->nilai,0,',','.') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:24px;color:#94a3b8;font-size:13px;">Belum ada aset di kantor ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @endif

</div>

@push('scripts')
<script>
// Animasi batang naik dari bawah
document.addEventListener('DOMContentLoaded', function () {
  var segs = document.querySelectorAll('.bar-seg');
  // Set semua ke 0 dulu (sudah dari PHP height:0)
  requestAnimationFrame(function () {
    setTimeout(function () {
      segs.forEach(function (el, i) {
        var target = el.getAttribute('data-h');
        el.style.transition = 'height .55s cubic-bezier(.34,1.1,.64,1) ' + (i * 0.04) + 's';
        el.style.height = target + 'px';
      });
    }, 80);
  });
});

function selectPeriode(btn, p) {
  document.querySelectorAll('[onclick^="selectPeriode"]').forEach(function(b) {
    b.style.borderColor = '#e2e8f0';
    b.style.background  = '#fff';
    b.style.color       = '#64748b';
  });
  btn.style.borderColor = '#f97316';
  btn.style.background  = '#fff7ed';
  btn.style.color       = '#f97316';
  if (typeof showToast === 'function') showToast('Periode: ' + p);
}
</script>
@endpush
@endsection