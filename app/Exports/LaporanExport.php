<?php

namespace App\Exports;

use App\Models\Aset;
use App\Models\Kantor;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;

// ── Sheet 1: Ringkasan per Kantor ─────────────────────────────
class RingkasanSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected array $kantorList;

    public function __construct(array $kantorList)
    {
        $this->kantorList = $kantorList;
    }

    public function title(): string { return 'Ringkasan'; }

    public function headings(): array
    {
        return ['Kantor', 'Total Aset', 'Kondisi Baik', 'Dalam Perbaikan', 'Rusak', 'Nilai Aset', '% Baik'];
    }

    public function collection(): Collection
    {
        return collect($this->kantorList)->map(fn($k) => [
            $k['nama'],
            $k['stat']['total'],
            $k['stat']['baik'],
            $k['stat']['perbaikan'],
            $k['stat']['rusak'],
            $k['stat']['nilai'],
            $k['stat']['total'] > 0
                ? round($k['stat']['baik'] / $k['stat']['total'] * 100) . '%'
                : '0%',
        ]);
    }

    public function columnWidths(): array
    {
        return ['A' => 35, 'B' => 14, 'C' => 16, 'D' => 20, 'E' => 10, 'F' => 18, 'G' => 12];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = count($this->kantorList) + 1;

        // Header info perusahaan
        $sheet->mergeCells('A1:G1');
        $sheet->insertNewRowBefore(1, 3);
        $sheet->setCellValue('A1', 'PT. DIAN PILAR UTAMA');
        $sheet->setCellValue('A2', 'Laporan Inventaris Aset');
        $sheet->setCellValue('A3', 'Tanggal Cetak: ' . now()->translatedFormat('d F Y'));

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'F97316']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
            2 => ['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '0F172A']]],
            3 => ['font' => ['size' => 10, 'color' => ['rgb' => '64748B']]],
            4 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            "A4:G{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ],
        ];
    }
}

// ── Sheet 2: Semua Aset ───────────────────────────────────────
class SemuaAsetSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected bool $isAdmin;
    protected ?int $kantorDbId;

    public function __construct(bool $isAdmin, ?int $kantorDbId)
    {
        $this->isAdmin    = $isAdmin;
        $this->kantorDbId = $kantorDbId;
    }

    public function title(): string { return 'Data Aset'; }

    public function headings(): array
    {
        return ['No', 'Kode Aset', 'Nama Aset', 'Kategori', 'Kantor', 'Ruangan',
                'Kondisi', 'Penanggung Jawab', 'Nilai (Rp)', 'Tanggal Pengadaan', 'Serial Number'];
    }

    public function collection(): Collection
    {
        $query = Aset::with('kantor');
        if (!$this->isAdmin && $this->kantorDbId) {
            $query->where('kantor_id', $this->kantorDbId);
        }

        return $query->latest()->get()->map(fn($a, $i) => [
            $i + 1,
            $a->kode,
            $a->nama,
            $a->kategori,
            $a->kantor?->short_name ?? '-',
            $a->ruangan ?? '-',
            $a->kondisi,
            $a->penanggung_jawab ?? '-',
            $a->nilai,
            $a->tanggal_pengadaan?->format('d/m/Y') ?? '-',
            $a->serial_number ?? '-',
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F97316']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}

// ── Main Export ───────────────────────────────────────────────
class LaporanExport implements WithMultipleSheets
{
    protected array  $kantorList;
    protected bool   $isAdmin;
    protected ?int   $kantorDbId;

    public function __construct(array $kantorList, bool $isAdmin, ?int $kantorDbId)
    {
        $this->kantorList  = $kantorList;
        $this->isAdmin     = $isAdmin;
        $this->kantorDbId  = $kantorDbId;
    }

    public function sheets(): array
    {
        return [
            new RingkasanSheet($this->kantorList),
            new SemuaAsetSheet($this->isAdmin, $this->kantorDbId),
        ];
    }
}