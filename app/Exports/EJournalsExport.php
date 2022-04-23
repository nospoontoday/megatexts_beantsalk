<?php

namespace App\Exports;

use App\Models\EJournal;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class EJournalsExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($eJournal): array
    {
        return [
            $eJournal->id,
            $eJournal->e_issn,
            $eJournal->editor->name,
            $eJournal->publisher->name,
            $eJournal->platform->name,
            $eJournal->accessModel->name,
            $eJournal->product->title,
            $eJournal->frequency,
            $eJournal->subscription_period,
            $eJournal->product->price,
            $eJournal->product->quantity,
            $eJournal->total_amount,
            $eJournal->product->subject,
        ];
    }

    public function query()
    {
        return EJournal::with([
            'product',
            'editor',
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
            'E-ISSN',
            'Editor',
            'Publisher',
            'Platform',
            'Access Model',
            'Title',
            'Frequency',
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
                $event->sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
