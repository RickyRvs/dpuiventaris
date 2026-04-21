@extends('layouts.app')

@section('content')
<div style="padding:20px;display:flex;flex-direction:column;gap:16px;">

  {{-- ── Alert ── --}}
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

  {{-- ── Header ── --}}
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:3px;">
        <div style="width:36px;height:36px;background:linear-gradient(135deg,#7c3aed,#5b21b6);border-radius:10px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:19px;">description</span>
        </div>
        <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;">Berita Acara Inventaris</h2>
      </div>
      <p style="font-size:12.5px;color:#64748b;margin-left:46px;">
        Kelola dokumen serah terima aset — unduh template, TTD + materai, lalu upload kembali.
      </p>
    </div>
    <button type="button" onclick="openModal('ba-tambah-modal')"
      style="background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;font-weight:700;border-radius:12px;padding:9px 20px;font-size:13px;box-shadow:0 4px 14px rgba(124,58,237,.25);border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
      <span class="material-symbols-outlined" style="font-size:16px;">add_circle</span> Buat Berita Acara
    </button>
  </div>

  {{-- ── Statistik Cards ── --}}
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
    @php
      $statCards = [
        ['Total','receipt_long',$stats['total'],'#7c3aed','#f5f3ff','#ede9fe'],
        ['Draft','draft',$stats['draft'],'#64748b','#f8fafc','#f1f5f9'],
        ['Menunggu Upload','upload_file',$stats['menunggu_upload'],'#2563eb','#eff6ff','#dbeafe'],
        ['Selesai','task_alt',$stats['selesai'],'#16a34a','#f0fdf4','#dcfce7'],
      ];
    @endphp
    @foreach($statCards as [$label,$icon,$val,$color,$bg,$bg2])
    <div style="background:{{ $bg }};border:1px solid {{ $bg2 }};border-radius:14px;padding:14px 16px;display:flex;align-items:center;gap:12px;">
      <div style="width:40px;height:40px;background:{{ $color }};border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:19px;">{{ $icon }}</span>
      </div>
      <div>
        <div style="font-size:22px;font-weight:800;color:#0f172a;font-family:'Sora',sans-serif;">{{ $val }}</div>
        <div style="font-size:11px;color:#64748b;font-weight:600;">{{ $label }}</div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- ── Filter ── --}}
  <div style="background:#fff;border-radius:14px;padding:14px 16px;border:1px solid #e2e8f0;box-shadow:0 1px 4px rgba(0,0,0,.04);">
    <form method="GET" action="{{ route('berita-acara') }}" style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
      <div style="position:relative;flex:1;min-width:200px;">
        <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:15px;">search</span>
        <input type="text" name="q" placeholder="Cari nomor BA, nama aset, pihak..." value="{{ request('q') }}" class="field" style="padding-left:34px;font-size:12.5px;"/>
      </div>
      <select name="status" class="field" style="width:auto;padding:9px 12px;font-size:12.5px;" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="draft"               {{ request('status')==='draft'?'selected':'' }}>Draft</option>
        <option value="template_downloaded" {{ request('status')==='template_downloaded'?'selected':'' }}>Template Diunduh</option>
        <option value="menunggu_upload"     {{ request('status')==='menunggu_upload'?'selected':'' }}>Menunggu Upload</option>
        <option value="selesai"             {{ request('status')==='selesai'?'selected':'' }}>Selesai</option>
      </select>
      <button type="submit" class="btn-or" style="padding:9px 16px;font-size:12.5px;background:linear-gradient(135deg,#7c3aed,#5b21b6);box-shadow:none;">
        <span class="material-symbols-outlined" style="font-size:15px;">search</span> Cari
      </button>
      @if(request()->hasAny(['q','status']))
      <a href="{{ route('berita-acara') }}" style="padding:9px 12px;background:#fef2f2;color:#ef4444;border:1.5px solid #fecaca;border-radius:10px;font-size:12.5px;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:5px;">
        <span class="material-symbols-outlined" style="font-size:14px;">close</span> Reset
      </a>
      @endif
    </form>
  </div>

  {{-- ── Alur Kerja ── --}}
  <div style="background:linear-gradient(135deg,#f5f3ff,#ede9fe);border:1px solid #c4b5fd;border-radius:14px;padding:14px 16px;">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
      <span class="material-symbols-outlined fill-icon" style="color:#7c3aed;font-size:18px;">info</span>
      <span style="font-size:12.5px;font-weight:800;color:#5b21b6;">Alur Berita Acara Serah Terima Aset</span>
    </div>
    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
      @php
        $steps = [
          ['1','Buat Berita Acara','add_circle','#7c3aed'],
          ['›','','','#cbd5e1'],
          ['2','Unduh Template PDF','download','#ca8a04'],
          ['›','','','#cbd5e1'],
          ['3','TTD + Tempel Materai','draw','#2563eb'],
          ['›','','','#cbd5e1'],
          ['4','Upload Dokumen TTD','upload_file','#16a34a'],
          ['›','','','#cbd5e1'],
          ['5','Selesai ✓','task_alt','#16a34a'],
        ];
      @endphp
      @foreach($steps as [$num, $label, $icon, $color])
        @if($label)
        <div style="display:flex;align-items:center;gap:5px;background:#fff;border-radius:8px;padding:5px 10px;border:1px solid #ddd6fe;">
          <span class="material-symbols-outlined" style="font-size:14px;color:{{ $color }};">{{ $icon }}</span>
          <span style="font-size:11.5px;font-weight:700;color:#4c1d95;">{{ $num }}. {{ $label }}</span>
        </div>
        @else
        <span style="font-size:16px;color:{{ $color }};font-weight:bold;">{{ $num }}</span>
        @endif
      @endforeach
    </div>
  </div>

  {{-- ── Table Card ── --}}
  <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;">

    @if($list->count() === 0)
    <div style="padding:60px 40px;text-align:center;">
      <div style="width:64px;height:64px;background:#f5f3ff;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <span class="material-symbols-outlined" style="font-size:32px;color:#c4b5fd;">description</span>
      </div>
      <p style="font-size:14px;font-weight:700;color:#334155;margin-bottom:6px;">Belum ada berita acara</p>
      <p style="font-size:12.5px;color:#94a3b8;margin-bottom:18px;">Buat berita acara serah terima aset pertama Anda</p>
      <button type="button" onclick="openModal('ba-tambah-modal')"
        style="background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;font-weight:700;border-radius:12px;padding:9px 20px;font-size:13px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
        <span class="material-symbols-outlined" style="font-size:15px;">add_circle</span> Buat Berita Acara
      </button>
    </div>

    @else
    <div style="overflow-x:auto;">
      <table style="width:100%;min-width:900px;border-collapse:collapse;">
        <thead>
          <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;">Nomor BA</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Aset</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Pihak Pertama</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Pihak Kedua (PIC)</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Tgl Serah Terima</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Status</th>
            <th style="padding:11px 12px;text-align:right;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($list as $ba)
          @php
            $sColor = match($ba->status) {
              'draft'               => '#64748b',
              'template_downloaded' => '#ca8a04',
              'menunggu_upload'     => '#2563eb',
              'selesai'             => '#16a34a',
              default               => '#64748b',
            };
            $sBg = match($ba->status) {
              'draft'               => '#f1f5f9',
              'template_downloaded' => '#fef9c3',
              'menunggu_upload'     => '#dbeafe',
              'selesai'             => '#dcfce7',
              default               => '#f1f5f9',
            };
            $sIcon = match($ba->status) {
              'draft'               => 'draft',
              'template_downloaded' => 'download',
              'menunggu_upload'     => 'upload_file',
              'selesai'             => 'task_alt',
              default               => 'help',
            };
            $asetCount = $ba->asets->count();
          @endphp
          <tr style="border-bottom:1px solid #f1f5f9;transition:background .12s;"
              onmouseover="this.style.background='#fafafa'"
              onmouseout="this.style.background='transparent'">

            <td style="padding:12px 12px;">
              <span style="font-family:monospace;font-size:11px;font-weight:700;color:#5b21b6;background:#f5f3ff;padding:3px 8px;border-radius:5px;white-space:nowrap;">{{ $ba->nomor }}</span>
              <div style="font-size:10px;color:#94a3b8;margin-top:2px;">{{ $ba->created_at?->format('d/m/Y') }}</div>
            </td>

            <td style="padding:12px 12px;">
              <div style="font-size:12.5px;font-weight:700;color:#0f172a;">{{ $ba->aset_nama ?? '—' }}</div>
              @if($asetCount > 1)
              <div style="display:inline-flex;align-items:center;gap:3px;margin-top:3px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:20px;padding:2px 8px;">
                <span class="material-symbols-outlined" style="font-size:11px;color:#7c3aed;">inventory_2</span>
                <span style="font-size:10.5px;font-weight:700;color:#7c3aed;">{{ $asetCount }} aset</span>
              </div>
              @else
              <div style="font-size:11px;color:#94a3b8;">{{ $ba->aset_kode ?? '' }} · {{ $ba->aset_kategori ?? '' }}</div>
              @endif
            </td>

            <td style="padding:12px 12px;">
              <div style="font-size:12.5px;font-weight:600;color:#334155;">{{ $ba->pihak_pertama_nama }}</div>
              <div style="font-size:11px;color:#94a3b8;">{{ $ba->pihak_pertama_jabatan }}</div>
            </td>

            <td style="padding:12px 12px;">
              <div style="font-size:12.5px;font-weight:600;color:#334155;">{{ $ba->pihak_kedua_nama }}</div>
              <div style="font-size:11px;color:#94a3b8;">{{ $ba->pihak_kedua_jabatan }}</div>
            </td>

            <td style="padding:12px 12px;font-size:12px;color:#475569;white-space:nowrap;">
              {{ \Carbon\Carbon::parse($ba->tanggal_serah_terima)->format('d/m/Y') }}
            </td>

            <td style="padding:12px 12px;">
              <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:{{ $sColor }};background:{{ $sBg }};padding:4px 10px;border-radius:20px;white-space:nowrap;">
                <span class="material-symbols-outlined fill-icon" style="font-size:12px;">{{ $sIcon }}</span>
                {{ $ba->status_label }}
              </span>
            </td>

            <td style="padding:12px 12px;">
              <div style="display:flex;gap:4px;justify-content:flex-end;flex-wrap:nowrap;">
                <button type="button" title="Detail" onclick="showDetail({{ $ba->id }})"
                  style="width:30px;height:30px;background:#f5f3ff;border:1.5px solid #ddd6fe;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                  <span class="material-symbols-outlined" style="font-size:14px;color:#7c3aed;">visibility</span>
                </button>
                <a href="{{ route('berita-acara.download', $ba->id) }}" title="Unduh Template PDF"
                  style="width:30px;height:30px;background:#fef9c3;border:1.5px solid #fde047;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                  <span class="material-symbols-outlined" style="font-size:14px;color:#ca8a04;">download</span>
                </a>
                @if($ba->status !== 'selesai')
                <button type="button" title="Upload Dokumen TTD" onclick="openUploadModal({{ $ba->id }}, '{{ $ba->nomor }}')"
                  style="width:30px;height:30px;background:#dbeafe;border:1.5px solid #93c5fd;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                  <span class="material-symbols-outlined" style="font-size:14px;color:#2563eb;">upload_file</span>
                </button>
                @else
                <a href="{{ route('berita-acara.view-dokumen', $ba->id) }}" title="Unduh Dokumen TTD"
                  style="width:30px;height:30px;background:#dcfce7;border:1.5px solid #86efac;border-radius:8px;display:flex;align-items:center;justify-content:center;text-decoration:none;">
                  <span class="material-symbols-outlined" style="font-size:14px;color:#16a34a;">download_done</span>
                </a>
                @endif
                <button type="button" title="Hapus" onclick="confirmDeleteBA({{ $ba->id }}, '{{ $ba->nomor }}')"
                  style="width:30px;height:30px;background:#fef2f2;border:1.5px solid #fecaca;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
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
        Menampilkan <strong style="color:#334155;">{{ $list->firstItem() }}–{{ $list->lastItem() }}</strong>
        dari <strong style="color:#334155;">{{ $list->total() }}</strong> berita acara
      </span>
      @if($list->hasPages())
      <div style="display:flex;gap:4px;">
        @if($list->onFirstPage())
          <span style="width:30px;height:30px;border-radius:7px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;font-size:13px;display:flex;align-items:center;justify-content:center;">‹</span>
        @else
          <a href="{{ $list->previousPageUrl() }}" style="width:30px;height:30px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;display:flex;align-items:center;justify-content:center;text-decoration:none;">‹</a>
        @endif
        @foreach($list->getUrlRange(max(1,$list->currentPage()-2), min($list->lastPage(),$list->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" style="width:30px;height:30px;border-radius:7px;border:1.5px solid {{ $page==$list->currentPage()?'#7c3aed':'#e2e8f0' }};background:{{ $page==$list->currentPage()?'#f5f3ff':'#fff' }};color:{{ $page==$list->currentPage()?'#7c3aed':'#64748b' }};font-size:11.5px;font-weight:700;display:flex;align-items:center;justify-content:center;text-decoration:none;">{{ $page }}</a>
        @endforeach
        @if($list->hasMorePages())
          <a href="{{ $list->nextPageUrl() }}" style="width:30px;height:30px;border-radius:7px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:13px;display:flex;align-items:center;justify-content:center;text-decoration:none;">›</a>
        @endif
      </div>
      @endif
    </div>
    @endif

  </div>
</div>

@push('scripts')

{{-- ════════════════════════════════════════════════════════════
     MODAL: BUAT BERITA ACARA BARU (multi-aset)
     ════════════════════════════════════════════════════════════ --}}
<div class="modal" id="ba-tambah-modal">
  <div class="modal-box" style="background:#fff;border-radius:22px;width:660px;max-width:96vw;max-height:92vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 24px 80px rgba(0,0,0,.22);">

    <div style="padding:20px 24px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#5b21b6);border-radius:11px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:20px;">description</span>
        </div>
        <div>
          <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;margin-bottom:2px;">Buat Berita Acara Baru</h3>
          <p style="font-size:11.5px;color:#94a3b8;">Satu BA bisa mencakup banyak aset sekaligus</p>
        </div>
      </div>
      <button onclick="closeModal('ba-tambah-modal')"
        style="border:none;background:#f1f5f9;border-radius:9px;width:32px;height:32px;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;">×</button>
    </div>

    <form method="POST" action="{{ route('berita-acara.store') }}" id="ba-form"
      style="display:flex;flex-direction:column;flex:1;overflow:hidden;">
      @csrf
      {{-- Hidden inputs aset_ids[] akan di-inject JS saat submit --}}
      <div id="ba-hidden-aset-ids"></div>

      <div style="overflow-y:auto;flex:1;padding:20px 24px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

          {{-- ══ MULTI-ASET PICKER ════════════════════════════════ --}}
          <div style="grid-column:1/-1;">
            <label class="lbl">
              Pilih Aset <span style="color:#ef4444;">*</span>
              <span id="ba-aset-counter" style="display:none;margin-left:6px;background:#7c3aed;color:#fff;font-size:9px;font-weight:800;padding:2px 8px;border-radius:20px;vertical-align:middle;">0 dipilih</span>
            </label>

            {{-- Dropdown --}}
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">inventory_2</span>
              <select id="ba-aset-select" class="field" style="padding-left:34px;font-size:13px;" onchange="baAddAset(this)">
                <option value="">-- Pilih aset, bisa lebih dari satu --</option>
                @foreach($asetList as $aset)
                <option value="{{ $aset->id }}"
                  data-kode="{{ $aset->kode }}"
                  data-nama="{{ $aset->nama }}"
                  data-kondisi="{{ $aset->kondisi }}"
                  data-kategori="{{ $aset->kategori }}"
                  data-kantor="{{ $aset->kantor?->short_name ?? '-' }}"
                  data-pj="{{ $aset->penanggung_jawab ?? '' }}"
                  data-nilai="{{ number_format($aset->nilai ?? 0, 0, ',', '.') }}">
                  [{{ $aset->kode }}] {{ $aset->nama }} — {{ $aset->kantor?->short_name ?? '-' }}
                </option>
                @endforeach
              </select>
            </div>

            {{-- Empty state --}}
            <div id="ba-aset-list-empty"
              style="margin-top:10px;border:2px dashed #e2e8f0;border-radius:12px;padding:22px;text-align:center;">
              <span class="material-symbols-outlined" style="font-size:28px;color:#cbd5e1;display:block;margin-bottom:6px;">add_box</span>
              <p style="font-size:12px;color:#94a3b8;font-weight:600;margin-bottom:2px;">Belum ada aset dipilih</p>
              <p style="font-size:11px;color:#cbd5e1;">Pilih dari dropdown di atas, bisa lebih dari satu</p>
            </div>

            {{-- List aset terpilih --}}
            <div id="ba-aset-list" style="display:none;margin-top:10px;flex-direction:column;gap:6px;"></div>

            {{-- Ringkasan total --}}
            <div id="ba-aset-summary"
              style="display:none;margin-top:8px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:10px;padding:10px 14px;align-items:center;justify-content:space-between;">
              <div style="display:flex;align-items:center;gap:6px;">
                <span class="material-symbols-outlined fill-icon" style="color:#7c3aed;font-size:15px;">checklist</span>
                <span id="ba-summary-count" style="font-size:12px;font-weight:700;color:#5b21b6;"></span>
              </div>
              <span id="ba-summary-nilai" style="font-size:12px;font-weight:700;color:#5b21b6;"></span>
            </div>
          </div>

          {{-- ── Tanggal ── --}}
          <div style="grid-column:1/-1;">
            <label class="lbl">Tanggal Serah Terima <span style="color:#ef4444;">*</span></label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">calendar_today</span>
              <input type="date" name="tanggal_serah_terima" class="field"
                value="{{ old('tanggal_serah_terima', date('Y-m-d')) }}"
                required style="padding-left:34px;font-size:13px;"/>
            </div>
          </div>

          {{-- ── Pihak Pertama ── --}}
          <div style="grid-column:1/-1;">
            <div style="display:flex;align-items:center;gap:8px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:10px;padding:8px 12px;">
              <span class="material-symbols-outlined fill-icon" style="color:#7c3aed;font-size:16px;">business</span>
              <span style="font-size:12px;font-weight:700;color:#5b21b6;">Pihak Pertama — Pemberi / Pimpinan PT</span>
            </div>
          </div>

          <div>
            <label class="lbl">Nama Pimpinan / Direksi <span style="color:#ef4444;">*</span></label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">person</span>
              <input type="text" name="pihak_pertama_nama" placeholder="Nama lengkap pimpinan" class="field"
                value="{{ old('pihak_pertama_nama') }}" required style="padding-left:34px;font-size:13px;"/>
            </div>
          </div>

          <div>
            <label class="lbl">Jabatan <span style="color:#ef4444;">*</span></label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">badge</span>
              <input type="text" name="pihak_pertama_jabatan" placeholder="Direktur Utama" class="field"
                value="{{ old('pihak_pertama_jabatan', 'Direktur Utama') }}" required style="padding-left:34px;font-size:13px;"/>
            </div>
          </div>

          {{-- ── Pihak Kedua (satu PIC untuk semua aset) ── --}}
          <div style="grid-column:1/-1;">
            <div style="display:flex;align-items:center;gap:8px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:8px 12px;">
              <span class="material-symbols-outlined fill-icon" style="color:#2563eb;font-size:16px;">person_pin</span>
              <span style="font-size:12px;font-weight:700;color:#1e40af;">Pihak Kedua — Penerima / PIC (satu orang untuk semua aset)</span>
            </div>
          </div>

          <div>
            <label class="lbl">Nama PIC / Penerima <span style="color:#ef4444;">*</span></label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">person</span>
              <input type="text" name="pihak_kedua_nama" id="ba-pihak-kedua-nama"
                placeholder="Nama penerima aset" class="field"
                value="{{ old('pihak_kedua_nama') }}" required style="padding-left:34px;font-size:13px;"/>
            </div>
            <p id="ba-pj-hint" style="display:none;font-size:11px;color:#7c3aed;margin-top:5px;align-items:center;gap:4px;">
              <span class="material-symbols-outlined" style="font-size:13px;">auto_awesome</span>
              Diisi otomatis dari penanggung jawab aset pertama, bisa diubah manual.
            </p>
          </div>

          <div>
            <label class="lbl">Jabatan / Fungsi <span style="color:#ef4444;">*</span></label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">badge</span>
              <input type="text" name="pihak_kedua_jabatan" placeholder="Staff IT / Kepala Divisi..." class="field"
                value="{{ old('pihak_kedua_jabatan') }}" required style="padding-left:34px;font-size:13px;"/>
            </div>
          </div>

          {{-- ── Keterangan ── --}}
          <div style="grid-column:1/-1;">
            <label class="lbl">Keterangan Tambahan</label>
            <textarea name="keterangan" placeholder="Catatan serah terima, kondisi khusus, atau informasi lain..."
              class="field" style="height:80px;resize:vertical;font-size:13px;">{{ old('keterangan') }}</textarea>
          </div>

        </div>
      </div>

      {{-- Footer --}}
      <div style="padding:14px 24px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:8px;flex-shrink:0;background:#fafafa;border-radius:0 0 22px 22px;">
        <button type="button" onclick="closeModal('ba-tambah-modal')" class="btn-ghost">Batal</button>
        <button type="submit" onclick="return baInjectIds()"
          style="background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;font-weight:700;border-radius:12px;padding:10px 22px;font-size:13px;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;">
          <span class="material-symbols-outlined" style="font-size:16px;">save</span> Buat & Simpan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     MODAL: UPLOAD DOKUMEN TTD
     ════════════════════════════════════════════════════════════ --}}
<div class="modal" id="ba-upload-modal">
  <div class="modal-box" style="background:#fff;border-radius:20px;width:460px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <div>
        <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;">Upload Dokumen TTD</h3>
        <p style="font-size:12px;color:#64748b;margin-top:2px;" id="ba-upload-nomor"></p>
      </div>
      <button onclick="closeModal('ba-upload-modal')"
        style="border:none;background:#f1f5f9;border-radius:7px;width:28px;height:28px;cursor:pointer;font-size:17px;color:#64748b;display:flex;align-items:center;justify-content:center;">×</button>
    </div>
    <form method="POST" action="{{ route('berita-acara.upload') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="ba-upload-id"/>
      <div style="padding:18px 22px;display:flex;flex-direction:column;gap:14px;">
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px;display:flex;gap:8px;">
          <span class="material-symbols-outlined fill-icon" style="color:#2563eb;font-size:16px;flex-shrink:0;margin-top:1px;">info</span>
          <p style="font-size:12px;color:#1e40af;line-height:1.5;">
            Upload dokumen berita acara yang <strong>sudah ditandatangani</strong> oleh kedua pihak
            dan <strong>ditempeli materai Rp 10.000</strong>. Format: PDF, JPG, atau PNG. Maks 10 MB.
          </p>
        </div>
        <div>
          <label class="lbl">Pilih File Dokumen <span style="color:#ef4444;">*</span></label>
          <div id="ba-drop-zone"
            style="border:2px dashed #c4b5fd;border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all .15s;background:#fafafa;"
            onclick="document.getElementById('ba-file-input').click()"
            ondragover="event.preventDefault();this.style.borderColor='#7c3aed';this.style.background='#f5f3ff';"
            ondragleave="this.style.borderColor='#c4b5fd';this.style.background='#fafafa';"
            ondrop="handleDrop(event)">
            <span class="material-symbols-outlined" style="font-size:36px;color:#c4b5fd;display:block;margin-bottom:8px;">upload_file</span>
            <p style="font-size:13px;font-weight:700;color:#5b21b6;" id="ba-drop-label">Klik atau drag & drop file di sini</p>
            <p style="font-size:11px;color:#94a3b8;margin-top:3px;">PDF, JPG, PNG — Maks 10 MB</p>
          </div>
          <input type="file" name="dokumen" id="ba-file-input" accept=".pdf,.jpg,.jpeg,.png" required
            style="display:none;" onchange="onFileSelect(this)"/>
        </div>
      </div>
      <div style="padding:0 22px 18px;display:flex;gap:8px;">
        <button type="button" onclick="closeModal('ba-upload-modal')" class="btn-ghost" style="flex:1;justify-content:center;">Batal</button>
        <button type="submit"
          style="flex:1;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border:none;border-radius:11px;padding:10px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;">
          <span class="material-symbols-outlined" style="font-size:15px;">cloud_upload</span> Upload
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     MODAL: DELETE KONFIRMASI
     ════════════════════════════════════════════════════════════ --}}
