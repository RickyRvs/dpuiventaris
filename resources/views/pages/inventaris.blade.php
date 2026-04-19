@extends('layouts.app')

@section('content')
<div style="padding:20px;display:flex;flex-direction:column;gap:16px;">

  {{-- Alert --}}
  @if(session('success'))
  <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #86efac;border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px;">
    <div style="width:28px;height:28px;background:#16a34a;border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:16px;">check_circle</span>
    </div>
    <span style="font-size:13px;font-weight:600;color:#166534;">{{ session('success') }}</span>
  </div>
  @endif
  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:12px 16px;display:flex;align-items:center;gap:10px;">
    <div style="width:28px;height:28px;background:#dc2626;border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:16px;">error</span>
    </div>
    <span style="font-size:13px;font-weight:600;color:#991b1b;">{{ $errors->first() }}</span>
  </div>
  @endif

  {{-- Header --}}
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:3px;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:10px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:19px;">inventory_2</span>
        </div>
        <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;">Daftar Inventaris</h2>
      </div>
      <p style="font-size:12.5px;color:#64748b;margin-left:46px;">
        <strong style="color:#f97316;">{{ $aset->total() }}</strong> aset terdaftar
        @if(request()->hasAny(['q','kondisi','kategori','kantor']))
          · <span style="color:#94a3b8;font-style:italic;">hasil filter</span>
        @endif
      </p>
    </div>
    {{-- Tombol berubah: onclick openModal bukan href --}}
    <button type="button" onclick="openModal('tambah-modal')" class="btn-or">
      <span class="material-symbols-outlined" style="font-size:16px;">add_circle</span> Tambah Aset
    </button>
  </div>

  {{-- Filter Bar --}}
  <div style="background:#fff;border-radius:14px;padding:14px 16px;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.04);">
    <form method="GET" action="{{ route('inventaris') }}" style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
      <div style="position:relative;flex:1;min-width:180px;">
        <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:15px;">search</span>
        <input type="text" name="q" placeholder="Cari nama, kode, kategori..." value="{{ request('q') }}" class="field" style="padding-left:34px;font-size:12.5px;"/>
      </div>
      <select name="kondisi" class="field" style="width:auto;padding:9px 12px;font-size:12.5px;" onchange="this.form.submit()">
        <option value="">Semua Kondisi</option>
        <option value="Baik"            {{ request('kondisi')==='Baik'?'selected':'' }}>Baik</option>
        <option value="Dalam Perbaikan" {{ request('kondisi')==='Dalam Perbaikan'?'selected':'' }}>Dalam Perbaikan</option>
        <option value="Rusak"           {{ request('kondisi')==='Rusak'?'selected':'' }}>Rusak</option>
      </select>
      <select name="kategori" class="field" style="width:auto;padding:9px 12px;font-size:12.5px;" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach(['Elektronik & IT','Furnitur Kantor','Kendaraan','Alat Berat','Infrastruktur','Peralatan Survey','Mekanikal & Elektrikal','Peralatan Konstruksi'] as $kat)
        <option value="{{ $kat }}" {{ request('kategori')===$kat?'selected':'' }}>{{ $kat }}</option>
        @endforeach
      </select>
      @if($isAdmin)
      <select name="kantor" class="field" style="width:auto;padding:9px 12px;font-size:12.5px;" onchange="this.form.submit()">
        <option value="">Semua Kantor</option>
        @foreach($kantorList as $kantor)
        <option value="{{ $kantor->short_name }}" {{ request('kantor')===$kantor->short_name?'selected':'' }}>{{ $kantor->short_name }}</option>
        @endforeach
      </select>
      @endif
      <button type="submit" class="btn-or" style="padding:9px 16px;font-size:12.5px;">
        <span class="material-symbols-outlined" style="font-size:15px;">search</span> Cari
      </button>
      @if(request()->hasAny(['q','kondisi','kategori','kantor']))
      <a href="{{ route('inventaris') }}" style="padding:9px 12px;background:#fef2f2;color:#ef4444;border:1.5px solid #fecaca;border-radius:10px;font-size:12.5px;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:5px;">
        <span class="material-symbols-outlined" style="font-size:14px;">close</span> Reset
      </a>
      @endif
    </form>
  </div>

  {{-- Table Card --}}
  <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;">

    @if($aset->count() === 0)
    <div style="padding:60px 40px;text-align:center;">
      <div style="width:64px;height:64px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <span class="material-symbols-outlined" style="font-size:32px;color:#cbd5e1;">inventory_2</span>
      </div>
      <p style="font-size:14px;font-weight:700;color:#334155;margin-bottom:6px;">Tidak ada aset ditemukan</p>
      <p style="font-size:12.5px;color:#94a3b8;margin-bottom:18px;">Coba ubah filter atau tambah aset baru</p>
      <button type="button" onclick="openModal('tambah-modal')" class="btn-or">
        <span class="material-symbols-outlined" style="font-size:15px;">add_circle</span> Tambah Aset
      </button>
    </div>

    @else
    <div style="width:100%;overflow-x:auto;">
      <table style="width:100%;min-width:780px;border-collapse:collapse;table-layout:fixed;">
        <colgroup>
          <col style="width:110px;"/>
          <col style="width:220px;"/>
          <col style="width:130px;"/>
          <col style="width:100px;"/>
          <col style="width:120px;"/>
          <col style="width:120px;"/>
          <col style="width:100px;"/>
        </colgroup>
        <thead>
          <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;">Kode</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Nama Aset</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Kategori</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">{{ $isAdmin ? 'Kantor' : 'Ruangan' }}</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Kondisi</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Nilai</th>
            <th style="padding:11px 12px;text-align:right;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($aset as $a)
          @php
            $isbaik  = $a->kondisi === 'Baik';
            $isrusak = $a->kondisi === 'Rusak';
            $kColor  = $isbaik ? '#16a34a' : ($isrusak ? '#dc2626' : '#ca8a04');
            $kBg     = $isbaik ? '#dcfce7'  : ($isrusak ? '#fee2e2'  : '#fef9c3');
            $kIcon   = $isbaik ? 'check_circle' : ($isrusak ? 'cancel' : 'build');

            $tglRaw = $a->tanggal_pengadaan ?? '-';
            $tgl = '-';
            if ($tglRaw && $tglRaw !== '-') {
              try { $tgl = \Carbon\Carbon::parse($tglRaw)->format('d/m/Y'); }
              catch (\Exception $e) { $tgl = $tglRaw; }
            }

            $asetJson = json_encode([
              'id'               => $a->id,
              'kode'             => $a->kode,
              'nama'             => $a->nama,
              'kategori'         => $a->kategori,
              'kondisi'          => $a->kondisi,
              'nilai'            => number_format($a->nilai, 0, ',', '.'),
              'ruangan'          => $a->ruangan ?? '-',
              'kantor'           => $a->kantor?->short_name ?? '-',
              'serial_number'    => $a->serial_number ?? '-',
              'penanggung_jawab' => $a->penanggung_jawab ?? '-',
              'tanggal_pengadaan'=> $tgl,
              'merek'            => $a->merek ?? '-',
              'model'            => $a->model ?? '-',
              'catatan'          => $a->catatan ?? '-',
            ]);
          @endphp
          <tr style="border-bottom:1px solid #f1f5f9;transition:background .12s;"
              onmouseover="this.style.background='#fafafa'"
              onmouseout="this.style.background='transparent'">
            <td style="padding:12px 12px;">
              <span style="font-family:monospace;font-size:11px;font-weight:700;color:#64748b;background:#f1f5f9;padding:3px 8px;border-radius:5px;white-space:nowrap;display:inline-block;max-width:100%;overflow:hidden;text-overflow:ellipsis;">{{ $a->kode }}</span>
            </td>
            <td style="padding:12px 12px;">
              <div style="display:flex;align-items:center;gap:9px;min-width:0;">
                <div style="width:32px;height:32px;min-width:32px;background:{{ $kBg }};border-radius:9px;display:flex;align-items:center;justify-content:center;">
                  <span class="material-symbols-outlined fill-icon" style="color:{{ $kColor }};font-size:16px;">{{ $kIcon }}</span>
                </div>
                <div style="min-width:0;">
                  <div style="font-size:12.5px;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $a->nama }}</div>
                  <div style="font-size:11px;color:#94a3b8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">PJ: {{ $a->penanggung_jawab ?? '—' }}</div>
                </div>
              </div>
            </td>
            <td style="padding:12px 12px;">
              <span style="font-size:11px;font-weight:600;color:#475569;background:#f1f5f9;padding:3px 8px;border-radius:20px;display:inline-block;max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $a->kategori }}</span>
            </td>
            <td style="padding:12px 12px;">
              <div style="display:flex;align-items:center;gap:4px;font-size:12px;color:#475569;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                <span class="material-symbols-outlined" style="font-size:13px;color:#94a3b8;flex-shrink:0;">{{ $isAdmin ? 'location_on' : 'door_open' }}</span>
                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $isAdmin ? ($a->kantor?->short_name ?? '—') : ($a->ruangan ?? '—') }}</span>
              </div>
            </td>
            <td style="padding:12px 12px;">
              <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:{{ $kColor }};background:{{ $kBg }};padding:4px 10px;border-radius:20px;white-space:nowrap;">
                <span style="width:5px;height:5px;border-radius:50%;background:{{ $kColor }};flex-shrink:0;"></span>
                {{ $a->kondisi }}
              </span>
            </td>
            <td style="padding:12px 12px;font-size:12.5px;font-weight:700;color:#0f172a;white-space:nowrap;">
              Rp {{ number_format($a->nilai, 0, ',', '.') }}
            </td>
            <td style="padding:12px 12px;">
              <div style="display:flex;gap:5px;justify-content:flex-end;">
                <button type="button"
                  onclick='showDetailModal({{ $asetJson }})'
                  title="Lihat Detail"
                  style="width:30px;height:30px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
                  onmouseover="this.style.background='#fff7ed';this.style.borderColor='#fdba74'"
                  onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                  <span class="material-symbols-outlined" style="font-size:14px;color:#f97316;">visibility</span>
                </button>
                <button type="button"
                  onclick="openStatusModal({{ $a->id }}, '{{ addslashes($a->nama) }}', '{{ $a->kondisi }}')"
                  title="Ubah Kondisi"
                  style="width:30px;height:30px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
                  onmouseover="this.style.background='#eff6ff';this.style.borderColor='#93c5fd'"
                  onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                  <span class="material-symbols-outlined" style="font-size:14px;color:#2563eb;">edit</span>
                </button>
                <button type="button"
                  onclick="confirmDelete({{ $a->id }}, '{{ addslashes($a->nama) }}')"
                  title="Hapus Aset"
                  style="width:30px;height:30px;background:#fef2f2;border:1.5px solid #fecaca;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
                  onmouseover="this.style.background='#fee2e2';this.style.borderColor='#f87171'"
                  onmouseout="this.style.background='#fef2f2';this.style.borderColor='#fecaca'">
                  <span class="material-symbols-outlined" style="font-size:14px;color:#dc2626;">delete</span>
                </button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div style="padding:12px 16px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;">
      <span style="font-size:12px;color:#94a3b8;">
        Menampilkan <strong style="color:#334155;">{{ $aset->firstItem() }}–{{ $aset->lastItem() }}</strong>
        dari <strong style="color:#334155;">{{ $aset->total() }}</strong> aset
      </span>
      @if($aset->hasPages())
      <div style="display:flex;gap:4px;">
        @if($aset->onFirstPage())
          <span style="width:30px;height:30px;border-radius:7px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;font-size:13px;display:flex;align-items:center;justify-content:center;">‹</span>
        @else
          <a href="{{ $aset->previousPageUrl() }}" style="width:30px;height:30px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;display:flex;align-items:center;justify-content:center;text-decoration:none;" onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">‹</a>
        @endif
        @foreach($aset->getUrlRange(max(1,$aset->currentPage()-2), min($aset->lastPage(),$aset->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" style="width:30px;height:30px;border-radius:7px;border:1.5px solid {{ $page==$aset->currentPage()?'#f97316':'#e2e8f0' }};background:{{ $page==$aset->currentPage()?'#fff7ed':'#fff' }};color:{{ $page==$aset->currentPage()?'#f97316':'#64748b' }};font-size:11.5px;font-weight:700;display:flex;align-items:center;justify-content:center;text-decoration:none;">{{ $page }}</a>
        @endforeach
        @if($aset->hasMorePages())
          <a href="{{ $aset->nextPageUrl() }}" style="width:30px;height:30px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;display:flex;align-items:center;justify-content:center;text-decoration:none;" onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">›</a>
        @endif
      </div>
      @endif
    </div>
    @endif
  </div>
</div>

@push('scripts')

{{-- ══════════════════════════════════════════════════
     MODAL: TAMBAH ASET BARU
     ══════════════════════════════════════════════════ --}}
<div class="modal" id="tambah-modal">
  <div class="modal-box" style="background:#fff;border-radius:22px;width:700px;max-width:96vw;max-height:92vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 24px 80px rgba(0,0,0,.22);">

    {{-- Header Modal --}}
    <div style="padding:20px 24px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:20px;">add_box</span>
        </div>
        <div>
          <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;margin-bottom:2px;">Tambah Aset Baru</h3>
          <p style="font-size:11.5px;color:#94a3b8;">Isi formulir untuk mendaftarkan aset ke inventaris</p>
        </div>
      </div>
      <button onclick="closeModal('tambah-modal')"
        style="border:none;background:#f1f5f9;border-radius:9px;width:32px;height:32px;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
        onmouseover="this.style.background='#fee2e2';this.style.color='#dc2626'"
        onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b'">×</button>
    </div>

    {{-- Step Indicator --}}
    <div style="padding:14px 24px;border-bottom:1px solid #f1f5f9;display:flex;gap:6px;flex-shrink:0;" id="tambah-steps">
      @php $steps = ['Informasi Dasar','Lokasi & PJ','Pengadaan']; @endphp
      @foreach($steps as $i => $label)
      <div id="step-indicator-{{ $i+1 }}"
        style="flex:1;display:flex;align-items:center;gap:7px;padding:8px 12px;border-radius:10px;border:1.5px solid {{ $i===0?'#f97316':'#e2e8f0' }};background:{{ $i===0?'#fff7ed':'#fff' }};cursor:pointer;transition:all .2s;"
        onclick="goToStep({{ $i+1 }})">
        <div id="step-dot-{{ $i+1 }}" style="width:22px;height:22px;border-radius:50%;background:{{ $i===0?'linear-gradient(135deg,#f97316,#c2410c)':'#f1f5f9' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:{{ $i===0?'#fff':'#94a3b8' }};">{{ $i+1 }}</div>
        <span id="step-label-{{ $i+1 }}" style="font-size:11px;font-weight:700;color:{{ $i===0?'#f97316':'#94a3b8' }};white-space:nowrap;">{{ $label }}</span>
      </div>
      @if(!$loop->last)
      <div style="display:flex;align-items:center;color:#cbd5e1;font-size:12px;">›</div>
      @endif
      @endforeach
    </div>

    {{-- Form Body --}}
    <form method="POST" action="{{ route('tambah-barang.store') }}" id="tambah-form"
      style="display:flex;flex-direction:column;flex:1;overflow:hidden;">
      @csrf

      {{-- Scrollable content area --}}
      <div style="overflow-y:auto;flex:1;padding:20px 24px;">

        {{-- STEP 1: Informasi Dasar --}}
        <div id="step-panel-1">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

            {{-- Nama Barang --}}
            <div style="grid-column:1/-1;">
              <label class="lbl">Nama Barang <span style="color:#ef4444;">*</span></label>
              <input type="text" name="nama" placeholder="Contoh: Workstation Dell Precision 3660"
                class="field" value="{{ old('nama') }}" required
                style="font-size:13px;"/>
            </div>

            {{-- Kategori --}}
            <div>
              <label class="lbl">Kategori <span style="color:#ef4444;">*</span></label>
              <select name="kategori" class="field" required style="font-size:13px;">
                <option value="">-- Pilih Kategori --</option>
                @foreach(['Elektronik & IT','Furnitur Kantor','Peralatan Survey','Kendaraan','Alat Berat','Infrastruktur','Mekanikal & Elektrikal','Peralatan Konstruksi','Konsumabel'] as $kat)
                <option value="{{ $kat }}" {{ old('kategori')===$kat?'selected':'' }}>{{ $kat }}</option>
                @endforeach
              </select>
            </div>

            {{-- Serial Number --}}
            <div>
              <label class="lbl">Nomor Seri / SKU</label>
              <input type="text" name="sn" placeholder="SN-XXXXXX" class="field"
                value="{{ old('sn') }}" style="font-size:13px;"/>
            </div>

            {{-- Merek --}}
            <div>
              <label class="lbl">Merek / Pabrikan</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">storefront</span>
                <input type="text" name="merek" placeholder="Dell, Daikin, Toyota..." class="field"
                  value="{{ old('merek') }}" style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Model --}}
            <div>
              <label class="lbl">Model / Tipe</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">layers</span>
                <input type="text" name="model" placeholder="Precision 3660 Tower" class="field"
                  value="{{ old('model') }}" style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Kode (auto) --}}
            <div>
              <label class="lbl">Kode Aset</label>
              <div style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:11px;padding:10px 14px;">
                <span class="material-symbols-outlined" style="font-size:15px;color:#94a3b8;">auto_awesome</span>
                <span style="font-size:12px;color:#94a3b8;font-style:italic;">Digenerate otomatis</span>
              </div>
            </div>
          </div>
        </div>

        {{-- STEP 2: Lokasi & PJ --}}
        <div id="step-panel-2" style="display:none;">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

            {{-- Kantor --}}
            <div style="grid-column:1/-1;">
              <label class="lbl">Kantor <span style="color:#ef4444;">*</span></label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">corporate_fare</span>
                <select name="kantor_id" class="field"
                  {{ !$isAdmin ? 'disabled' : '' }} required
                  style="padding-left:34px;font-size:13px;">
                  <option value="">-- Pilih Kantor --</option>
                  @foreach($kantorList as $kantor)
                  @php $selected = old('kantor_id') == $kantor->id || (!$isAdmin && session('kantor_db_id') == $kantor->id); @endphp
                  <option value="{{ $kantor->id }}" {{ $selected?'selected':'' }}>{{ $kantor->nama }}</option>
                  @endforeach
                </select>
              </div>
              @if(!$isAdmin)
              <input type="hidden" name="kantor_id" value="{{ session('kantor_db_id') }}"/>
              @endif
            </div>

            {{-- Ruangan --}}
            <div>
              <label class="lbl">Ruangan / Lokasi</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">door_open</span>
                <input type="text" name="ruangan" placeholder="Lt. 2 - Studio Desain" class="field"
                  value="{{ old('ruangan') }}" style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Penanggung Jawab --}}
            <div>
              <label class="lbl">Penanggung Jawab <span style="color:#ef4444;">*</span></label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">person</span>
                <input type="text" name="pj" placeholder="Nama penanggung jawab" class="field"
                  value="{{ old('pj') }}" required style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Info box --}}
            <div style="grid-column:1/-1;">
              <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe;border-radius:12px;padding:12px 16px;display:flex;align-items:flex-start;gap:10px;">
                <span class="material-symbols-outlined fill-icon" style="color:#2563eb;font-size:18px;flex-shrink:0;margin-top:1px;">info</span>
                <p style="font-size:12px;color:#1e40af;line-height:1.5;">
                  Pilih kantor yang merupakan lokasi utama aset ini berada.
                  Ruangan bersifat opsional namun membantu pelacakan aset.
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- STEP 3: Pengadaan & Kondisi --}}
        <div id="step-panel-3" style="display:none;">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

            {{-- Nilai --}}
            <div>
              <label class="lbl">Nilai Perolehan (Rp)</label>
              <div style="position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:700;color:#94a3b8;">Rp</span>
                <input type="text" name="nilai" id="tambah-nilai-input"
                  placeholder="0" class="field"
                  value="{{ old('nilai') }}"
                  oninput="formatRupiahTambah(this)"
                  style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Tanggal Pengadaan --}}
            <div>
              <label class="lbl">Tanggal Pengadaan</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">calendar_today</span>
                <input type="date" name="tanggal" class="field"
                  value="{{ old('tanggal') }}" style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Kondisi --}}
            <div style="grid-column:1/-1;">
              <label class="lbl">Kondisi Awal <span style="color:#ef4444;">*</span></label>
              <div style="display:flex;gap:8px;">
                @foreach([
                  ['Baik','check_circle','#16a34a','#dcfce7','Siap pakai'],
                  ['Dalam Perbaikan','build','#ca8a04','#fef9c3','Perlu perbaikan'],
                  ['Rusak','cancel','#dc2626','#fee2e2','Tidak berfungsi'],
                ] as [$val, $icon, $color, $bg, $desc])
                <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:10px;border:1.5px solid #e2e8f0;cursor:pointer;transition:all .15s;"
                  id="tambah-kondisi-label-{{ Str::slug($val) }}"
                  onmouseover="this.style.borderColor='{{ $color }}';this.style.background='{{ $bg }}'"
                  onmouseout="syncKondisiStyle('{{ Str::slug($val) }}')">
                  <input type="radio" name="kondisi" value="{{ $val }}"
                    id="tambah-radio-{{ Str::slug($val) }}"
                    style="accent-color:{{ $color }};width:14px;height:14px;"
                    {{ old('kondisi','Baik')===$val?'checked':'' }}
                    onchange="syncAllKondisiStyles()"/>
                  <div style="width:26px;height:26px;background:{{ $bg }};border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined fill-icon" style="color:{{ $color }};font-size:14px;">{{ $icon }}</span>
                  </div>
                  <div>
                    <p style="font-size:12px;font-weight:700;color:#0f172a;">{{ $val }}</p>
                    <p style="font-size:10.5px;color:#94a3b8;">{{ $desc }}</p>
                  </div>
                </label>
                @endforeach
              </div>
            </div>

            {{-- Garansi --}}
            <div>
              <label class="lbl">Garansi (Bulan)</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">verified_user</span>
                <input type="number" name="garansi" placeholder="12" class="field"
                  min="0" value="{{ old('garansi') }}" style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Garansi Habis --}}
            <div>
              <label class="lbl">Tanggal Garansi Habis</label>
              <div style="position:relative;">
                <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">event_busy</span>
                <input type="date" name="garansi_habis" class="field"
                  value="{{ old('garansi_habis') }}" style="padding-left:34px;font-size:13px;"/>
              </div>
            </div>

            {{-- Catatan --}}
            <div style="grid-column:1/-1;">
              <label class="lbl">Catatan Tambahan</label>
              <textarea name="catatan" placeholder="Spesifikasi khusus, catatan kondisi, atau informasi lainnya..."
                class="field" style="height:80px;resize:vertical;font-size:13px;">{{ old('catatan') }}</textarea>
            </div>
          </div>
        </div>
      </div>

      {{-- Footer Navigasi Step --}}
      <div style="padding:14px 24px;border-top:1px solid #f1f5f9;display:flex;flex-direction:column;gap:8px;flex-shrink:0;background:#fafafa;border-radius:0 0 22px 22px;">

        {{-- Inline error strip --}}
        <div id="tambah-step-error"
          style="display:none;align-items:center;gap:8px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:9px 14px;">
          <span class="material-symbols-outlined fill-icon" style="color:#dc2626;font-size:16px;flex-shrink:0;">error</span>
          <span style="font-size:12.5px;font-weight:600;color:#991b1b;"></span>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
        <button type="button" id="tambah-btn-prev"
          onclick="prevStep()"
          style="display:none;padding:10px 18px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:11px;font-size:13px;font-weight:700;color:#475569;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .15s;"
          onmouseover="this.style.borderColor='#94a3b8'"
          onmouseout="this.style.borderColor='#e2e8f0'">
          <span class="material-symbols-outlined" style="font-size:15px;">arrow_back</span> Sebelumnya
        </button>
        <span style="flex:1;"></span>
        <button type="button" onclick="closeModal('tambah-modal')" class="btn-ghost">Batal</button>
        <button type="button" id="tambah-btn-next"
          onclick="nextStep()"
          class="btn-or">
          Selanjutnya <span class="material-symbols-outlined" style="font-size:15px;">arrow_forward</span>
        </button>
        <button type="submit" id="tambah-btn-submit"
          style="display:none;"
          class="btn-or">
          <span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan Aset
        </button>
        </div>{{-- end flex row --}}
      </div>{{-- end footer --}}
    </form>
  </div>
