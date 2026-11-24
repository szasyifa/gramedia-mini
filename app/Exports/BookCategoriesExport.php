<?php

namespace App\Exports;

use App\Models\BookCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;



class BookCategoriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $no = 0;
    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Jumlah Buku',
        ];
    }

    public function collection(): Collection
    {
        return BookCategory::with('books')->get();
    }

    public function map($bookCategory): array
    {
        return [
            ++$this->no,
            $bookCategory->name,
            $bookCategory->books->count(),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $totalRows = $this->collection()->count() + 1;
        return [

            // style the first row as bold text
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
            ],
            // atur styling untuk A1:C10 (misalnya jumlah datanya 9, dinamis sesuai $totalRows)
            "A1:C$totalRows" => [
                "borders" => [
                    "allBorders" => [
                        "borderStyle" => Border::BORDER_THIN,
                        "color" => ["rgb" => "000000"], //kode rgb hitam
                    ]
                ]
            ]
        ];
    }
}
// headings -> tabel heading
// map -> mengubah, menyusun ulang, dan memilih data spesifik dari setiap baris
