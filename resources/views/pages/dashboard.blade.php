@extends('layouts.app')

@section('content')
<div style="padding:24px;display:flex;flex-direction:column;gap:20px;">

  <!-- Header -->
  <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:22px;color:#0f172a;margin-bottom:4px;">
        {{ $isAdmin ? 'Pusat Operasi Global' : 'Dasbor Operator' }}
      </h2>
      <p style="font-size:13px;color:#64748b;">{{ $kantorLabel }} — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div style="display:flex;gap:8px;">
      <a href="{{ route('laporan') }}" class="btn-ghost" style="font-size:12px;padding:8px 16px;">Lihat Laporan</a>
      <a href="{{ route('tambah-barang') }}" class="btn-or" style="font-size:12px;padding:8px 16px;">
        <span class="material-symbols-outlined" style="font-size:16px;">add</span> Tambah Aset
      </a>
    </div>
  </div>

  <!-- Stats Row -->
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:14px;">
    <div class="card-stat stat-accent-border">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Total Aset</span>
        <div style="width:32px;height:32px;background:#fff7ed;border-radius:8px;display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined" style="color:#f97316;font-size:18px;">inventory_2</span></div>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:28px;font-weight:800;color:#0f172a;">{{ $stat['total'] }}</p>
      <p style="font-size:11px;color:#10b981;font-weight:700;margin-top:2px;">▲ +12 bulan ini</p>
    </div>
    <div class="card-stat" style="border-left:3px solid #16a34a;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Kondisi Baik</span>
        <div style="width:32px;height:32px;background:#dcfce7;border-radius:8px;display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined fill-icon" style="color:#16a34a;font-size:18px;">check_circle</span></div>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:28px;font-weight:800;color:#0f172a;">{{ $stat['baik'] }}</p>
      <p style="font-size:11px;color:#16a34a;font-weight:700;margin-top:2px;">Siap digunakan</p>
    </div>
    <div class="card-stat" style="border-left:3px solid #ca8a04;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Dalam Perbaikan</span>
        <div style="width:32px;height:32px;background:#fef9c3;border-radius:8px;display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined" style="color:#ca8a04;font-size:18px;">build</span></div>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:28px;font-weight:800;color:#0f172a;">{{ $stat['perbaikan'] }}</p>
      <p style="font-size:11px;color:#ca8a04;font-weight:700;margin-top:2px;">Perlu tindak lanjut</p>
    </div>
    <div class="card-stat" style="border-left:3px solid #dc2626;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;">Rusak</span>
        <div style="width:32px;height:32px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined fill-icon" style="color:#dc2626;font-size:18px;">cancel</span></div>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:28px;font-weight:800;color:#0f172a;">{{ $stat['rusak'] }}</p>
      <p style="font-size:11px;color:#dc2626;font-weight:700;margin-top:2px;">Butuh perhatian segera</p>
    </div>
    @if($isAdmin)
    <div class="card-stat" style="background:linear-gradient(135deg,#f97316,#c2410c);border:none;position:relative;overflow:hidden;">
      <div style="position:absolute;right:-10px;bottom:-10px;opacity:.12;"><span class="material-symbols-outlined fill-icon" style="font-size:80px;color:#fff;">analytics</span></div>
      <div style="position:relative;z-index:1;">
        <span style="font-size:10px;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.07em;">Nilai Total</span>
        <p style="font-family:'Sora',sans-serif;font-size:26px;font-weight:800;color:#fff;margin:6px 0 4px;">Rp {{ $stat['nilai'] }}</p>
        <a href="{{ route('laporan') }}" style="font-size:11px;font-weight:700;color:rgba(255,255,255,.8);text-decoration:underline;">Lihat Laporan →</a>
      </div>
    </div>
    @endif
  </div>

  <!-- Kondisi Bar -->
  <div class="card" style="padding:18px 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
      <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:13px;color:#0f172a;">Distribusi Kondisi Aset</h3>
      <span style="font-size:11px;color:#94a3b8;">{{ count($aset) }} aset ditampilkan</span>
    </div>
    @php $total = count($aset); @endphp
    <div style="display:flex;height:12px;border-radius:999px;overflow:hidden;gap:2px;">
      @if($total > 0)
      <div style="width:{{ round($stat['baik']/$total*100,1) }}%;background:linear-gradient(90deg,#16a34a,#22c55e);border-radius:999px 0 0 999px;"></div>
      <div style="width:{{ round($stat['perbaikan']/$total*100,1) }}%;background:linear-gradient(90deg,#ca8a04,#eab308);"></div>
      <div style="width:{{ round($stat['rusak']/$total*100,1) }}%;background:linear-gradient(90deg,#dc2626,#ef4444);border-radius:0 999px 999px 0;"></div>
      @else
      <div style="width:100%;background:#f1f5f9;border-radius:999px;"></div>
      @endif
    </div>
    <div style="display:flex;gap:20px;margin-top:10px;">
      @foreach([['Baik',$stat['baik'],'#16a34a'],['Dalam Perbaikan',$stat['perbaikan'],'#ca8a04'],['Rusak',$stat['rusak'],'#dc2626']] as [$l,$n,$c])
      <div style="display:flex;align-items:center;gap:6px;">
        <span style="width:8px;height:8px;border-radius:50%;background:{{ $c }};flex-shrink:0;"></span>
        <span style="font-size:11px;color:#64748b;font-weight:600;">{{ $l }}: <strong style="color:#0f172a;">{{ $n }}</strong></span>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Main Grid -->
  <div style="display:grid;grid-template-columns:1fr {{ $isAdmin ? '300px' : '280px' }};gap:16px;">

    <!-- Aset Terbaru -->
    <div class="card" style="overflow:hidden;">
      <div style="padding:16px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:14px;color:#0f172a;">Aset Terbaru</h3>
        <a href="{{ route('inventaris') }}" style="font-size:12px;font-weight:700;color:#f97316;text-decoration:none;">Lihat Semua →</a>
      </div>
      <table class="tbl" style="width:100%;">
        <thead><tr>
          <th>Nama Aset</th>
          <th>{{ $isAdmin ? 'Kantor' : 'Ruangan' }}</th>
          <th>Kondisi</th>
          <th></th>
        </tr></thead>
        <tbody>
          @foreach(array_slice($aset, 0, 7) as $a)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;background:{{ $a['kondisi']==='Baik'?'#dcfce7':($a['kondisi']==='Rusak'?'#fee2e2':'#fef9c3') }};border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <span class="material-symbols-outlined fill-icon" style="color:{{ $a['kondisi']==='Baik'?'#16a34a':($a['kondisi']==='Rusak'?'#dc2626':'#ca8a04') }};font-size:16px;">{{ $a['kondisi']==='Baik'?'check_circle':($a['kondisi']==='Rusak'?'cancel':'build') }}</span>
                </div>
                <div>
                  <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $a['nama'] }}</div>
                  <div style="font-size:11px;color:#94a3b8;">{{ $a['kode'] }}</div>
                </div>
              </div>
            </td>
            <td style="font-size:12px;color:#64748b;">{{ $isAdmin ? $a['kantor'] : $a['ruangan'] }}</td>
            <td>
              <span class="badge-{{ strtolower(str_replace(' ','-',$a['kondisi']==='Dalam Perbaikan'?'perbaikan':strtolower($a['kondisi']))) }}">{{ $a['kondisi'] }}</span>
            </td>
            <td>
              <button onclick='showDetailModal({{ json_encode($a) }})' style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:4px;border-radius:6px;" onmouseover="this.style.background='#fff7ed';this.style.color='#f97316'" onmouseout="this.style.background='none';this.style.color='#94a3b8'">
                <span class="material-symbols-outlined" style="font-size:16px;">open_in_new</span>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Side Panel -->
    <div style="display:flex;flex-direction:column;gap:14px;">
      @if($isAdmin)
      <!-- Kapasitas per Kantor -->
      <div class="card" style="padding:18px;">
        <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:13px;color:#0f172a;margin-bottom:14px;">Kapasitas per Kantor</h3>
        @foreach($kantorList as $kantor)
        <div style="margin-bottom:12px;">
          <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
            <span style="font-size:12px;color:#334155;font-weight:600;">{{ $kantor['short'] }}</span>
            <span style="font-size:12px;font-weight:700;color:#0f172a;">{{ $kantor['stat']['total'] }}</span>
          </div>
          <div class="progress-track"><div class="progress-fill" style="width:{{ min(100,round($kantor['stat']['total']/4)) }}%;"></div></div>
        </div>
        @endforeach
      </div>
      @endif

      <!-- Pemeliharaan Mendatang -->
      <div class="card" style="padding:18px;">
        <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:13px;color:#0f172a;margin-bottom:14px;">Pemeliharaan Mendatang</h3>
        <div style="display:flex;flex-direction:column;gap:10px;">
          @foreach([['Servis Lift A1','18 Apr','Proses','#f59e0b'],['Pest Control','22 Apr','Terjadwal','#94a3b8'],['Kalibrasi AC','25 Apr','Terjadwal','#94a3b8'],['Cek Genset','1 Mei','Terjadwal','#94a3b8']] as [$n,$d,$s,$c])
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <div><p style="font-size:12px;font-weight:700;color:#0f172a;">{{ $n }}</p><p style="font-size:11px;color:#94a3b8;">{{ $d }}</p></div>
            <span style="font-size:10px;font-weight:700;color:{{ $c }};background:{{ $c }}18;padding:3px 10px;border-radius:999px;border:1px solid {{ $c }}30;">{{ $s }}</span>
          </div>
          @endforeach
          <a href="{{ route('jadwal') }}" style="width:100%;padding:8px;border-radius:10px;border:1px dashed #fed7aa;background:#fff7ed;color:#f97316;font-size:12px;font-weight:700;cursor:pointer;margin-top:4px;display:block;text-align:center;text-decoration:none;">Lihat Semua Jadwal →</a>
        </div>
      </div>

      <!-- Stok Kritis -->
      <div class="card" style="padding:16px;border-left:3px solid #ef4444;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
          <span class="material-symbols-outlined" style="color:#ef4444;font-size:18px;">warning</span>
          <h3 style="font-family:'Sora',sans-serif;font-weight:700;font-size:12px;color:#0f172a;">Stok Di Bawah Minimum</h3>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;">
          <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 10px;background:#fef2f2;border-radius:8px;">
            <span style="font-size:12px;font-weight:700;color:#0f172a;">Toner Printer HP</span>
            <span style="font-size:11px;font-weight:700;color:#dc2626;">2 unit</span>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 10px;background:#fef9c3;border-radius:8px;">
            <span style="font-size:12px;font-weight:700;color:#0f172a;">Sabuk Pengaman</span>
            <span style="font-size:11px;font-weight:700;color:#ca8a04;">8 unit</span>
          </div>
        </div>
        <a href="{{ route('stok') }}" style="width:100%;padding:7px;border-radius:8px;border:none;background:transparent;color:#ef4444;font-size:12px;font-weight:700;cursor:pointer;margin-top:8px;display:block;text-align:center;text-decoration:none;">Kelola Stok →</a>
      </div>
    </div>
  </div>
</div>
@endsection
