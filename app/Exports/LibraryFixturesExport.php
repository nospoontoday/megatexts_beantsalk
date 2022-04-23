<?php

namespace App\Exports;

use App\Models\LibraryFixture;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class LibraryFixturesExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($libraryFixture): array
    {
        return [
            $libraryFixture->id,
            $libraryFixture->item_code,
            $libraryFixture->product->title,
            $libraryFixture->dimension,
            $libraryFixture->product->price,
            $libraryFixture->product->quantity,
            $libraryFixture->total_amount,
            $libraryFixture->vatable_sales,
            $libraryFixture->vat,
            $libraryFixture->product->subject,
        ];
    }

    public function query()
    {
        return LibraryFixture::with([
            'product',
            'manufacturer',
        ])
        ->whereIn('id', $this->selectedRows);
    }

    public function headings(): array
    {
        return [
            'ID#',
            'Item Code',
            'Description',
            'Dimension',
            'Price',
            'Quantity',
            'Total Amount',
            'Vatable Sales',
            'VAT',
            'Subject'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
