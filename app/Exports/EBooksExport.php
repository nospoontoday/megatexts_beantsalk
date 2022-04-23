<?php

namespace App\Exports;

use App\Models\EBook;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class EBooksExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($eBook): array
    {
        return [
            $eBook->id,
            $eBook->e_isbn,
            $eBook->author->name,
            $eBook->publisher->name,
            $eBook->platform->name,
            $eBook->accessModel->name,
            $eBook->product->title,
            $eBook->publication_year,
            $eBook->product->quantity,
            $eBook->product->price,
            $eBook->total_amount,
            $eBook->product->subject,
        ];
    }

    public function query()
    {
        return EBook::with([
            'product',
            'author',
            'publisher',
            'platform',
            'accessModel',
        ])
        ->whereIn('id', $this->selectedRows);
    }

    public function headings(): array
    {
        return [
            'ID#',
            'E-ISBN',
            'Author',
            'Publisher',
            'Platform',
            'Access Model',
            'Title',
            'Publication Year',
            'Quantity',
            'Price',
            'Total Amount',
            'Subject'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
