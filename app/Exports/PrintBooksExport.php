<?php

namespace App\Exports;

use App\Models\PrintBook;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class PrintBooksExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($printBook): array
    {
        return [
            $printBook->id,
            $printBook->isbn_13,
            $printBook->author->name,
            $printBook->publisher->name,
            $printBook->product->title,
            $printBook->publication_year,
            $printBook->product->quantity,
            $printBook->product->price,
            $printBook->discount,
            $printBook->total_amount,
            $printBook->product->subject,
        ];
    }

    public function query()
    {
        return PrintBook::with([
            'product',
            'author',
            'publisher',
        ])
        ->whereIn('id', $this->selectedRows);
    }

    public function headings(): array
    {
        return [
            'ID#',
            'ISBN 13',
            'Author',
            'Publisher',
            'Title',
            'Publication Year',
            'Quantity',
            'Price',
            'Discount',
            'Total Amount',
            'Subject'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