<div class="modal" id="ba-delete-modal">
  <div class="modal-box" style="background:#fff;border-radius:20px;width:380px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:28px 26px;text-align:center;">
      <div style="width:60px;height:60px;background:#fef2f2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;border:2px solid #fecaca;">
        <span class="material-symbols-outlined fill-icon" style="color:#ef4444;font-size:28px;">delete_forever</span>
      </div>
      <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;margin-bottom:7px;">Hapus Berita Acara?</h3>
      <p style="font-size:12.5px;color:#64748b;margin-bottom:6px;">Dokumen berikut akan dihapus permanen:</p>
      <p style="font-size:13.5px;font-weight:700;color:#5b21b6;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:9px;padding:9px 14px;margin-bottom:18px;" id="ba-delete-nomor"></p>
      <p style="font-size:12px;color:#ef4444;margin-bottom:22px;">⚠️ File dokumen TTD juga akan ikut dihapus.</p>
      <form method="POST" action="{{ route('berita-acara.destroy') }}" style="display:flex;gap:8px;">
        @csrf
        <input type="hidden" name="id" id="ba-delete-id"/>
        <button type="button" onclick="closeModal('ba-delete-modal')" class="btn-ghost" style="flex:1;justify-content:center;">Batal</button>
        <button type="submit" style="flex:1;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:11px;padding:10px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;">
          <span class="material-symbols-outlined" style="font-size:15px;">delete_forever</span> Ya, Hapus
        </button>
      </form>
    </div>
  </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     MODAL: DETAIL BERITA ACARA
     ════════════════════════════════════════════════════════════ --}}
