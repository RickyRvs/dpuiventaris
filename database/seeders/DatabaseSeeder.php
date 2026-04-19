<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Kantor;
use App\Models\User;
use App\Models\Aset;
use App\Models\Stok;
use App\Models\Mutasi;
use App\Models\Jadwal;
use App\Models\AuditLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kantors ──────────────────────────────────────────────
        $kantors = [
            ['kode' => 'pku', 'nama' => 'Kantor Pusat Pekanbaru',       'short_name' => 'Pekanbaru'],
            ['kode' => 'jkt', 'nama' => 'Kantor Proyek Tebet Jakarta',  'short_name' => 'Tebet Jakarta'],
            ['kode' => 'sby', 'nama' => 'Kantor Proyek Surabaya',       'short_name' => 'Surabaya'],
            ['kode' => 'bks', 'nama' => 'Kantor Proyek Bekasi',         'short_name' => 'Bekasi'],
        ];
        foreach ($kantors as $k) Kantor::create($k);

        $pku = Kantor::where('kode', 'pku')->first();
        $jkt = Kantor::where('kode', 'jkt')->first();
        $sby = Kantor::where('kode', 'sby')->first();
        $bks = Kantor::where('kode', 'bks')->first();

        // ── Users ─────────────────────────────────────────────────
        $admin = User::create([
            'nama'      => 'Ahmad Santoso',
            'email'     => 'admin@dianpilar.co.id',
            'password'  => Hash::make('admin123'),
            'peran'     => 'admin',
            'kantor_id' => null,
            'initials'  => 'AS',
            'color1'    => '#f97316',
            'color2'    => '#c2410c',
        ]);

        $operators = [
            ['nama'=>'Rina Amelia',    'email'=>'rina@dianpilar.co.id',   'kantor_id'=>$pku->id, 'initials'=>'RA','color1'=>'#2563eb','color2'=>'#1d4ed8'],
            ['nama'=>'Arif Budiman',   'email'=>'arif@dianpilar.co.id',   'kantor_id'=>$jkt->id, 'initials'=>'AB','color1'=>'#16a34a','color2'=>'#15803d'],
            ['nama'=>'Dewi Lestari',   'email'=>'dewi@dianpilar.co.id',   'kantor_id'=>$sby->id, 'initials'=>'DL','color1'=>'#7c3aed','color2'=>'#6d28d9'],
            ['nama'=>'Hendra Susanto', 'email'=>'hendra@dianpilar.co.id', 'kantor_id'=>$bks->id, 'initials'=>'HS','color1'=>'#dc2626','color2'=>'#b91c1c'],
            ['nama'=>'Sari Dewi',      'email'=>'sari@dianpilar.co.id',   'kantor_id'=>$pku->id, 'initials'=>'SD','color1'=>'#0891b2','color2'=>'#0e7490'],
        ];

        // Also create a generic operator account for demo
        User::create([
            'nama'      => 'Operator Demo',
            'email'     => 'operator@dianpilar.co.id',
            'password'  => Hash::make('operator123'),
            'peran'     => 'operator',
            'kantor_id' => $pku->id,
            'initials'  => 'OP',
            'color1'    => '#64748b',
            'color2'    => '#475569',
        ]);

        foreach ($operators as $op) {
            User::create(array_merge($op, [
                'password' => Hash::make('operator123'),
                'peran'    => 'operator',
            ]));
        }

        // ── Aset Pekanbaru ────────────────────────────────────────
        $asetPku = [
            ['kode'=>'DPU-2201','nama'=>'MacBook Pro 14" M3 Pro','kategori'=>'Elektronik & IT','ruangan'=>'Lt. 3 - Dept. IT','kondisi'=>'Baik','nilai'=>42500000,'tanggal_pengadaan'=>'2024-03-12','serial_number'=>'C50LL928X-PRO','penanggung_jawab'=>'Andri Wijaya','catatan'=>'Unit baru, kondisi prima.'],
            ['kode'=>'DPU-2202','nama'=>'AC Split 2PK Daikin','kategori'=>'Mekanikal & Elektrikal','ruangan'=>'Ruang Rapat Lt.3','kondisi'=>'Dalam Perbaikan','nilai'=>8500000,'tanggal_pengadaan'=>'2023-01-05','serial_number'=>'DKAC-220X','penanggung_jawab'=>'Hadi Prasetyo','catatan'=>'Kompresor bermasalah.'],
            ['kode'=>'DPU-2203','nama'=>'Genset 500kVA Perkins','kategori'=>'Infrastruktur','ruangan'=>'Basement','kondisi'=>'Rusak','nilai'=>185000000,'tanggal_pengadaan'=>'2021-07-20','serial_number'=>'PRKS-500-2021','penanggung_jawab'=>'Hadi Prasetyo','catatan'=>'Kerusakan pada alternator.'],
            ['kode'=>'DPU-2204','nama'=>'Proyektor Epson EB-X49','kategori'=>'Elektronik & IT','ruangan'=>'Ruang Pelatihan','kondisi'=>'Baik','nilai'=>5200000,'tanggal_pengadaan'=>'2023-09-03','serial_number'=>'EBX49-2023','penanggung_jawab'=>'Sari Dewi','catatan'=>''],
            ['kode'=>'DPU-2205','nama'=>'Meja Kerja Standing Desk','kategori'=>'Furnitur Kantor','ruangan'=>'Lt. 2 - Open Space','kondisi'=>'Baik','nilai'=>3800000,'tanggal_pengadaan'=>'2024-02-15','serial_number'=>null,'penanggung_jawab'=>'Sari Dewi','catatan'=>''],
        ];
        foreach ($asetPku as $a) Aset::create(array_merge($a, ['kantor_id' => $pku->id]));

        // ── Aset Jakarta ──────────────────────────────────────────
        $asetJkt = [
            ['kode'=>'DPU-3101','nama'=>'Workstation Dell Precision 3660','kategori'=>'Elektronik & IT','ruangan'=>'Studio Desain','kondisi'=>'Baik','nilai'=>28000000,'tanggal_pengadaan'=>'2023-11-01','serial_number'=>'DELL-3660-2023','penanggung_jawab'=>'Budi Santoso','catatan'=>''],
            ['kode'=>'DPU-3102','nama'=>'Plotter HP DesignJet T830','kategori'=>'Elektronik & IT','ruangan'=>'Studio Desain','kondisi'=>'Baik','nilai'=>22500000,'tanggal_pengadaan'=>'2023-11-01','serial_number'=>'HPT830-001','penanggung_jawab'=>'Budi Santoso','catatan'=>''],
            ['kode'=>'DPU-3103','nama'=>'UPS APC 3000VA','kategori'=>'Infrastruktur','ruangan'=>'Ruang Server','kondisi'=>'Dalam Perbaikan','nilai'=>6800000,'tanggal_pengadaan'=>'2022-03-10','serial_number'=>'APC3000-01','penanggung_jawab'=>'Arif Budiman','catatan'=>'Baterai aus.'],
            ['kode'=>'DPU-3104','nama'=>'Kursi Ergonomis Herman Miller','kategori'=>'Furnitur Kantor','ruangan'=>'General','kondisi'=>'Baik','nilai'=>12500000,'tanggal_pengadaan'=>'2023-01-05','serial_number'=>null,'penanggung_jawab'=>'Arif Budiman','catatan'=>''],
        ];
        foreach ($asetJkt as $a) Aset::create(array_merge($a, ['kantor_id' => $jkt->id]));

        // ── Aset Surabaya ─────────────────────────────────────────
        $asetSby = [
            ['kode'=>'DPU-4101','nama'=>'Laptop Asus Vivobook 15','kategori'=>'Elektronik & IT','ruangan'=>'Ruang Kerja','kondisi'=>'Baik','nilai'=>9500000,'tanggal_pengadaan'=>'2024-04-20','serial_number'=>'ASUS-VB15-2024','penanggung_jawab'=>'Dewi Lestari','catatan'=>''],
            ['kode'=>'DPU-4102','nama'=>'Toyota Innova Crysta','kategori'=>'Kendaraan','ruangan'=>'Parkir','kondisi'=>'Baik','nilai'=>320000000,'tanggal_pengadaan'=>'2022-06-10','serial_number'=>'B-1234-XYZ','penanggung_jawab'=>'Firman Hadi','catatan'=>''],
            ['kode'=>'DPU-4103','nama'=>'Total Station Topcon ES-103','kategori'=>'Peralatan Survey','ruangan'=>'Gudang Alat','kondisi'=>'Baik','nilai'=>58000000,'tanggal_pengadaan'=>'2023-08-03','serial_number'=>'TPC-ES103-23','penanggung_jawab'=>'Dewi Lestari','catatan'=>''],
            ['kode'=>'DPU-4104','nama'=>'Laptop Dell Latitude 5540','kategori'=>'Elektronik & IT','ruangan'=>'Ruang Kerja','kondisi'=>'Rusak','nilai'=>14500000,'tanggal_pengadaan'=>'2023-09-01','serial_number'=>'DELL-L5540-01','penanggung_jawab'=>'Dewi Lestari','catatan'=>'LCD pecah.'],
        ];
        foreach ($asetSby as $a) Aset::create(array_merge($a, ['kantor_id' => $sby->id]));

        // ── Aset Bekasi ───────────────────────────────────────────
        $asetBks = [
            ['kode'=>'DPU-5101','nama'=>'Forklift Komatsu FG15','kategori'=>'Alat Berat','ruangan'=>'Gudang Bekasi','kondisi'=>'Baik','nilai'=>148000000,'tanggal_pengadaan'=>'2022-02-12','serial_number'=>'KMTSU-FG15-22','penanggung_jawab'=>'Hendra Susanto','catatan'=>''],
            ['kode'=>'DPU-5102','nama'=>'Concrete Mixer Molen 500L','kategori'=>'Alat Berat','ruangan'=>'Area Proyek','kondisi'=>'Dalam Perbaikan','nilai'=>35000000,'tanggal_pengadaan'=>'2021-05-05','serial_number'=>'MOLEN-500-21','penanggung_jawab'=>'Hendra Susanto','catatan'=>'Gear box aus.'],
            ['kode'=>'DPU-5103','nama'=>'Scaffolding Set (50 unit)','kategori'=>'Peralatan Konstruksi','ruangan'=>'Gudang','kondisi'=>'Baik','nilai'=>24500000,'tanggal_pengadaan'=>'2020-01-01','serial_number'=>null,'penanggung_jawab'=>'Agus Kurniawan','catatan'=>''],
            ['kode'=>'DPU-5104','nama'=>'Genset Mobile 100kVA','kategori'=>'Infrastruktur','ruangan'=>'Area Proyek','kondisi'=>'Rusak','nilai'=>85000000,'tanggal_pengadaan'=>'2021-01-01','serial_number'=>'GEN100K-21','penanggung_jawab'=>'Hendra Susanto','catatan'=>'Kerusakan panel kontrol.'],
        ];
        foreach ($asetBks as $a) Aset::create(array_merge($a, ['kantor_id' => $bks->id]));

        // ── Stok ──────────────────────────────────────────────────
        $stokData = [
            ['kode'=>'STK-001','nama'=>'Toner Printer HP LaserJet','satuan'=>'unit','stok'=>2,'min_stok'=>5,'kategori'=>'Konsumabel','kantor_id'=>$pku->id],
            ['kode'=>'STK-002','nama'=>'Kertas A4 80gsm','satuan'=>'rim','stok'=>15,'min_stok'=>10,'kategori'=>'Konsumabel','kantor_id'=>$pku->id],
            ['kode'=>'STK-003','nama'=>'Baterai UPS APC','satuan'=>'unit','stok'=>4,'min_stok'=>3,'kategori'=>'Suku Cadang','kantor_id'=>$pku->id],
            ['kode'=>'STK-004','nama'=>'Lampu LED 18W','satuan'=>'unit','stok'=>20,'min_stok'=>10,'kategori'=>'Mekanikal','kantor_id'=>$pku->id],
            ['kode'=>'STK-101','nama'=>'Cartridge Plotter HP','satuan'=>'unit','stok'=>3,'min_stok'=>2,'kategori'=>'Konsumabel','kantor_id'=>$jkt->id],
            ['kode'=>'STK-102','nama'=>'Kertas Plotter A0','satuan'=>'roll','stok'=>5,'min_stok'=>3,'kategori'=>'Konsumabel','kantor_id'=>$jkt->id],
            ['kode'=>'STK-201','nama'=>'Oli Mesin Toyota','satuan'=>'liter','stok'=>10,'min_stok'=>5,'kategori'=>'Perawatan Kendaraan','kantor_id'=>$sby->id],
            ['kode'=>'STK-202','nama'=>'Baterai Total Station','satuan'=>'unit','stok'=>0,'min_stok'=>2,'kategori'=>'Suku Cadang','kantor_id'=>$sby->id],
            ['kode'=>'STK-301','nama'=>'Solar Industri','satuan'=>'liter','stok'=>200,'min_stok'=>100,'kategori'=>'Bahan Bakar','kantor_id'=>$bks->id],
            ['kode'=>'STK-302','nama'=>'Pelumas Alat Berat','satuan'=>'drum','stok'=>3,'min_stok'=>2,'kategori'=>'Perawatan','kantor_id'=>$bks->id],
            ['kode'=>'STK-303','nama'=>'Sabuk Pengaman','satuan'=>'unit','stok'=>8,'min_stok'=>10,'kategori'=>'K3','kantor_id'=>$bks->id],
        ];
        foreach ($stokData as $s) Stok::create($s);

        // ── Mutasi ────────────────────────────────────────────────
        $asetDell = Aset::where('kode','DPU-3101')->first();
        $asetAC   = Aset::where('kode','DPU-2202')->first();
        $asetProj = Aset::where('kode','DPU-2204')->first();
        $asetUPS  = Aset::where('kode','DPU-3103')->first();
        $asetScaf = Aset::where('kode','DPU-5103')->first();

        $mutasiData = [
            ['kode'=>'MUT-001','aset_id'=>$asetDell->id,'kantor_asal_id'=>$jkt->id,'kantor_tujuan_id'=>$sby->id,'pengaju_id'=>$admin->id,'alasan'=>'Kebutuhan proyek Surabaya.','status'=>'Disetujui'],
            ['kode'=>'MUT-002','aset_id'=>$asetAC->id,  'kantor_asal_id'=>$pku->id,'kantor_tujuan_id'=>$bks->id,'pengaju_id'=>$admin->id,'alasan'=>'Pindah ke area kerja baru.','status'=>'Menunggu'],
            ['kode'=>'MUT-003','aset_id'=>$asetProj->id,'kantor_asal_id'=>$pku->id,'kantor_tujuan_id'=>$jkt->id,'pengaju_id'=>$admin->id,'alasan'=>'Kebutuhan presentasi Jakarta.','status'=>'Ditolak'],
            ['kode'=>'MUT-004','aset_id'=>$asetUPS->id, 'kantor_asal_id'=>$jkt->id,'kantor_tujuan_id'=>$pku->id,'pengaju_id'=>$admin->id,'alasan'=>'Pindah ke server room Pekanbaru.','status'=>'Menunggu'],
            ['kode'=>'MUT-005','aset_id'=>$asetScaf->id,'kantor_asal_id'=>$bks->id,'kantor_tujuan_id'=>$sby->id,'pengaju_id'=>$admin->id,'alasan'=>'Proyek konstruksi Surabaya.','status'=>'Disetujui'],
        ];
        foreach ($mutasiData as $m) Mutasi::create($m);

        // ── Jadwal ────────────────────────────────────────────────
        $asetGenset  = Aset::where('kode','DPU-2203')->first();
        $asetMolen   = Aset::where('kode','DPU-5102')->first();
        $asetForklift= Aset::where('kode','DPU-5101')->first();
        $asetTopcon  = Aset::where('kode','DPU-4103')->first();

        $jadwalData = [
            ['kode'=>'JDW-001','aset_id'=>$asetGenset->id, 'kantor_id'=>$pku->id,'jenis'=>'Penggantian Alternator','teknisi'=>'Pak Budiono',      'tanggal'=>'2025-04-18','waktu'=>'09:00 - 12:00','status'=>'Dalam Proses'],
            ['kode'=>'JDW-002','aset_id'=>$asetAC->id,     'kantor_id'=>$pku->id,'jenis'=>'Perbaikan Kompresor',   'teknisi'=>'Daikin Service',    'tanggal'=>'2025-04-22','waktu'=>'09:00 - 11:00','status'=>'Terjadwal'],
            ['kode'=>'JDW-003','aset_id'=>$asetForklift->id,'kantor_id'=>$bks->id,'jenis'=>'Pemeriksaan Tahunan',  'teknisi'=>'PT. Komatsu Indo',  'tanggal'=>'2025-04-25','waktu'=>'08:00 - 16:00','status'=>'Terjadwal'],
            ['kode'=>'JDW-004','aset_id'=>$asetMolen->id,  'kantor_id'=>$bks->id,'jenis'=>'Penggantian Gear Box',  'teknisi'=>'Bengkel Mitra',     'tanggal'=>'2025-04-30','waktu'=>'08:00 - 17:00','status'=>'Terjadwal'],
            ['kode'=>'JDW-005','aset_id'=>$asetTopcon->id, 'kantor_id'=>$sby->id,'jenis'=>'Kalibrasi Tahunan',     'teknisi'=>'Topcon Indonesia',  'tanggal'=>'2025-05-02','waktu'=>'10:00 - 12:00','status'=>'Terjadwal'],
            ['kode'=>'JDW-006','aset_id'=>$asetProj->id,   'kantor_id'=>$pku->id,'jenis'=>'Pembersihan Lensa',     'teknisi'=>'Tim Internal',      'tanggal'=>'2025-04-05','waktu'=>'10:00 - 11:00','status'=>'Selesai'],
            ['kode'=>'JDW-007','aset_id'=>$asetUPS->id,    'kantor_id'=>$jkt->id,'jenis'=>'Penggantian Baterai',   'teknisi'=>'APC Partner',       'tanggal'=>'2025-04-10','waktu'=>'09:00 - 11:00','status'=>'Terlewat'],
        ];
        foreach ($jadwalData as $j) Jadwal::create($j);

        // ── Audit Log awal ────────────────────────────────────────
        AuditLog::create(['user_name'=>'System','aksi'=>'Database seeder dijalankan — data awal berhasil dimuat.','modul'=>'System','icon'=>'database','bg'=>'#f0fdf4','ic'=>'#16a34a']);
        AuditLog::create(['user_name'=>'Ahmad Santoso','aksi'=>'Login ke sistem','modul'=>'Auth','icon'=>'login','bg'=>'#f8fafc','ic'=>'#94a3b8']);
    }
}