</div>

{{-- ── STATUS MODAL ─────────────────────────────────── --}}
<div class="modal" id="status-modal">
  <div class="modal-box" style="background:#fff;border-radius:20px;width:420px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <div>
        <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;">Ubah Status Kondisi</h3>
        <p style="font-size:12px;color:#64748b;margin-top:2px;" id="status-modal-nama"></p>
      </div>
      <button onclick="closeModal('status-modal')" style="border:none;background:#f1f5f9;border-radius:7px;width:28px;height:28px;cursor:pointer;font-size:17px;color:#64748b;line-height:1;display:flex;align-items:center;justify-content:center;">×</button>
    </div>
    <form method="POST" action="{{ route('inventaris.status') }}">
      @csrf
      <input type="hidden" name="id" id="status-aset-id"/>
      <div style="padding:18px 22px;display:flex;flex-direction:column;gap:8px;">
        @foreach([
          ['Baik',            'check_circle', '#16a34a', '#dcfce7', 'Aset berfungsi normal, siap digunakan'],
          ['Dalam Perbaikan', 'build',        '#ca8a04', '#fef9c3', 'Sedang diperbaiki atau dalam pemeliharaan'],
          ['Rusak',           'cancel',       '#dc2626', '#fee2e2', 'Tidak dapat digunakan, perlu tindakan segera'],
        ] as [$val, $icon, $color, $bg, $desc])
        <label id="label-kondisi-{{ Str::slug($val) }}"
          style="display:flex;align-items:center;gap:10px;padding:12px;border-radius:10px;border:1.5px solid #e2e8f0;cursor:pointer;transition:all .15s;"
          onmouseover="this.style.borderColor='{{ $color }}';this.style.background='{{ $bg }}'"
          onmouseout="if(!document.getElementById('radio-{{ Str::slug($val) }}').checked){this.style.borderColor='#e2e8f0';this.style.background='#fff'}">
          <input type="radio" id="radio-{{ Str::slug($val) }}" name="kondisi" value="{{ $val }}"
            style="accent-color:{{ $color }};width:15px;height:15px;"
            onchange="highlightKondisi('{{ Str::slug($val) }}')"/>
          <div style="width:28px;height:28px;background:{{ $bg }};border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-symbols-outlined fill-icon" style="color:{{ $color }};font-size:15px;">{{ $icon }}</span>
          </div>
          <div>
            <p style="font-size:13px;font-weight:700;color:#0f172a;">{{ $val }}</p>
            <p style="font-size:11px;color:#94a3b8;">{{ $desc }}</p>
          </div>
        </label>
        @endforeach
      </div>
      <div style="padding:0 22px 18px;display:flex;gap:8px;">
        <button type="button" onclick="closeModal('status-modal')" class="btn-ghost" style="flex:1;justify-content:center;">Batal</button>
        <button type="submit" class="btn-or" style="flex:1;justify-content:center;">
          <span class="material-symbols-outlined" style="font-size:15px;">save</span> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ── DELETE MODAL ─────────────────────────────────── --}}
