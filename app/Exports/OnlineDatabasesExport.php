<?php

namespace App\Exports;

use App\Models\OnlineDatabase;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class OnlineDatabasesExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($onlineDatabase): array
    {
        return [
            $onlineDatabase->id,
            $onlineDatabase->publisher->name,
            $onlineDatabase->platform->name,
            $onlineDatabase->accessModel->name,
            $onlineDatabase->product->title,
            $onlineDatabase->subscription_period,
            $onlineDatabase->product->price,
            $onlineDatabase->product->quantity,
            $onlineDatabase->total_amount,
            $onlineDatabase->product->subject,
        ];
    }

    public function query()
    {
        return OnlineDatabase::with([
            'product',
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
            'Publisher',
            'Platform',
            'Access Model',
            'Title',
            'Subscription Period',
            'Price',
            'Quantity',
            'Total Amount',
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
