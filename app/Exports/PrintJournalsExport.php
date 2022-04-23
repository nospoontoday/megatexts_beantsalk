<?php

namespace App\Exports;

use App\Models\PrintJournal;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class PrintJournalsExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use Exportable;

    protected $selectedRows;

    public function __construct($selectedRows)
    {
        $this->selectedRows = $selectedRows;
    }

    public function map($printJournal): array
    {
        return [
            $printJournal->id,
            $printJournal->issn,
            $printJournal->editor->name,
            $printJournal->product->title,
            $printJournal->issue_number,
            $printJournal->product->price,
            $printJournal->product->quantity,
            $printJournal->total_amount,
            $printJournal->product->subject,
        ];
    }

    public function query()
    {
        return PrintJournal::with([
            'product',
            'editor',
        ])
        ->whereIn('id', $this->selectedRows);
    }

    public function headings(): array
    {
        return [
            'ID#',
            'ISSN',
            'Editor',
            'Title',
            'Issue Number',
            'Unit Price Per Issue',
            'Quantity',
            'Total Amount',
            'Subject'
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }
}