<div class="modal" id="delete-modal">
  <div class="modal-box" style="background:#fff;border-radius:20px;width:400px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:28px 26px;text-align:center;">
      <div style="width:60px;height:60px;background:#fef2f2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;border:2px solid #fecaca;">
        <span class="material-symbols-outlined fill-icon" style="color:#ef4444;font-size:28px;">delete_forever</span>
      </div>
      <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;margin-bottom:7px;">Hapus Aset?</h3>
      <p style="font-size:12.5px;color:#64748b;margin-bottom:6px;">Aset berikut akan dihapus permanen:</p>
      <p style="font-size:13.5px;font-weight:700;color:#0f172a;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;padding:9px 14px;margin-bottom:18px;" id="delete-nama-display"></p>
      <p style="font-size:12px;color:#ef4444;margin-bottom:22px;">⚠️ Tindakan ini tidak dapat dibatalkan.</p>
      <form method="POST" action="{{ route('inventaris.delete') }}" style="display:flex;gap:8px;">
        @csrf
        <input type="hidden" name="id" id="delete-aset-id"/>
        <button type="button" onclick="closeModal('delete-modal')" class="btn-ghost" style="flex:1;justify-content:center;">Batal</button>
        <button type="submit" style="flex:1;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:11px;padding:10px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;">
          <span class="material-symbols-outlined" style="font-size:15px;">delete_forever</span> Ya, Hapus
        </button>
      </form>
    </div>
  </div>
