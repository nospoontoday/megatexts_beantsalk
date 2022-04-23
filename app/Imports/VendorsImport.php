<?php

namespace App\Imports;

use App\Models\Vendor;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VendorsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $vendor = Vendor::create(
            [
                'name'              => $row['name'],
                'contact_person'    => $row['contact_person'],
            ]
        );

        $vendor->addresses()->create(
            [
                'email'             => $row['email'],
                'city'              => $row['city'],
            ]
        );
        $vendor->contacts()->create(
            [
                'mobile'            => $row['mobile'],
            ]
        );

        return $vendor;

    }

    public function rules(): array
    {
        return [
            '*.name'      => Rule::unique('vendors', 'name'),
            '*.email'     => Rule::unique('addresses', 'email'),
        ];
    }
}
