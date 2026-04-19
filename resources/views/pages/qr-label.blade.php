@extends('layouts.app')

@section('content')
<div style="padding:24px;display:flex;flex-direction:column;gap:20px;">

  {{-- ── Header ── --}}
  <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="width:42px;height:42px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(249,115,22,.3);">
        <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:22px;">qr_code_2</span>
      </div>
      <div>
        <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;line-height:1.2;">QR Code & Label Aset</h2>
        <p style="font-size:12.5px;color:#94a3b8;margin-top:1px;">Generate dan cetak label QR untuk identifikasi aset</p>
      </div>
    </div>
    <div style="display:flex;gap:8px;">
      <button onclick="printAllVisible()" class="btn-ghost" style="font-size:12.5px;padding:9px 16px;">
        <span class="material-symbols-outlined" style="font-size:16px;">print</span> Cetak Semua
      </button>
    </div>
  </div>

  {{-- ── Main Grid ── --}}
  <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">

    {{-- ── Daftar Aset ── --}}
    <div style="background:#fff;border-radius:18px;border:1px solid #e8eaf0;box-shadow:0 2px 12px rgba(0,0,0,.05);overflow:hidden;">

      {{-- Search bar --}}
      <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px;background:#fafafa;">
        <div style="position:relative;flex:1;">
          <span class="material-symbols-outlined" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:15px;">search</span>
          <input type="text" id="qr-search" placeholder="Cari aset untuk generate QR..." class="field"
            style="padding-left:36px;font-size:13px;"
            oninput="filterQrTable(this.value)"/>
        </div>
        <span id="qr-count" style="font-size:12px;color:#94a3b8;white-space:nowrap;font-weight:600;">{{ count($asetList) }} aset</span>
      </div>

      {{-- Table --}}
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:500px;" id="qr-table">
          <thead>
            <tr style="background:#f8fafc;border-bottom:1.5px solid #e8eaf0;">
              <th style="padding:10px 20px;text-align:left;font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.09em;white-space:nowrap;">Kode</th>
              <th style="padding:10px 14px;text-align:left;font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.09em;">Nama Aset</th>
              <th style="padding:10px 14px;text-align:left;font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.09em;">Kantor</th>
              <th style="padding:10px 18px;text-align:right;font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.09em;">Aksi</th>
            </tr>
          </thead>
          <tbody id="qr-tbody">
            @foreach($asetList as $a)
            <tr class="qr-row"
              data-nama="{{ strtolower($a['nama']) }}"
              data-kode="{{ strtolower($a['kode']) }}"
              data-kantor="{{ strtolower($a['kantor']) }}"
              style="border-bottom:1px solid #f5f5f7;transition:background .1s;"
              onmouseover="this.style.background='#fffbf7'"
              onmouseout="this.style.background='transparent'">

              {{-- Kode --}}
              <td style="padding:13px 20px;">
                <span style="font-family:monospace;font-size:10.5px;font-weight:800;color:#64748b;background:#f1f5f9;padding:3px 8px;border-radius:5px;white-space:nowrap;">{{ $a['kode'] }}</span>
              </td>

              {{-- Nama + Kategori --}}
              <td style="padding:13px 14px;min-width:180px;">
                <div style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">{{ $a['nama'] }}</div>
                <div style="font-size:11px;color:#94a3b8;margin-top:1px;">{{ $a['kategori'] }}</div>
              </td>

              {{-- Kantor --}}
              <td style="padding:13px 14px;">
                <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:#475569;font-weight:600;">
                  <span class="material-symbols-outlined" style="font-size:13px;color:#cbd5e1;">location_on</span>
                  {{ $a['kantor'] }}
                </div>
              </td>

              {{-- Aksi --}}
              <td style="padding:13px 18px;text-align:right;">
                <button
                  onclick="selectQrAset('{{ $a['kode'] }}','{{ addslashes($a['nama']) }}','{{ $a['kantor'] }}','{{ $a['ruangan'] }}','{{ $a['kategori'] }}')"
                  style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1.5px solid #fed7aa;color:#ea580c;padding:6px 14px;border-radius:9px;cursor:pointer;font-size:11.5px;font-weight:700;display:inline-flex;align-items:center;gap:5px;transition:all .15s;"
                  onmouseover="this.style.background='linear-gradient(135deg,#f97316,#ea580c)';this.style.color='#fff';this.style.borderColor='transparent';this.style.boxShadow='0 4px 12px rgba(249,115,22,.3)'"
                  onmouseout="this.style.background='linear-gradient(135deg,#fff7ed,#ffedd5)';this.style.color='#ea580c';this.style.borderColor='#fed7aa';this.style.boxShadow='none'">
                  <span class="material-symbols-outlined" style="font-size:13px;">qr_code</span> Generate
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Empty state --}}
      <div id="qr-empty" style="display:none;padding:50px 20px;text-align:center;">
        <div style="width:56px;height:56px;background:#f1f5f9;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
          <span class="material-symbols-outlined" style="font-size:28px;color:#cbd5e1;">search_off</span>
        </div>
        <p style="font-size:14px;font-weight:700;color:#334155;margin-bottom:4px;">Tidak ada hasil</p>
        <p style="font-size:12.5px;color:#94a3b8;">Coba kata kunci lain</p>
      </div>
    </div>

    {{-- ── Panel Kanan ── --}}
    <div style="display:flex;flex-direction:column;gap:14px;position:sticky;top:20px;">

      {{-- QR Preview Card --}}
      <div style="background:#fff;border-radius:18px;border:1px solid #e8eaf0;box-shadow:0 2px 12px rgba(0,0,0,.05);overflow:hidden;">

        {{-- Header card --}}
        <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px;">
          <div style="width:28px;height:28px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:7px;display:flex;align-items:center;justify-content:center;">
            <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:14px;">qr_code_2</span>
          </div>
          <span style="font-family:'Sora',sans-serif;font-size:13px;font-weight:800;color:#0f172a;">Preview Label</span>
        </div>

        <div style="padding:20px;" id="qr-preview-body">

          {{-- Placeholder state --}}
          <div id="qr-placeholder" style="text-align:center;padding:10px 0 20px;">
            <div style="width:140px;height:140px;border:2px dashed #e2e8f0;border-radius:14px;margin:0 auto 14px;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,#f8fafc,#f1f5f9);">
              <span class="material-symbols-outlined" style="font-size:52px;color:#cbd5e1;">qr_code_2</span>
            </div>
            <p style="font-size:12.5px;color:#94a3b8;font-weight:600;">Pilih aset untuk melihat preview</p>
            <p style="font-size:11px;color:#cbd5e1;margin-top:3px;">Klik tombol Generate di tabel</p>
          </div>

          {{-- Active QR state (hidden until selected) --}}
          <div id="qr-active" style="display:none;">
            {{-- Label preview card --}}
            <div id="qr-label-card" style="background:linear-gradient(135deg,#0f172a,#1e293b);border-radius:14px;padding:18px;margin-bottom:16px;position:relative;overflow:hidden;">
              {{-- dekorasi --}}
              <div style="position:absolute;top:-30px;right:-30px;width:100px;height:100px;background:rgba(249,115,22,.15);border-radius:50%;pointer-events:none;"></div>
              <div style="position:absolute;bottom:-20px;left:-20px;width:70px;height:70px;background:rgba(249,115,22,.08);border-radius:50%;pointer-events:none;"></div>

              <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;position:relative;z-index:1;">
                <div style="flex:1;min-width:0;">
                  {{-- Logo kecil --}}
                  <div style="display:flex;align-items:center;gap:6px;margin-bottom:12px;">
                    <div style="width:20px;height:20px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:4px;display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-weight:800;font-size:7px;color:#fff;flex-shrink:0;">DPU</div>
                    <span style="font-size:9px;font-weight:700;color:rgba(249,115,22,.8);text-transform:uppercase;letter-spacing:.1em;">PT. Dian Pilar Utama</span>
                  </div>
                  <div id="qr-label-nama" style="font-family:'Sora',sans-serif;font-size:13px;font-weight:800;color:#fff;line-height:1.3;margin-bottom:6px;word-break:break-word;"></div>
                  <div style="display:flex;flex-direction:column;gap:3px;">
                    <div style="display:flex;align-items:center;gap:5px;">
                      <span class="material-symbols-outlined" style="font-size:11px;color:rgba(249,115,22,.7);">tag</span>
                      <span id="qr-label-kode" style="font-family:monospace;font-size:10px;font-weight:700;color:rgba(249,115,22,.9);"></span>
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <span class="material-symbols-outlined" style="font-size:11px;color:#64748b;">location_on</span>
                      <span id="qr-label-kantor" style="font-size:10px;color:#64748b;font-weight:600;"></span>
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;">
                      <span class="material-symbols-outlined" style="font-size:11px;color:#64748b;">door_open</span>
                      <span id="qr-label-ruangan" style="font-size:10px;color:#64748b;font-weight:600;"></span>
                    </div>
                  </div>
                </div>
                {{-- QR Code visual --}}
                <div style="flex-shrink:0;">
                  <div id="qr-visual" style="width:80px;height:80px;background:#fff;border-radius:8px;padding:6px;"></div>
                </div>
              </div>

              {{-- Barcode strip --}}
              <div style="margin-top:12px;position:relative;z-index:1;">
                <div id="qr-label-cat" style="display:inline-flex;align-items:center;gap:4px;background:rgba(249,115,22,.2);border:1px solid rgba(249,115,22,.3);border-radius:5px;padding:3px 8px;">
                  <span class="material-symbols-outlined fill-icon" style="font-size:10px;color:#f97316;">inventory_2</span>
                  <span style="font-size:9.5px;font-weight:700;color:#fb923c;"></span>
                </div>
              </div>
            </div>

            {{-- Info pills --}}
            <div style="display:flex;gap:6px;margin-bottom:16px;flex-wrap:wrap;">
              <div style="flex:1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:9px;padding:9px 12px;text-align:center;">
                <div style="font-size:9px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Kantor</div>
                <div id="qr-info-kantor" style="font-size:12px;font-weight:700;color:#0f172a;"></div>
              </div>
              <div style="flex:1;background:#f8fafc;border:1px solid #f1f5f9;border-radius:9px;padding:9px 12px;text-align:center;">
                <div style="font-size:9px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Ruangan</div>
                <div id="qr-info-ruangan" style="font-size:12px;font-weight:700;color:#0f172a;"></div>
              </div>
            </div>

            {{-- Action buttons --}}
            <div style="display:flex;gap:8px;">
              <button onclick="doCetak()" class="btn-or" style="flex:1;font-size:12.5px;padding:10px;justify-content:center;">
                <span class="material-symbols-outlined" style="font-size:15px;">print</span> Cetak
              </button>
              <button onclick="doUnduh()" class="btn-ghost" style="flex:1;font-size:12.5px;padding:10px;justify-content:center;">
                <span class="material-symbols-outlined" style="font-size:15px;">download</span> Unduh
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- ── Cetak Massal ── --}}
      <div style="background:#fff;border-radius:18px;border:1px solid #e8eaf0;box-shadow:0 2px 12px rgba(0,0,0,.05);overflow:hidden;">
        <div style="padding:14px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:8px;">
          <div style="width:28px;height:28px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);border-radius:7px;display:flex;align-items:center;justify-content:center;">
            <span class="material-symbols-outlined fill-icon" style="color:#fff;font-size:14px;">print</span>
          </div>
          <span style="font-family:'Sora',sans-serif;font-size:13px;font-weight:800;color:#0f172a;">Cetak Massal</span>
        </div>
        <div style="padding:14px 16px;display:flex;flex-direction:column;gap:8px;">
          @foreach([
            ['Semua aset Pekanbaru',    '42 aset', 'location_city',  '#f0fdf4', '#16a34a'],
            ['Semua aset Jakarta',      '28 aset', 'apartment',      '#eff6ff', '#2563eb'],
            ['Aset Elektronik & IT',    '35 aset', 'devices',        '#f5f3ff', '#7c3aed'],
            ['Aset Rusak / Perbaikan',  '7 aset',  'build_circle',   '#fef2f2', '#ef4444'],
          ] as [$label, $count, $icon, $bg, $color])
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:#f8fafc;border-radius:10px;border:1px solid #f1f5f9;gap:10px;transition:all .15s;"
            onmouseover="this.style.background='#fff';this.style.borderColor='#e2e8f0'"
            onmouseout="this.style.background='#f8fafc';this.style.borderColor='#f1f5f9'">
            <div style="display:flex;align-items:center;gap:10px;min-width:0;">
              <div style="width:30px;height:30px;background:{{ $bg }};border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-symbols-outlined fill-icon" style="color:{{ $color }};font-size:15px;">{{ $icon }}</span>
              </div>
              <div style="min-width:0;">
                <p style="font-size:12.5px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $label }}</p>
                <p style="font-size:10.5px;color:#94a3b8;margin-top:1px;">{{ $count }}</p>
              </div>
            </div>
            <button onclick="showToast('Mencetak {{ $count }}...')"
              style="flex-shrink:0;background:#fff;border:1.5px solid #e2e8f0;color:#475569;padding:5px 12px;border-radius:8px;cursor:pointer;font-size:11.5px;font-weight:700;display:flex;align-items:center;gap:4px;transition:all .15s;white-space:nowrap;"
              onmouseover="this.style.background='#f97316';this.style.color='#fff';this.style.borderColor='#f97316'"
              onmouseout="this.style.background='#fff';this.style.color='#475569';this.style.borderColor='#e2e8f0'">
              <span class="material-symbols-outlined" style="font-size:13px;">print</span> Cetak
            </button>
          </div>
          @endforeach
        </div>
      </div>

      {{-- ── Tips ── --}}
      <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);border:1px solid #fed7aa;border-radius:14px;padding:14px 16px;display:flex;gap:10px;">
        <span class="material-symbols-outlined fill-icon" style="color:#f97316;font-size:18px;flex-shrink:0;margin-top:1px;">lightbulb</span>
        <div>
          <p style="font-size:12px;font-weight:700;color:#c2410c;margin-bottom:3px;">Tips Cetak</p>
          <p style="font-size:11.5px;color:#9a3412;line-height:1.6;">Gunakan kertas label A4 untuk hasil terbaik. QR code dapat dipindai dengan semua aplikasi kamera smartphone.</p>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ── Print stylesheet (tersembunyi saat normal) ── --}}
