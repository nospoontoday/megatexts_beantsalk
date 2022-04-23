<?php

namespace App\Imports;

use App\Models\Author;
use App\Models\PrintBook;
use App\Models\Product;
use App\Models\Publisher;
use App\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class PrintBooksImport implements ToModel, WithHeadingRow, SkipsOnFailure
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
        $type = Type::where('name', 'print-books')->first();

        //create the product
        $product = Product::create([
            'type_id' => $type->id,
            'title'   => $row['title'],
            'quantity' => $row['quantity'],
            'price'    => $row['price'],
            'subject'  => $row['subject'],
        ]);

        $printBook = PrintBook::create([
            'product_id' => $product->id,
            'author_id'  => $author->id,
            'publisher_id' => $publisher->id,
            'isbn_13'      => $row['isbn_13'],
            'publication_year' => $row['publication_year'],
            'discount'         => array_key_exists('discount', $row) ?? $row['discount'],
        ]);

        return $printBook;
    }
}