</div>

{{-- ── DETAIL MODAL ─────────────────────────────────── --}}
<div class="modal" id="detail-modal">
  <div class="modal-box" style="background:#fff;border-radius:20px;width:580px;max-width:95vw;max-height:88vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div id="detail-modal-content"></div>
  </div>
</div>

<style>
/* Label helper */
.lbl {
  display: block;
  font-size: 10px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .08em;
  margin-bottom: 6px;
}
/* Step btn hover */
#tambah-btn-prev { display: none; }
</style>

<script>
var URL_QR_LABEL  = "{{ route('qr-label') }}";
var CURRENT_STEP  = 1;
var TOTAL_STEPS   = 3;
var HAS_OLD_INPUT = {{ old('nama') ? 'true' : 'false' }};

/* ── Auto-open modal jika ada validasi error dari store ── */
window.addEventListener('DOMContentLoaded', function () {
  if (HAS_OLD_INPUT || {{ $errors->hasBag('default') && $errors->any() ? 'true' : 'false' }}) {
    openModal('tambah-modal');
    syncAllKondisiStyles();
  }
});

/* ══ STEP WIZARD ══════════════════════════════════════ */
var stepColors = {
  1: { border: '#f97316', bg: '#fff7ed', dotBg: 'linear-gradient(135deg,#f97316,#c2410c)', dotColor: '#fff', labelColor: '#f97316' },
  done: { border: '#dcfce7', bg: '#f0fdf4', dotBg: '#16a34a', dotColor: '#fff', labelColor: '#16a34a' },
  idle: { border: '#e2e8f0', bg: '#fff',    dotBg: '#f1f5f9', dotColor: '#94a3b8', labelColor: '#94a3b8' },
};