<div id="print-area" style="display:none;"></div>

@push('scripts')
<script>
/* ── State ── */
var currentAset = null;

/* ── Filter table ── */
function filterQrTable(q) {
  var rows  = document.querySelectorAll('.qr-row');
  var empty = document.getElementById('qr-empty');
  var count = 0;
  var lq    = q.toLowerCase();

  rows.forEach(function(r) {
    var match = !lq ||
      r.dataset.nama.includes(lq) ||
      r.dataset.kode.includes(lq) ||
      r.dataset.kantor.includes(lq);
    r.style.display = match ? '' : 'none';
    if (match) count++;
  });

  document.getElementById('qr-count').textContent = count + ' aset';
  empty.style.display = count === 0 ? 'block' : 'none';
}

/* ── Generate QR visual (7×7 fake QR with fixed corners) ── */
function buildQrGrid(seed) {
  /* deterministik agar tidak berubah setiap render */
  var rng = (function(s) {
    return function() { s = (s * 1664525 + 1013904223) & 0xffffffff; return (s >>> 0) / 0xffffffff; };
  })(seed.split('').reduce(function(a, c) { return a + c.charCodeAt(0); }, 0));

  var SIZE = 9;
  var cells = [];
  for (var i = 0; i < SIZE * SIZE; i++) cells.push(rng() > 0.42);

  /* paksa 3 corner finder pattern 3×3 */
  function setCorner(row, col) {
    for (var r = row; r < row + 3; r++)
      for (var c = col; c < col + 3; c++) {
        var on = (r === row || r === row+2 || c === col || c === col+2);
        cells[r * SIZE + c] = on;
      }
  }
  setCorner(0, 0);
  setCorner(0, SIZE - 3);
  setCorner(SIZE - 3, 0);

  var html = '<div style="display:grid;grid-template-columns:repeat(' + SIZE + ',1fr);gap:1.5px;width:100%;height:100%;">';
  cells.forEach(function(on) {
    html += '<div style="background:' + (on ? '#0f172a' : 'transparent') + ';border-radius:1px;aspect-ratio:1;"></div>';
  });
  html += '</div>';
  return html;
}

