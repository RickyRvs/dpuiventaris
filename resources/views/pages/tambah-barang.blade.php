@extends('layouts.app')

@section('content')
<div style="padding:24px;max-width:860px;">

  {{-- Flash --}}
  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:12px 16px;margin-bottom:16px;">
    <ul style="font-size:13px;color:#991b1b;margin:0;padding-left:16px;">
      @foreach($errors->all() as $err)
      <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <!-- Header -->
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;">
    <a href="{{ route('inventaris') }}" style="width:34px;height:34px;border-radius:10px;background:#f8fafc;border:1px solid #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;text-decoration:none;" onmouseover="this.style.background='#fff7ed'" onmouseout="this.style.background='#f8fafc'">
      <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
    </a>
    <div>
      <h2 style="font-family:'Sora',sans-serif;font-weight:800;font-size:20px;color:#0f172a;margin-bottom:2px;">Tambah Aset Baru</h2>
      <p style="font-size:12px;color:#94a3b8;">Isi formulir di bawah untuk mendaftarkan aset baru ke inventaris</p>
    </div>
  </div>

  <form method="POST" action="{{ route('tambah-barang.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="card" style="padding:28px;display:flex;flex-direction:column;gap:24px;">

      <!-- Informasi Dasar -->
      <section>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
          <span class="material-symbols-outlined" style="color:#f97316;">info</span>
          <span style="font-size:11px;font-weight:700;color:#0f172a;text-transform:uppercase;letter-spacing:.08em;">Informasi Dasar</span>
          <div style="flex:1;height:1px;background:#f1f5f9;"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
          <div style="grid-column:1/-1;">
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Nama Barang <span style="color:#ef4444;">*</span></label>
            <input type="text" name="nama" placeholder="Contoh: Workstation Dell Precision 3660" class="field" value="{{ old('nama') }}" required/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kategori <span style="color:#ef4444;">*</span></label>
            <select name="kategori" class="field" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach(['Elektronik & IT','Furnitur Kantor','Peralatan Survey','Kendaraan','Alat Berat','Infrastruktur','Mekanikal & Elektrikal','Peralatan Konstruksi','Konsumabel'] as $kat)
              <option value="{{ $kat }}" {{ old('kategori')===$kat?'selected':'' }}>{{ $kat }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Nomor Seri / SKU</label>
            <input type="text" name="sn" placeholder="SN-XXXXXX" class="field" value="{{ old('sn') }}"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Merek / Pabrikan</label>
            <input type="text" name="merek" placeholder="Contoh: Dell, Daikin, Toyota" class="field" value="{{ old('merek') }}"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Model / Tipe</label>
            <input type="text" name="model" placeholder="Contoh: Precision 3660 Tower" class="field" value="{{ old('model') }}"/>
          </div>
        </div>
      </section>

      <!-- Lokasi & PJ -->
      <section>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
          <span class="material-symbols-outlined" style="color:#f97316;">location_on</span>
          <span style="font-size:11px;font-weight:700;color:#0f172a;text-transform:uppercase;letter-spacing:.08em;">Lokasi & Penanggung Jawab</span>
          <div style="flex:1;height:1px;background:#f1f5f9;"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kantor <span style="color:#ef4444;">*</span></label>
            <select name="kantor_id" class="field" {{ !$isAdmin ? 'disabled' : '' }} required>
              <option value="">-- Pilih Kantor --</option>
              @foreach($kantorList as $kantor)
              @php $selected = old('kantor_id') == $kantor->id || (!$isAdmin && session('kantor_db_id') == $kantor->id); @endphp
              <option value="{{ $kantor->id }}" {{ $selected?'selected':'' }}>{{ $kantor->nama }}</option>
              @endforeach
            </select>
            @if(!$isAdmin)
            <input type="hidden" name="kantor_id" value="{{ session('kantor_db_id') }}"/>
            @endif
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Ruangan / Lokasi</label>
            <input type="text" name="ruangan" placeholder="Lt. 2 - Studio Desain" class="field" value="{{ old('ruangan') }}"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Penanggung Jawab <span style="color:#ef4444;">*</span></label>
            <input type="text" name="pj" placeholder="Nama penanggung jawab" class="field" value="{{ old('pj') }}" required/>
          </div>
        </div>
      </section>

      <!-- Nilai & Kondisi -->
      <section>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
          <span class="material-symbols-outlined" style="color:#f97316;">payments</span>
          <span style="font-size:11px;font-weight:700;color:#0f172a;text-transform:uppercase;letter-spacing:.08em;">Data Pengadaan & Kondisi</span>
          <div style="flex:1;height:1px;background:#f1f5f9;"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Nilai Perolehan (Rp)</label>
            <input type="text" name="nilai" id="nilai-input" placeholder="0" class="field" value="{{ old('nilai') }}" oninput="formatRupiah(this)"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Tanggal Pengadaan</label>
            <input type="date" name="tanggal" class="field" value="{{ old('tanggal') }}"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kondisi Awal <span style="color:#ef4444;">*</span></label>
            <select name="kondisi" class="field" required>
              @foreach(['Baik','Dalam Perbaikan','Rusak'] as $k)
              <option value="{{ $k }}" {{ old('kondisi')===$k?'selected':'' }}>{{ $k }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Garansi (Bulan)</label>
            <input type="number" name="garansi" placeholder="12" class="field" min="0" value="{{ old('garansi') }}"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Tanggal Garansi Habis</label>
            <input type="date" name="garansi_habis" class="field" value="{{ old('garansi_habis') }}"/>
          </div>
          <div>
            <label style="display:block;font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Kode Aset</label>
            <input type="text" value="(Auto-generate)" class="field" readonly style="background:#f1f5f9;color:#94a3b8;font-style:italic;font-size:12px;"/>
          </div>
        </div>
      </section>

      <!-- Catatan -->
      <section>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
          <span class="material-symbols-outlined" style="color:#f97316;">notes</span>
          <span style="font-size:11px;font-weight:700;color:#0f172a;text-transform:uppercase;letter-spacing:.08em;">Catatan Tambahan</span>
          <div style="flex:1;height:1px;background:#f1f5f9;"></div>
        </div>
        <textarea name="catatan" placeholder="Tambahkan catatan kondisi, spesifikasi khusus, atau informasi lainnya..." class="field" style="height:90px;resize:vertical;">{{ old('catatan') }}</textarea>
      </section>

      <!-- Action Buttons -->
      <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:8px;border-top:1px solid #f1f5f9;">
        <a href="{{ route('inventaris') }}" class="btn-ghost">Batal</a>
        <button type="submit" class="btn-or">
          <span class="material-symbols-outlined" style="font-size:17px;">save</span> Simpan Aset
        </button>
      </div>
    </div>
  </form>
</div>

@push('scripts')
<script>
function formatRupiah(el) {
  let val = el.value.replace(/\D/g,'');
  el.value = val.replace(/\B(?=(\d{3})+(?!\d))/g,'.');
}
</script>
@endpush
@endsection