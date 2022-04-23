<?php

namespace App\Imports;

use App\Models\Author;
use App\Models\AVMaterial;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class AVMaterialsImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $author = Author::firstOrCreate(['name' => $row['author']]);
        $publisher = Publisher::firstOrCreate(['name' => $row['publisher']]);

        //find the type
        $type = Type::where('name', 'AV-materials')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $row['title'],
            'quantity' => $row['quantity'],
            'price'    => $row['price'],
            'subject'  => $row['subject'],
        ]);

        $avMaterial = AVMaterial::create([
            'product_id' => $product->id,
            'author_id'  => $author->id,
            'publisher_id' => $publisher->id,
            'item_code'      => $row['item_code'],
            'publication_year' => $row['publication_year'],
            'discount'         => array_key_exists('discount', $row) ?? $row['discount'],
        ]);

        return $avMaterial;
    }
}
