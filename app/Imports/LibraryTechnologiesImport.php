<?php

namespace App\Imports;

use App\Models\Developer;
use App\Models\LibraryTechnology;
use App\Models\Product;
use App\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class LibraryTechnologiesImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $developer = Developer::firstOrCreate(['name' => $row['developer']]);

        //find the type
        $type = Type::where('name', 'library-technologies')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $row['title'],
            'quantity' => $row['quantity'],
            'price'    => $row['price'],
            'subject'  => $row['subject'],
        ]);

        $libraryTechnology = LibraryTechnology::create([
            'product_id'            => $product->id,
            'developer_id'          => $developer->id,
            'item_code'             => $row['item_code'],
            'subscription_period'   => $row['subscription_period'],
            'vatable_sales'         => $row['vatable_sales'],
            'vat'                   => $row['vat'],
        ]);

        return $libraryTechnology;
    }
}