<div class="modal" id="ba-detail-modal">
  <div class="modal-box" style="background:#fff;border-radius:20px;width:620px;max-width:95vw;max-height:88vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div id="ba-detail-content">
      <div style="padding:40px;text-align:center;">
        <span class="material-symbols-outlined" style="font-size:36px;color:#c4b5fd;animation:spin 1s linear infinite;">progress_activity</span>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes spin { to { transform:rotate(360deg); } }
.lbl { display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px; }
@keyframes slideIn { from{opacity:0;transform:translateY(-5px)} to{opacity:1;transform:translateY(0)} }
.ba-aset-item { animation:slideIn .18s ease; }
</style>

<script>
var BA_DETAIL_URL   = "{{ route('berita-acara.detail', ['id' => '__ID__']) }}";
var BA_DOWNLOAD_URL = "{{ route('berita-acara.download', ['id' => '__ID__']) }}";
var BA_VIEW_DOK_URL = "{{ route('berita-acara.view-dokumen', ['id' => '__ID__']) }}";

/* ══════════════════════════════════════════
   STATE
   ══════════════════════════════════════════ */
var baSelectedAsets = {};   // key = aset_id string
var baFirstPjFilled = false;

/* ── Tambah aset ke daftar ── */
function baAddAset(sel) {
  var opt = sel.options[sel.selectedIndex];
  if (!opt.value) return;

  var id = opt.value;

  // Duplikat → flash item yang sudah ada
  if (baSelectedAsets[id]) {
    baFlashDuplicate(id);
    sel.value = '';
    return;
  }

  baSelectedAsets[id] = {
    id:       id,
    kode:     opt.dataset.kode     || '',
    nama:     opt.dataset.nama     || '',
    kondisi:  opt.dataset.kondisi  || '',
    kategori: opt.dataset.kategori || '',
    kantor:   opt.dataset.kantor   || '',
    pj:       opt.dataset.pj       || '',
    nilai:    opt.dataset.nilai    || '0',
  };

  // Auto-fill PIC dari PJ aset pertama yang ditambahkan
  if (!baFirstPjFilled && baSelectedAsets[id].pj) {
    var pjInput = document.getElementById('ba-pihak-kedua-nama');
    if (pjInput) {
      pjInput.value = baSelectedAsets[id].pj;
      document.getElementById('ba-pj-hint').style.display = 'flex';
      baFirstPjFilled = true;
    }
  }

  sel.value = '';
  baRenderList();
}