function goToStep(step) {
  // hanya bisa navigasi ke step sebelumnya atau saat ini
  if (step > CURRENT_STEP) return;
  CURRENT_STEP = step;
  renderStep();
}

function nextStep() {
  if (!validateStep(CURRENT_STEP)) return;
  if (CURRENT_STEP < TOTAL_STEPS) {
    CURRENT_STEP++;
    renderStep();
  }
}

function prevStep() {
  if (CURRENT_STEP > 1) {
    CURRENT_STEP--;
    renderStep();
  }
}

function renderStep() {
  for (var i = 1; i <= TOTAL_STEPS; i++) {
    var panel = document.getElementById('step-panel-' + i);
    var ind   = document.getElementById('step-indicator-' + i);
    var dot   = document.getElementById('step-dot-' + i);
    var lbl   = document.getElementById('step-label-' + i);

    panel.style.display = (i === CURRENT_STEP) ? 'block' : 'none';

    var style = i < CURRENT_STEP ? stepColors.done : (i === CURRENT_STEP ? stepColors[1] : stepColors.idle);
    ind.style.borderColor  = style.border;
    ind.style.background   = style.bg;
    dot.style.background   = style.dotBg;
    dot.style.color        = style.dotColor;
    lbl.style.color        = style.labelColor;

    // tampilkan checkmark di step selesai
    dot.textContent = i < CURRENT_STEP ? '✓' : i;
  }

  // Tombol prev
  var btnPrev = document.getElementById('tambah-btn-prev');
  btnPrev.style.display = CURRENT_STEP > 1 ? 'flex' : 'none';

  // Tombol next / submit
  var btnNext   = document.getElementById('tambah-btn-next');
  var btnSubmit = document.getElementById('tambah-btn-submit');
  if (CURRENT_STEP === TOTAL_STEPS) {
    btnNext.style.display   = 'none';
    btnSubmit.style.display = 'flex';
  } else {
    btnNext.style.display   = 'flex';
    btnSubmit.style.display = 'none';
  }
}

