<?php

namespace App\Exports;

use App\Models\AVMaterial;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class AVMaterialsExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($avMaterial): array
    {
        return [
            $avMaterial->id,
            $avMaterial->item_code,
            $avMaterial->author->name,
            $avMaterial->publisher->name,
            $avMaterial->product->title,
            $avMaterial->publication_year,
            $avMaterial->product->quantity,
            $avMaterial->product->price,
            $avMaterial->discount,
            $avMaterial->total_amount,
            $avMaterial->product->subject,
        ];
    }

    public function query()
    {
        return AVMaterial::with([
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
            'Item Code',
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
