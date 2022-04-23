<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $customer = Customer::create(
            [
                'company_name'      => $row['company_name'],
                'buyer_name'        => $row['buyer_name'],
            ]
        );

        $customer->addresses()->create(
            [
                'email'             => $row['email'],
                'city'              => $row['city'],
            ]
        );
        $customer->contacts()->create(
            [
                'mobile'            => $row['mobile'],
            ]
        );

        return $customer;

    }

    public function rules(): array
    {
        return [
            '*.company_name'      => Rule::unique('customers', 'company_name'),
            '*.email'             => Rule::unique('addresses', 'email'),
        ];
    }

}