/* ── Hapus aset dari daftar ── */
function baRemoveAset(id) {
  delete baSelectedAsets[id];
  if (Object.keys(baSelectedAsets).length === 0) {
    baFirstPjFilled = false;
    document.getElementById('ba-pj-hint').style.display = 'none';
  }
  baRenderList();
}

/* ── Render ulang daftar aset ── */
function baRenderList() {
  var ids     = Object.keys(baSelectedAsets);
  var elEmpty = document.getElementById('ba-aset-list-empty');
  var elList  = document.getElementById('ba-aset-list');
  var elSum   = document.getElementById('ba-aset-summary');
  var elCtr   = document.getElementById('ba-aset-counter');

  if (ids.length === 0) {
    elEmpty.style.display = 'block';
    elList.style.display  = 'none';
    elSum.style.display   = 'none';
    elCtr.style.display   = 'none';
    return;
  }

  elEmpty.style.display = 'none';
  elList.style.display  = 'flex';
  elSum.style.display   = 'flex';
  elCtr.style.display   = 'inline-block';

  // Hitung total nilai
  var total = ids.reduce(function(acc, id) {
    return acc + (parseInt((baSelectedAsets[id].nilai || '0').replace(/\./g, '')) || 0);
  }, 0);

  elCtr.textContent = ids.length + ' dipilih';
  document.getElementById('ba-summary-count').textContent = ids.length + ' aset dipilih';
  document.getElementById('ba-summary-nilai').textContent = 'Total: Rp ' + total.toLocaleString('id-ID');

  function kc(kondisi) {
    if (kondisi === 'Baik')  return { c:'#16a34a', bg:'#dcfce7', icon:'check_circle' };
    if (kondisi === 'Rusak') return { c:'#dc2626', bg:'#fee2e2', icon:'cancel' };
    return { c:'#ca8a04', bg:'#fef9c3', icon:'build' };
  }

  elList.innerHTML = ids.map(function(id) {
    var a = baSelectedAsets[id];
    var k = kc(a.kondisi);
    return '<div class="ba-aset-item" data-id="' + id + '" ' +
      'style="display:flex;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:11px;padding:10px 12px;transition:border-color .15s,background .15s;"' +
      ' onmouseover="this.style.borderColor=\'#c4b5fd\';this.style.background=\'#fafafe\'"' +
      ' onmouseout="this.style.borderColor=\'#e2e8f0\';this.style.background=\'#f8fafc\'">' +
        '<div style="width:32px;height:32px;min-width:32px;background:' + k.bg + ';border-radius:8px;display:flex;align-items:center;justify-content:center;">' +
          '<span class="material-symbols-outlined fill-icon" style="font-size:15px;color:' + k.c + ';">' + k.icon + '</span>' +
        '</div>' +
        '<div style="flex:1;min-width:0;">' +
          '<div style="font-size:12.5px;font-weight:700;color:#0f172a;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + a.nama + '</div>' +
          '<div style="font-size:11px;color:#94a3b8;">' + a.kode + ' · ' + a.kategori + ' · ' + a.kantor + '</div>' +
        '</div>' +
        '<div style="text-align:right;flex-shrink:0;margin-right:4px;">' +
          '<div style="font-size:11.5px;font-weight:700;color:#334155;">Rp ' + a.nilai + '</div>' +
          '<span style="font-size:10px;font-weight:700;color:' + k.c + ';background:' + k.bg + ';padding:1px 7px;border-radius:20px;">' + a.kondisi + '</span>' +
        '</div>' +
        '<button type="button" onclick="baRemoveAset(\'' + id + '\')" title="Hapus" ' +
        'style="width:26px;height:26px;min-width:26px;background:#fef2f2;border:1.5px solid #fecaca;border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center;">' +
          '<span class="material-symbols-outlined" style="font-size:13px;color:#ef4444;">close</span>' +
        '</button>' +
    '</div>';
  }).join('');
}

