<?php

namespace App\Exports;

use App\Models\Book;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;




class BooksExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $no = 0;

    public function headings(): array
    {
        return [
            'No',
            'Sampul Buku',
            'Nama Kategori Buku',
            'Judul Buku',
            'Penulis',
            'Penerbit',
            'Tanggal Rilis',
            'Jumlah Halaman',
            'Bahasa',
            'Harga',
        ];
    }

    public function collection(): Collection
    {
        return Book::with('bookCategory')->get();
    }

    public function map($book): array
    {
        return [
            ++$this->no,
            $book->cover,
            $book->bookCategory->name ?? '-',
            $book->title,
            $book->writer,
            $book->publisher,
            $book->release_date,
            $book->page_of_book,
            $book->language,
            'Rp ' . number_format($book->price, 0, ',', '.'),
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
            "A1:J$totalRows" => [
                "borders" => [
                    "allBorders" => [
                        "borderStyle" => Border::BORDER_THIN,
                        "color" => ["rgb" => "000000"],
                    ]
                ]
            ]
        ];
    }
}