function showStepError(msg) {
  var el = document.getElementById('tambah-step-error');
  if (!el) return;
  el.querySelector('span:last-child').textContent = msg;
  el.style.display = 'flex';
  setTimeout(function () { el.style.display = 'none'; }, 3000);
}

function validateStep(step) {
  if (step === 1) {
    var nama     = document.querySelector('#tambah-form [name="nama"]').value.trim();
    var kategori = document.querySelector('#tambah-form [name="kategori"]').value;
    if (!nama)     { showStepError('Nama barang wajib diisi.'); return false; }
    if (!kategori) { showStepError('Kategori wajib dipilih.'); return false; }
  }
  if (step === 2) {
    var pj = document.querySelector('#tambah-form [name="pj"]').value.trim();
    if (!pj) { showStepError('Penanggung jawab wajib diisi.'); return false; }
  }
  return true;
}

/* ══ Kondisi radio style sync ═════════════════════════ */
var kondisiConfig = {
  'baik':            { color: '#16a34a', bg: '#dcfce7' },
  'dalam-perbaikan': { color: '#ca8a04', bg: '#fef9c3' },
  'rusak':           { color: '#dc2626', bg: '#fee2e2' },
};

function syncKondisiStyle(slug) {
  var rd  = document.getElementById('tambah-radio-' + slug);
  var lbl = document.getElementById('tambah-kondisi-label-' + slug);
  if (!rd || !lbl) return;
  if (rd.checked) {
    lbl.style.borderColor = kondisiConfig[slug].color;
    lbl.style.background  = kondisiConfig[slug].bg;
  } else {
    lbl.style.borderColor = '#e2e8f0';
    lbl.style.background  = '#fff';
  }
}

