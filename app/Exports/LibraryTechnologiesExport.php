<?php

namespace App\Exports;

use App\Models\LibraryTechnology;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class LibraryTechnologiesExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($libraryTechnology): array
    {
        return [
            $libraryTechnology->id,
            $libraryTechnology->item_code,
            $libraryTechnology->developer->name,
            $libraryTechnology->product->title,
            $libraryTechnology->subscription_period,
            $libraryTechnology->product->price,
            $libraryTechnology->product->quantity,
            $libraryTechnology->total_amount,
            $libraryTechnology->vatable_sales,
            $libraryTechnology->vat,
            $libraryTechnology->product->subject,
        ];
    }

    public function query()
    {
        return LibraryTechnology::with([
            'product',
            'developer',
        ])
        ->whereIn('id', $this->selectedRows);
    }

    public function headings(): array
    {
        return [
            'ID#',
            'Item Code',
            'Developer',
            'Title',
            'Subscription Period',
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
                $event->sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
