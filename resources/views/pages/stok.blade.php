@extends('layouts.app')

@section('content')
<div style="padding:20px;display:flex;flex-direction:column;gap:16px;">

  {{-- Flash --}}
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
          <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:19px;">shelves</span>
        </div>
        <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;">Manajemen Stok</h2>
      </div>
      <p style="font-size:12.5px;color:#64748b;margin-left:46px;">Pantau ketersediaan bahan &amp; suku cadang</p>
    </div>
    <button onclick="openModal('stok-modal')" class="btn-or">
      <span class="material-symbols-outlined" style="font-size:16px;">add_circle</span> Tambah Item
    </button>
  </div>

  {{-- Stats --}}
  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
    @foreach([
      ['Total Item',   $statStok['total'],  '#f97316', '#fff7ed', 'shelves',        'Semua item terdaftar'],
      ['Stok Aman',    $statStok['aman'],   '#16a34a', '#f0fdf4', 'inventory',      'Stok di atas minimum'],
      ['Stok Kritis',  $statStok['kritis'], '#dc2626', '#fef2f2', 'warning',        'Di bawah batas minimum'],
      ['Stok Habis',   $statStok['habis'],  '#64748b', '#f8fafc', 'remove_circle',  'Stok kosong / 0'],
    ] as [$lbl, $num, $color, $bg, $icon, $sub])
    <div style="background:#fff;border-radius:14px;border:1px solid #f1f5f9;padding:16px 18px;box-shadow:0 1px 4px rgba(0,0,0,.04);border-top:3px solid {{ $color }};">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
        <span style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;">{{ $lbl }}</span>
        <div style="width:30px;height:30px;background:{{ $bg }};border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:{{ $color }};font-size:16px;">{{ $icon }}</span>
        </div>
      </div>
      <p style="font-family:'Sora',sans-serif;font-size:28px;font-weight:800;color:#0f172a;line-height:1;margin-bottom:4px;">{{ $num }}</p>
      <p style="font-size:11px;color:#94a3b8;">{{ $sub }}</p>
    </div>
    @endforeach
  </div>

  {{-- Alert Kritis --}}
  @php $stokKritis = $stokList->filter(fn($s) => $s->stok > 0 && $s->stok < $s->min_stok); @endphp
  @if($stokKritis->count() > 0)
  <div style="background:linear-gradient(135deg,#fef2f2,#fee2e2);border:1px solid #fecaca;border-radius:14px;padding:14px 18px;display:flex;align-items:flex-start;gap:12px;">
    <div style="width:36px;height:36px;background:#fef2f2;border:1.5px solid #fecaca;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <span class="material-symbols-outlined fill-icon" style="color:#ef4444;font-size:18px;">warning</span>
    </div>
    <div>
      <p style="font-size:13px;font-weight:700;color:#991b1b;margin-bottom:3px;">{{ $stokKritis->count() }} item di bawah stok minimum!</p>
      <p style="font-size:12px;color:#dc2626;line-height:1.6;">
        {{ $stokKritis->map(fn($s) => $s->nama . ' (' . $s->stok . '/' . $s->min_stok . ' ' . $s->satuan . ')')->implode(' · ') }}
      </p>
    </div>
  </div>
  @endif

  {{-- Table --}}
  <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;">

    @if($stokList->count() === 0)
    <div style="padding:60px 40px;text-align:center;">
      <div style="width:64px;height:64px;background:#f1f5f9;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <span class="material-symbols-outlined" style="font-size:32px;color:#cbd5e1;">shelves</span>
      </div>
      <p style="font-size:14px;font-weight:700;color:#334155;margin-bottom:6px;">Belum ada item stok</p>
      <p style="font-size:12.5px;color:#94a3b8;margin-bottom:18px;">Tambahkan item stok pertama untuk mulai memantau</p>
      <button onclick="openModal('stok-modal')" class="btn-or">
        <span class="material-symbols-outlined" style="font-size:15px;">add_circle</span> Tambah Item
      </button>
    </div>
    @else
    <div style="width:100%;overflow-x:auto;">
      <table style="width:100%;min-width:700px;border-collapse:collapse;">
        <thead>
          <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
            <th style="padding:11px 16px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;">Kode</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Nama Item</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Kategori</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Kantor</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Stok</th>
            <th style="padding:11px 12px;text-align:left;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Status</th>
            <th style="padding:11px 16px;text-align:right;font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($stokList as $s)
          @php
            $pct    = $s->min_stok > 0 ? min(100, round($s->stok / $s->min_stok * 100)) : 100;
            $habis  = $s->stok == 0;
            $kritis = !$habis && $s->stok < $s->min_stok;
            $aman   = !$habis && !$kritis;
            $barColor = $habis ? '#94a3b8' : ($kritis ? '#dc2626' : '#16a34a');
          @endphp
          <tr style="border-bottom:1px solid #f1f5f9;transition:background .12s;"
              onmouseover="this.style.background='#fafafa'"
              onmouseout="this.style.background='transparent'">

            {{-- Kode --}}
            <td style="padding:13px 16px;">
              <span style="font-family:monospace;font-size:11px;font-weight:700;color:#64748b;background:#f1f5f9;padding:3px 8px;border-radius:5px;white-space:nowrap;">{{ $s->kode }}</span>
            </td>

            {{-- Nama --}}
            <td style="padding:13px 12px;">
              <div style="display:flex;align-items:center;gap:9px;">
                <div style="width:32px;height:32px;min-width:32px;background:{{ $habis ? '#f1f5f9' : ($kritis ? '#fee2e2' : '#dcfce7') }};border-radius:9px;display:flex;align-items:center;justify-content:center;">
                  <span class="material-symbols-outlined fill-icon" style="font-size:15px;color:{{ $habis ? '#94a3b8' : ($kritis ? '#dc2626' : '#16a34a') }};">{{ $habis ? 'remove_circle' : ($kritis ? 'warning' : 'inventory') }}</span>
                </div>
                <div>
                  <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $s->nama }}</div>
                  <div style="font-size:11px;color:#94a3b8;">{{ $s->satuan }}</div>
                </div>
              </div>
            </td>

            {{-- Kategori --}}
            <td style="padding:13px 12px;">
              <span style="font-size:11px;font-weight:600;color:#475569;background:#f1f5f9;padding:3px 9px;border-radius:20px;white-space:nowrap;">{{ $s->kategori }}</span>
            </td>

            {{-- Kantor --}}
            <td style="padding:13px 12px;">
              <div style="display:flex;align-items:center;gap:4px;font-size:12px;color:#475569;font-weight:600;">
                <span class="material-symbols-outlined" style="font-size:13px;color:#94a3b8;">location_on</span>
                {{ $s->kantor->short_name ?? '—' }}
              </div>
            </td>

            {{-- Stok bar --}}
            <td style="padding:13px 12px;">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:68px;height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;flex-shrink:0;">
                  <div style="height:100%;width:{{ $pct }}%;background:{{ $barColor }};border-radius:99px;transition:width .4s;"></div>
                </div>
                <div style="font-size:13px;">
                  <span style="font-weight:700;color:{{ $barColor }};">{{ $s->stok }}</span>
                  <span style="color:#94a3b8;font-size:11px;"> / {{ $s->min_stok }} {{ $s->satuan }}</span>
                </div>
              </div>
            </td>

            {{-- Status badge --}}
            <td style="padding:13px 12px;">
              @if($habis)
                <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#64748b;background:#f1f5f9;border:1px solid #e2e8f0;padding:4px 10px;border-radius:20px;white-space:nowrap;">
                  <span style="width:5px;height:5px;border-radius:50%;background:#94a3b8;"></span> Habis
                </span>
              @elseif($kritis)
                <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#dc2626;background:#fee2e2;border:1px solid #fecaca;padding:4px 10px;border-radius:20px;white-space:nowrap;">
                  <span style="width:5px;height:5px;border-radius:50%;background:#dc2626;"></span> Kritis
                </span>
              @else
                <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#16a34a;background:#dcfce7;border:1px solid #bbf7d0;padding:4px 10px;border-radius:20px;white-space:nowrap;">
                  <span style="width:5px;height:5px;border-radius:50%;background:#16a34a;"></span> Aman
                </span>
              @endif
            </td>

            {{-- Aksi --}}
            <td style="padding:13px 16px;">
              <div style="display:flex;gap:5px;justify-content:flex-end;">
                <button type="button"
                  onclick="openUpdateStok({{ $s->id }},'{{ addslashes($s->nama) }}',{{ $s->stok }},'{{ $s->satuan }}',{{ $s->min_stok }})"
                  title="Update Stok"
                  style="height:30px;padding:0 10px;background:#fff7ed;border:1.5px solid #fed7aa;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#f97316;white-space:nowrap;transition:all .15s;"
                  onmouseover="this.style.background='#ffedd5';this.style.borderColor='#f97316'"
                  onmouseout="this.style.background='#fff7ed';this.style.borderColor='#fed7aa'">
                  <span class="material-symbols-outlined" style="font-size:13px;">edit</span> Update
                </button>
                <button type="button"
                  onclick="confirmDeleteStok({{ $s->id }},'{{ addslashes($s->nama) }}')"
                  title="Hapus"
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

    {{-- Footer info --}}
    <div style="padding:10px 16px;border-top:1px solid #f1f5f9;">
      <span style="font-size:12px;color:#94a3b8;">
        Total <strong style="color:#334155;">{{ $stokList->count() }}</strong> item stok
      </span>
    </div>
    @endif
  </div>
