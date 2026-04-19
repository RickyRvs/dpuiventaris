@extends('layouts.app')

@section('content')
<div style="padding:24px;max-width:900px;display:flex;flex-direction:column;gap:16px;">
  <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:4px;">Catatan Audit</h2>
      <p style="font-size:13px;color:#64748b;">Rekam jejak aktivitas seluruh pengguna sistem</p>
    </div>
    <div style="display:flex;gap:8px;">
      <input type="text" placeholder="Cari aktivitas..." class="field" style="width:200px;font-size:12px;" id="audit-search" oninput="filterAudit(this.value)"/>
      <button onclick="showToast('Mengunduh log audit...')" class="btn-ghost" style="font-size:12px;padding:8px 14px;">
        <span class="material-symbols-outlined" style="font-size:15px;">download</span> Ekspor Log
      </button>
    </div>
  </div>

  <div class="card" style="overflow:hidden;" id="audit-list">
    {{-- ✅ FIX BUG 6: pakai $log->user, $log->aksi, $log->waktu, $log->modul
         (Eloquent object, bukan array) --}}
    @forelse($auditLogs as $log)
    <div class="audit-row" data-text="{{ strtolower($log->aksi . ' ' . $log->user_name . ' ' . $log->modul) }}"
         style="display:flex;align-items:flex-start;gap:14px;padding:14px 18px;border-bottom:1px solid #f8fafc;transition:background .12s;cursor:default;"
         onmouseover="this.style.background='#fff7ed'" onmouseout="this.style.background='transparent'">
      <div style="width:36px;height:36px;border-radius:10px;background:{{ $log->bg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
        <span class="material-symbols-outlined" style="color:{{ $log->ic }};font-size:17px;">{{ $log->icon }}</span>
      </div>
      <div style="flex:1;">
        {{-- user_name langsung dari kolom --}}
        <p style="font-size:13px;color:#0f172a;">
          <span style="font-weight:700;">{{ $log->user_name }}</span>
          {{ $log->aksi }}
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-top:2px;">{{ $log->waktu }}</p>
      </div>
      <span style="font-size:10px;font-weight:700;color:#94a3b8;background:#f8fafc;padding:3px 8px;border-radius:6px;flex-shrink:0;border:1px solid #f1f5f9;">
        {{ $log->modul }}
      </span>
    </div>
    @empty
    <div style="padding:60px;text-align:center;color:#94a3b8;">
      <span class="material-symbols-outlined" style="font-size:48px;display:block;margin-bottom:12px;color:#cbd5e1;">history</span>
      <p style="font-size:14px;font-weight:600;">Belum ada catatan aktivitas</p>
    </div>
    @endforelse
  </div>

  <!-- Pagination -->
  <div style="display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:12px;color:#94a3b8;">
      Menampilkan {{ $auditLogs->firstItem() }}–{{ $auditLogs->lastItem() }} dari {{ $auditLogs->total() }} entri
    </span>
    <div style="display:flex;gap:6px;">
      @if($auditLogs->hasPages())
        @if($auditLogs->onFirstPage())
          <button disabled style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#cbd5e1;font-size:14px;cursor:not-allowed;">‹</button>
        @else
          <a href="{{ $auditLogs->previousPageUrl() }}" style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:14px;display:flex;align-items:center;justify-content:center;text-decoration:none;">‹</a>
        @endif

        @foreach($auditLogs->getUrlRange(max(1, $auditLogs->currentPage()-2), min($auditLogs->lastPage(), $auditLogs->currentPage()+2)) as $page => $url)
        <a href="{{ $url }}" style="width:32px;height:32px;border-radius:8px;border:1px solid {{ $page==$auditLogs->currentPage()?'#f97316':'#e2e8f0' }};background:{{ $page==$auditLogs->currentPage()?'#fff7ed':'#fff' }};color:{{ $page==$auditLogs->currentPage()?'#f97316':'#64748b' }};font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;text-decoration:none;">{{ $page }}</a>
        @endforeach

        @if($auditLogs->hasMorePages())
          <a href="{{ $auditLogs->nextPageUrl() }}" style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#64748b;font-size:14px;display:flex;align-items:center;justify-content:center;text-decoration:none;">›</a>
        @endif
      @endif
    </div>
  </div>
</div>

@push('scripts')
<script>
function filterAudit(val) {
  const q = val.toLowerCase();
  document.querySelectorAll('.audit-row').forEach(row => {
    row.style.display = row.dataset.text.includes(q) ? '' : 'none';
  });
}
</script>
@endpush
@endsection