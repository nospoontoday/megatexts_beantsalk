<?php

namespace App\Imports;

use App\Models\LibraryFixture;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class LibraryFixturesImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $manufacturer = Manufacturer::firstOrCreate(['name' => $row['manufacturer']]);

        //find the type
        $type = Type::where('name', 'library-fixtures')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $row['title'],
            'quantity' => $row['quantity'],
            'price'    => $row['price'],
            'subject'  => $row['subject'],
        ]);

        $libraryFixture = LibraryFixture::create([
            'product_id'    => $product->id,
            'manufacturer_id'  => $manufacturer->id,
            'item_code'     => $row['item_code'],
            'dimension'     => $row['dimension'],
            'vatable_sales' => $row['vatable_sales'],
            'vat'           => $row['vat'],
        ]);
        
        return $libraryFixture;
    }
}