/* ── Flash item duplikat ── */
function baFlashDuplicate(id) {
  var el = document.querySelector('.ba-aset-item[data-id="' + id + '"]');
  if (!el) return;
  el.style.borderColor = '#f97316';
  el.style.background  = '#fff7ed';
  setTimeout(function() {
    el.style.borderColor = '#e2e8f0';
    el.style.background  = '#f8fafc';
  }, 700);
}

/* ── Inject hidden inputs sebelum submit ── */
function baInjectIds() {
  var ids = Object.keys(baSelectedAsets);
  if (ids.length === 0) {
    alert('Pilih minimal 1 aset terlebih dahulu.');
    return false;
  }
  document.getElementById('ba-hidden-aset-ids').innerHTML =
    ids.map(function(id) {
      return '<input type="hidden" name="aset_ids[]" value="' + id + '"/>';
    }).join('');
  return true;
}

/* ══════════════════════════════════════════
   Upload Modal
   ══════════════════════════════════════════ */
function openUploadModal(id, nomor) {
  document.getElementById('ba-upload-id').value          = id;
  document.getElementById('ba-upload-nomor').textContent = nomor;
  document.getElementById('ba-drop-label').textContent   = 'Klik atau drag & drop file di sini';
  document.getElementById('ba-file-input').value         = '';
  openModal('ba-upload-modal');
}
function onFileSelect(input) {
  if (input.files[0]) document.getElementById('ba-drop-label').textContent = '📄 ' + input.files[0].name;
}
function handleDrop(e) {
  e.preventDefault();
  document.getElementById('ba-drop-zone').style.borderColor = '#c4b5fd';
  document.getElementById('ba-drop-zone').style.background  = '#fafafa';
  if (e.dataTransfer.files.length) {
    try { var dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]); document.getElementById('ba-file-input').files = dt.files; } catch(x) {}
    document.getElementById('ba-drop-label').textContent = '📄 ' + e.dataTransfer.files[0].name;
  }
}

