<?php

namespace App\Imports;

use App\Models\AccessModel;
use App\Models\Editor;
use App\Models\EJournal;
use App\Models\Platform;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EJournalsImport implements ToModel, WithHeadingRow, SkipsOnFailure
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
        $publisher = Publisher::firstOrCreate(['name' => $row['publisher']]);
        $platform = Platform::firstOrCreate(['name' => $row['platform']]);
        $access_model = AccessModel::firstOrCreate(['name' => $row['access_model']]);

        //find the type
        $type = Type::where('name', 'e-journals')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $row['title'],
            'quantity' => $row['quantity'],
            'price'    => $row['price'],
            'subject'  => $row['subject'],
        ]);

        $eJournal = EJournal::create([
            'product_id'            => $product->id,
            'editor_id'             => $editor->id,
            'publisher_id'          => $publisher->id,
            'e_issn'                => $row['e_issn'],
            'frequency'             => $row['frequency'],
            'platform_id'              => $platform->id,
            'access_model_id'          => $access_model->id,
            'subscription_period'   => $row['subscription_period'],
        ]);
        
        return $eJournal;
    }
}
