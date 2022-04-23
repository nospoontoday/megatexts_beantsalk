<?php

namespace App\Imports;

use App\Models\Editor;
use App\Models\PrintJournal;
use App\Models\Product;
use App\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class PrintJournalsImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $editor = Editor::firstOrCreate(['name' => $row['editor']]);

        $type = Type::where('name', 'print-journals')->first();

        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $row['title'],
            'quantity' => $row['quantity'],
            'price'    => $row['price'],
            'subject'  => $row['subject'],
        ]);

        $printJournal = PrintJournal::create([
            'product_id' => $product->id,
            'editor_id'  => $editor->id,
            'issn'       => $row['issn'],
            'issue_number' => $row['issue_number'],
        ]);

        return $printJournal;
    }
}
