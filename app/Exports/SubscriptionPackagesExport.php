<?php

namespace App\Exports;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubscriptionPackagesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private int $no = 0;

    public function headings(): array
    {
        return [
            ['Data Paket Langganan'],
            [
                'No',
                'Nama Paket Langganan',
                'Harga',
                'Jumlah Buku',
                'Pelanggan Aktif',
            ],
        ];
    }

    public function collection(): Collection
    {
        // Mengambil paket langganan beserta jumlah buku dan pelanggan aktif
        return SubscriptionPackage::with(['subscriptionPackageBooks', 'subscriptionPackageUsers'])->get();
    }

    public function map($package): array
    {
        return [
            ++$this->no,
            $package->name_package ?? $package->name,
            'Rp ' . number_format($package->price, 0, ',', '.'),
            $package->subscriptionPackageBooks->count(),
            $package->subscriptionPackageUsers->where('expired_date', '>=', today()->toDateString())->count(),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:E1');
        $totalRows = $this->collection()->count() + 2;

        return [
            1 => [
                'font' => [
                    'bold' =>true,
                    'size' => 16,
                ],
                'alignment' =>[
                    'horizontal' =>Alignment::HORIZONTAL_CENTER
                ],
            ],

            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
            ],

            "A2:E{$totalRows}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