/* ══════════════════════════════════════════
   Delete Modal
   ══════════════════════════════════════════ */
function confirmDeleteBA(id, nomor) {
  document.getElementById('ba-delete-id').value          = id;
  document.getElementById('ba-delete-nomor').textContent = nomor;
  openModal('ba-delete-modal');
}

/* ══════════════════════════════════════════
   Detail Modal
   ══════════════════════════════════════════ */
function showDetail(id) {
  document.getElementById('ba-detail-content').innerHTML =
    '<div style="padding:40px;text-align:center;"><span class="material-symbols-outlined" style="font-size:36px;color:#c4b5fd;animation:spin 1s linear infinite;">progress_activity</span></div>';
  openModal('ba-detail-modal');
  fetch(BA_DETAIL_URL.replace('__ID__', id))
    .then(function(r) { return r.json(); })
    .then(function(d) { renderDetail(d); })
    .catch(function() { document.getElementById('ba-detail-content').innerHTML = '<p style="padding:24px;color:#ef4444;">Gagal memuat data.</p>'; });
}

function renderDetail(d) {
  // Tabel aset
  var rows = (d.aset_list || []).map(function(a, i) {
    return '<tr style="border-bottom:1px solid #f1f5f9;">' +
      '<td style="padding:8px 10px;font-size:12px;font-weight:700;color:#334155;">' + (i+1) + '. ' + a.nama + '</td>' +
      '<td style="padding:8px 10px;font-size:11px;color:#64748b;">' + a.kode + '</td>' +
      '<td style="padding:8px 10px;font-size:11px;color:#64748b;">' + a.kondisi + '</td>' +
      '<td style="padding:8px 10px;font-size:11px;font-weight:700;color:#0f172a;text-align:right;">' + a.nilai + '</td>' +
    '</tr>';
  }).join('');

  var asetBlock =
    '<div style="grid-column:1/-1;">' +
      '<p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:6px;">Daftar Aset (' + d.aset_count + ' item)</p>' +
      '<div style="border-radius:10px;border:1px solid #e2e8f0;overflow:hidden;">' +
        '<table style="width:100%;border-collapse:collapse;">' +
          '<thead><tr style="background:#f1f5f9;">' +
            '<th style="padding:7px 10px;text-align:left;font-size:10px;font-weight:700;color:#64748b;">Nama Aset</th>' +
            '<th style="padding:7px 10px;text-align:left;font-size:10px;font-weight:700;color:#64748b;">Kode</th>' +
            '<th style="padding:7px 10px;text-align:left;font-size:10px;font-weight:700;color:#64748b;">Kondisi</th>' +
            '<th style="padding:7px 10px;text-align:right;font-size:10px;font-weight:700;color:#64748b;">Nilai</th>' +
          '</tr></thead>' +
          '<tbody>' + rows + '</tbody>' +
          '<tfoot><tr style="background:#f5f3ff;">' +
            '<td colspan="3" style="padding:8px 10px;font-size:11px;font-weight:800;color:#5b21b6;">Total Nilai</td>' +
            '<td style="padding:8px 10px;font-size:12px;font-weight:800;color:#5b21b6;text-align:right;">' + d.total_nilai + '</td>' +
          '</tr></tfoot>' +
        '</table>' +
      '</div>' +
    '</div>';

  var infoFields = [
    ['Nomor BA',         d.nomor],
    ['Status',           '<span style="font-size:11px;font-weight:700;color:'+d.status_color+';background:'+d.status_bg+';padding:3px 9px;border-radius:20px;">'+d.status_label+'</span>'],
    ['Kantor',           d.kantor],
    ['Tgl Serah Terima', d.tanggal_serah_terima],
    ['Pihak Pertama',    d.pihak_pertama_nama + ' — ' + d.pihak_pertama_jabatan],
    ['Pihak Kedua (PIC)',d.pihak_kedua_nama + ' — ' + d.pihak_kedua_jabatan],
    ['Keterangan',       d.keterangan],
    ['Dibuat Oleh',      d.dibuat_oleh],
    ['Tanggal Dibuat',   d.created_at],
    ['Upload TTD',       d.uploaded_at || '—'],
    ['File Dokumen',     d.dokumen_signed_nama || '—'],
  ];

  var infoHtml = infoFields.map(function(f) {
    return '<div style="background:#f8fafc;border-radius:10px;padding:10px 12px;border:1px solid #f1f5f9;">' +
      '<p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:3px;">' + f[0] + '</p>' +
      '<p style="font-size:12.5px;font-weight:700;color:#0f172a;word-break:break-word;">' + (f[1] || '—') + '</p>' +
    '</div>';
  }).join('');

  var btnDl = '<a href="' + BA_DOWNLOAD_URL.replace('__ID__', d.id) + '" style="flex:1;justify-content:center;font-size:12px;background:#fef9c3;color:#92400e;border:1.5px solid #fde047;border-radius:11px;padding:9px;font-weight:700;display:flex;align-items:center;gap:5px;text-decoration:none;"><span class="material-symbols-outlined" style="font-size:14px;">download</span> Unduh Template</a>';
  var btnDok = d.has_dokumen
    ? '<a href="' + BA_VIEW_DOK_URL.replace('__ID__', d.id) + '" style="flex:1;justify-content:center;font-size:12px;background:#dcfce7;color:#166534;border:1.5px solid #86efac;border-radius:11px;padding:9px;font-weight:700;display:flex;align-items:center;gap:5px;text-decoration:none;"><span class="material-symbols-outlined" style="font-size:14px;">download_done</span> Lihat Dok TTD</a>'
    : '<button onclick="closeModal(\'ba-detail-modal\');openUploadModal('+d.id+',\''+d.nomor+'\')" style="flex:1;justify-content:center;font-size:12px;background:#dbeafe;color:#1e40af;border:1.5px solid #93c5fd;border-radius:11px;padding:9px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:5px;"><span class="material-symbols-outlined" style="font-size:14px;">upload_file</span> Upload TTD</button>';

  document.getElementById('ba-detail-content').innerHTML =
    '<div style="padding:18px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:10px;">' +
      '<div style="display:flex;align-items:center;gap:10px;">' +
        '<div style="width:40px;height:40px;background:#f5f3ff;border-radius:10px;display:flex;align-items:center;justify-content:center;">' +
          '<span class="material-symbols-outlined fill-icon" style="color:#7c3aed;font-size:20px;">description</span>' +
        '</div>' +
        '<div>' +
          '<h3 style="font-family:\'Sora\',sans-serif;font-weight:800;font-size:15px;color:#0f172a;margin-bottom:4px;">' + d.nomor + '</h3>' +
          '<span style="font-size:11px;font-weight:700;color:'+d.status_color+';background:'+d.status_bg+';padding:3px 9px;border-radius:20px;">' + d.status_label + '</span>' +
        '</div>' +
      '</div>' +
      '<button onclick="closeModal(\'ba-detail-modal\')" style="border:none;background:#f1f5f9;border-radius:7px;width:28px;height:28px;cursor:pointer;font-size:16px;color:#64748b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">×</button>' +
    '</div>' +
    '<div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:8px;">' + asetBlock + infoHtml + '</div>' +
    '<div style="padding:0 16px 16px;display:flex;gap:7px;">' + btnDl + btnDok + '</div>';
}

window.addEventListener('DOMContentLoaded', function () {
  if ({{ $errors->any() ? 'true' : 'false' }}) openModal('ba-tambah-modal');
});
</script>
@endpush
@endsection