function syncAllKondisiStyles() {
  Object.keys(kondisiConfig).forEach(function(slug) { syncKondisiStyle(slug); });
}

/* ══ Rupiah formatter ═════════════════════════════════ */
function formatRupiahTambah(el) {
  var val = el.value.replace(/\D/g, '');
  el.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/* ══ Status Modal ══════════════════════════════════════ */
function openStatusModal(id, nama, kondisi) {
  document.getElementById('status-aset-id').value = id;
  document.getElementById('status-modal-nama').textContent = nama;

  ['baik','dalam-perbaikan','rusak'].forEach(function(slug) {
    var lbl = document.getElementById('label-kondisi-' + slug);
    var rd  = document.getElementById('radio-' + slug);
    if (lbl) { lbl.style.borderColor = '#e2e8f0'; lbl.style.background = '#fff'; }
    if (rd)  { rd.checked = false; }
  });

  var map  = { 'Baik': 'baik', 'Dalam Perbaikan': 'dalam-perbaikan', 'Rusak': 'rusak' };
  var slug = map[kondisi];
  if (slug) {
    var rd = document.getElementById('radio-' + slug);
    if (rd) rd.checked = true;
    highlightKondisi(slug);
  }
  openModal('status-modal');
}

function highlightKondisi(activeSlug) {
  var colors = {
    'baik':            { border: '#16a34a', bg: '#dcfce7' },
    'dalam-perbaikan': { border: '#ca8a04', bg: '#fef9c3' },
    'rusak':           { border: '#dc2626', bg: '#fee2e2' },
  };
  ['baik','dalam-perbaikan','rusak'].forEach(function(slug) {
    var lbl = document.getElementById('label-kondisi-' + slug);
    if (!lbl) return;
    if (slug === activeSlug) {
      lbl.style.borderColor = colors[slug].border;
      lbl.style.background  = colors[slug].bg;
    } else {
      lbl.style.borderColor = '#e2e8f0';
      lbl.style.background  = '#fff';
    }
  });
}

/* ══ Delete Modal ══════════════════════════════════════ */
function confirmDelete(id, nama) {
  document.getElementById('delete-aset-id').value = id;
  document.getElementById('delete-nama-display').textContent = nama;
  openModal('delete-modal');
}

/* ══ Detail Modal ══════════════════════════════════════ */
function showDetailModal(data) {
  var kColor = data.kondisi === 'Baik' ? '#16a34a' : (data.kondisi === 'Rusak' ? '#dc2626' : '#ca8a04');
  var kBg    = data.kondisi === 'Baik' ? '#dcfce7' : (data.kondisi === 'Rusak' ? '#fee2e2' : '#fef9c3');
  var kIcon  = data.kondisi === 'Baik' ? 'check_circle' : (data.kondisi === 'Rusak' ? 'cancel' : 'build');

  var fields = [
    ['Kode Aset',         data.kode],
    ['Kategori',          data.kategori],
    ['Kondisi',           data.kondisi],
    ['Nilai Aset',        'Rp ' + data.nilai],
    ['Kantor',            data.kantor],
    ['Ruangan',           data.ruangan],
    ['Penanggung Jawab',  data.penanggung_jawab],
    ['Serial Number',     data.serial_number],
    ['Merek',             data.merek],
    ['Model',             data.model],
    ['Tanggal Pengadaan', data.tanggal_pengadaan],
    ['Catatan',           data.catatan],
  ];

  var fieldsHtml = fields.map(function(f) {
    return '<div style="background:#f8fafc;border-radius:10px;padding:10px 12px;border:1px solid #f1f5f9;">' +
      '<p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:3px;">' + f[0] + '</p>' +
      '<p style="font-size:13px;font-weight:700;color:#0f172a;word-break:break-word;">' + (f[1] || '—') + '</p>' +
    '</div>';
  }).join('');

  var namaSafe = (data.nama || '').replace(/\\/g,'\\\\').replace(/'/g,"\\'");

  document.getElementById('detail-modal-content').innerHTML =
    '<div style="padding:18px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:flex-start;justify-content:space-between;gap:10px;">' +
      '<div style="display:flex;align-items:center;gap:10px;min-width:0;">' +
        '<div style="width:40px;height:40px;min-width:40px;background:' + kBg + ';border-radius:10px;display:flex;align-items:center;justify-content:center;">' +
          '<span class="material-symbols-outlined fill-icon" style="color:' + kColor + ';font-size:20px;">' + kIcon + '</span>' +
        '</div>' +
        '<div style="min-width:0;">' +
          '<h3 style="font-family:\'Sora\',sans-serif;font-weight:800;font-size:15px;color:#0f172a;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + data.nama + '</h3>' +
          '<span style="font-size:11px;font-weight:700;color:' + kColor + ';background:' + kBg + ';padding:3px 9px;border-radius:20px;">' + data.kondisi + '</span>' +
        '</div>' +
      '</div>' +
      '<button onclick="closeModal(\'detail-modal\')" style="border:none;background:#f1f5f9;border-radius:7px;width:28px;height:28px;cursor:pointer;font-size:16px;color:#64748b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">×</button>' +
    '</div>' +
    '<div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:8px;">' + fieldsHtml + '</div>' +
    '<div style="padding:0 16px 16px;display:flex;gap:7px;">' +
      '<button onclick="closeModal(\'detail-modal\');openStatusModal(' + data.id + ',\'' + namaSafe + '\',\'' + data.kondisi + '\')" class="btn-ghost" style="flex:1;justify-content:center;font-size:12px;">' +
        '<span class="material-symbols-outlined" style="font-size:14px;">edit</span> Ubah Kondisi' +
      '</button>' +
      '<button onclick="closeModal(\'detail-modal\');confirmDelete(' + data.id + ',\'' + namaSafe + '\')" style="flex:1;justify-content:center;font-size:12px;background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;border-radius:11px;padding:9px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:5px;">' +
        '<span class="material-symbols-outlined" style="font-size:14px;">delete</span> Hapus' +
      '</button>' +
      '<a href="' + URL_QR_LABEL + '" class="btn-or" style="flex:1;justify-content:center;font-size:12px;">' +
        '<span class="material-symbols-outlined" style="font-size:14px;">qr_code</span> QR Label' +
      '</a>' +
    '</div>';

  openModal('detail-modal');
}
</script>
@endpush
@endsection