</div>

@push('scripts')

{{-- ═══════════════════════════════════════════════
     MODAL: TAMBAH ITEM STOK
     ═══════════════════════════════════════════════ --}}
<div class="modal" id="stok-modal">
  <div class="modal-box" style="background:#fff;border-radius:22px;width:500px;max-width:95vw;box-shadow:0 24px 80px rgba(0,0,0,.22);overflow:hidden;">

    {{-- Header --}}
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:10px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:19px;">add_box</span>
        </div>
        <div>
          <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;margin-bottom:2px;">Tambah Item Stok</h3>
          <p style="font-size:11.5px;color:#94a3b8;">Daftarkan bahan atau suku cadang baru</p>
        </div>
      </div>
      <button onclick="closeModal('stok-modal')"
        style="border:none;background:#f1f5f9;border-radius:9px;width:32px;height:32px;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
        onmouseover="this.style.background='#fee2e2';this.style.color='#dc2626'"
        onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b'">×</button>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('stok.store') }}">
      @csrf
      <div style="padding:20px 24px;display:flex;flex-direction:column;gap:16px;max-height:62vh;overflow-y:auto;">

        {{-- Nama --}}
        <div>
          <label class="lbl">Nama Item <span style="color:#ef4444;">*</span></label>
          <input type="text" name="nama" class="field" placeholder="Contoh: Toner Printer HP LaserJet" required/>
        </div>

        {{-- Kategori + Satuan --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="lbl">Kategori <span style="color:#ef4444;">*</span></label>
            <select name="kategori" class="field" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach(['Konsumabel','Suku Cadang','Mekanikal','Bahan Bakar','K3','Perawatan','Perawatan Kendaraan','Alat Tulis','Lainnya'] as $k)
              <option>{{ $k }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="lbl">Satuan <span style="color:#ef4444;">*</span></label>
            <input type="text" name="satuan" class="field" placeholder="unit, rim, liter, pcs..." required/>
          </div>
        </div>

        {{-- Stok Awal + Min Stok --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label class="lbl">Stok Awal <span style="color:#ef4444;">*</span></label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">shelves</span>
              <input type="number" name="stok" class="field" placeholder="0" min="0" required style="padding-left:34px;"/>
            </div>
          </div>
          <div>
            <label class="lbl">Minimum Stok <span style="color:#ef4444;">*</span></label>
            <div style="position:relative;">
              <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">warning</span>
              <input type="number" name="min_stok" class="field" placeholder="5" min="0" required style="padding-left:34px;"/>
            </div>
          </div>
        </div>

        {{-- Info box --}}
        <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1px solid #fed7aa;border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:8px;">
          <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:16px;flex-shrink:0;">info</span>
          <p style="font-size:11.5px;color:#9a3412;line-height:1.5;">Sistem akan memberi peringatan otomatis jika stok turun di bawah batas minimum.</p>
        </div>

        {{-- Kantor --}}
        <div>
          <label class="lbl">Kantor <span style="color:#ef4444;">*</span></label>
          <div style="position:relative;">
            <span class="material-symbols-outlined" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:15px;color:#94a3b8;pointer-events:none;">corporate_fare</span>
            <select name="kantor_id" class="field" required style="padding-left:34px;">
              <option value="">-- Pilih Kantor --</option>
              @foreach($kantorList as $k)
              <option value="{{ $k->id }}" {{ !$isAdmin && session('kantor_db_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ $k->short_name }})</option>
              @endforeach
            </select>
          </div>
        </div>

      </div>

      {{-- Footer --}}
      <div style="padding:14px 24px;border-top:1px solid #f1f5f9;display:flex;gap:10px;background:#fafafa;">
        <button type="button" onclick="closeModal('stok-modal')" class="btn-ghost" style="flex:1;justify-content:center;">Batal</button>
        <button type="submit" class="btn-or" style="flex:1;justify-content:center;">
          <span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan Item
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     MODAL: UPDATE STOK
     ═══════════════════════════════════════════════ --}}
<div class="modal" id="update-stok-modal">
  <div class="modal-box" style="background:#fff;border-radius:22px;width:400px;max-width:95vw;box-shadow:0 24px 80px rgba(0,0,0,.22);overflow:hidden;">

    {{-- Header --}}
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#2563eb,#1d4ed8);border-radius:10px;display:flex;align-items:center;justify-content:center;">
          <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:19px;">edit</span>
        </div>
        <div>
          <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:15px;color:#0f172a;margin-bottom:2px;">Update Stok</h3>
          <p style="font-size:11px;color:#94a3b8;" id="update-stok-nama"></p>
        </div>
      </div>
      <button onclick="closeModal('update-stok-modal')"
        style="border:none;background:#f1f5f9;border-radius:9px;width:32px;height:32px;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0;"
        onmouseover="this.style.background='#fee2e2';this.style.color='#dc2626'"
        onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b'">×</button>
    </div>

    <form method="POST" action="{{ route('stok.update') }}">
      @csrf
      <input type="hidden" name="id" id="update-stok-id"/>
      <div style="padding:24px;">

        {{-- Stok sekarang --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
          <div>
            <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">Stok Saat Ini</p>
            <p style="font-size:22px;font-weight:800;color:#0f172a;font-family:'Sora',sans-serif;" id="update-stok-current">0</p>
          </div>
          <div style="text-align:right;">
            <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;">Minimum</p>
            <p style="font-size:22px;font-weight:800;color:#f97316;font-family:'Sora',sans-serif;" id="update-stok-min">0</p>
          </div>
        </div>

        {{-- Input jumlah --}}
        <div>
          <label class="lbl" style="margin-bottom:10px;">Jumlah Stok Baru <span style="color:#ef4444;">*</span></label>
          <div style="display:flex;align-items:center;gap:10px;">
            <button type="button" onclick="changeStok(-1)"
              style="width:40px;height:40px;border-radius:10px;border:1.5px solid #e2e8f0;background:#f8fafc;font-size:20px;cursor:pointer;color:#475569;display:flex;align-items:center;justify-content:center;font-weight:700;transition:all .15s;flex-shrink:0;"
              onmouseover="this.style.background='#fef2f2';this.style.borderColor='#fca5a5';this.style.color='#dc2626'"
              onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0';this.style.color='#475569'">−</button>
            <div style="flex:1;position:relative;">
              <input type="number" name="jumlah" id="update-jumlah" class="field" min="0" required
                style="text-align:center;font-size:20px;font-weight:800;font-family:'Sora',sans-serif;padding:12px;"/>
            </div>
            <button type="button" onclick="changeStok(1)"
              style="width:40px;height:40px;border-radius:10px;border:1.5px solid #e2e8f0;background:#f8fafc;font-size:20px;cursor:pointer;color:#475569;display:flex;align-items:center;justify-content:center;font-weight:700;transition:all .15s;flex-shrink:0;"
              onmouseover="this.style.background='#f0fdf4';this.style.borderColor='#86efac';this.style.color='#16a34a'"
              onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0';this.style.color='#475569'">+</button>
          </div>
          <p style="font-size:11px;color:#94a3b8;text-align:center;margin-top:6px;" id="update-satuan-label"></p>
        </div>
      </div>

      {{-- Footer --}}
      <div style="padding:0 24px 20px;display:flex;gap:10px;">
        <button type="button" onclick="closeModal('update-stok-modal')" class="btn-ghost" style="flex:1;justify-content:center;">Batal</button>
        <button type="submit" class="btn-or" style="flex:1;justify-content:center;">
          <span class="material-symbols-outlined" style="font-size:16px;">save</span> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══════════════════════════════════════════════
     MODAL: KONFIRMASI HAPUS
     ═══════════════════════════════════════════════ --}}
<div class="modal" id="delete-stok-modal">
  <div class="modal-box" style="background:#fff;border-radius:22px;width:400px;max-width:95vw;box-shadow:0 24px 80px rgba(0,0,0,.22);">
    <div style="padding:30px 26px;text-align:center;">
      <div style="width:60px;height:60px;background:#fef2f2;border-radius:14px;border:2px solid #fecaca;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <span class="material-symbols-outlined fill-icon" style="color:#ef4444;font-size:28px;">delete_forever</span>
      </div>
      <h3 style="font-family:'Sora',sans-serif;font-weight:800;font-size:16px;color:#0f172a;margin-bottom:8px;">Hapus Item Stok?</h3>
      <p style="font-size:12.5px;color:#64748b;margin-bottom:6px;">Item berikut akan dihapus permanen:</p>
      <p style="font-size:13.5px;font-weight:700;color:#0f172a;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;padding:9px 14px;margin-bottom:18px;" id="delete-stok-nama-display"></p>
      <p style="font-size:12px;color:#ef4444;margin-bottom:24px;">⚠️ Tindakan ini tidak dapat dibatalkan.</p>
      <form method="POST" action="{{ route('stok.delete') }}" style="display:flex;gap:8px;">
        @csrf
        <input type="hidden" name="id" id="delete-stok-id"/>
        <button type="button" onclick="closeModal('delete-stok-modal')" class="btn-ghost" style="flex:1;justify-content:center;">Batal</button>
        <button type="submit"
          style="flex:1;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:11px;padding:10px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
          <span class="material-symbols-outlined" style="font-size:15px;">delete_forever</span> Ya, Hapus
        </button>
      </form>
    </div>
  </div>
</div>

<style>
.lbl {
  display: block;
  font-size: 10px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .08em;
  margin-bottom: 6px;
}
</style>

<script>
function openUpdateStok(id, nama, stok, satuan, minStok) {
  document.getElementById('update-stok-id').value       = id;
  document.getElementById('update-stok-nama').textContent    = nama;
  document.getElementById('update-jumlah').value        = stok;
  document.getElementById('update-stok-current').textContent = stok + ' ' + satuan;
  document.getElementById('update-stok-min').textContent     = minStok + ' ' + satuan;
  document.getElementById('update-satuan-label').textContent = 'Satuan: ' + satuan;
  openModal('update-stok-modal');
}

function changeStok(delta) {
  var inp = document.getElementById('update-jumlah');
  inp.value = Math.max(0, parseInt(inp.value || 0) + delta);
}

function confirmDeleteStok(id, nama) {
  document.getElementById('delete-stok-id').value               = id;
  document.getElementById('delete-stok-nama-display').textContent = nama;
  openModal('delete-stok-modal');
}
</script>
@endpush
@endsection