/* ── Select aset → tampilkan preview ── */
function selectQrAset(kode, nama, kantor, ruangan, kategori) {
  currentAset = { kode: kode, nama: nama, kantor: kantor, ruangan: ruangan, kategori: kategori };

  /* sembunyikan placeholder, tampilkan active */
  document.getElementById('qr-placeholder').style.display = 'none';
  document.getElementById('qr-active').style.display      = 'block';

  /* isi konten */
  document.getElementById('qr-label-nama').textContent    = nama;
  document.getElementById('qr-label-kode').textContent    = kode;
  document.getElementById('qr-label-kantor').textContent  = kantor;
  document.getElementById('qr-label-ruangan').textContent = ruangan !== '-' ? ruangan : 'Tidak diketahui';
  document.getElementById('qr-info-kantor').textContent   = kantor;
  document.getElementById('qr-info-ruangan').textContent  = ruangan !== '-' ? ruangan : '—';

  /* kategori badge */
  var catEl = document.getElementById('qr-label-cat');
  catEl.querySelector('span:last-child').textContent = kategori;

  /* QR visual */
  document.getElementById('qr-visual').innerHTML = buildQrGrid(kode);

  /* highlight baris yang dipilih */
  document.querySelectorAll('.qr-row').forEach(function(r) {
    r.style.background = r.dataset.kode === kode.toLowerCase() ? '#fff7ed' : '';
  });

  /* scroll ke panel kanan (mobile) */
  document.getElementById('qr-active').scrollIntoView({ behavior: 'smooth', block: 'nearest' });

  showToast('QR ' + kode + ' berhasil digenerate!');
}

/* ── Cetak satu aset ── */
function doCetak() {
  if (!currentAset) return;
  showToast('Mencetak label ' + currentAset.kode + '...', 'info');
  setTimeout(function() { window.print(); }, 300);
}

/* ── Unduh (simulasi) ── */
function doUnduh() {
  if (!currentAset) return;
  showToast('QR label ' + currentAset.kode + ' diunduh!');
}

/* ── Cetak semua aset yang tampil ── */
function printAllVisible() {
  showToast('Mencetak semua aset yang ditampilkan...');
  setTimeout(function() { window.print(); }, 500);
}
</script>
@endpush
@endsection