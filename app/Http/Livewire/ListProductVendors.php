<?php

namespace App\Http\Livewire;

use App\Models\PrintBook;
use App\Models\PrintJournal;
use App\Models\Product;
use App\Models\Type;
use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class ListProductVendors extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $productVendors = [];
    public $vendors = [];
    public $books;
    public $type;

    public function rules()
    {
        return [
            'vendors.company_name' => 'required|unique:vendors,name',
            'vendors.contact_person' => 'required',
            'vendors.phone_number' => 'required',
            'vendors.email' => 'required|unique:addresses,email',
            'vendors.website' => 'sometimes|required',
            'vendors.address' => 'required',
            'vendors.city' => 'required',
            'vendors.state' => 'required',
            'vendors.zip' => 'required',
            'productVendors.*.title' => 'sometimes|required',
            'productVendors.*.quantity' => 'required|numeric|min:1',
            'productVendors.*.type' => 'required',
        ];
    }

    public function productsHaveDuplicates() {
        foreach($this->productVendors as $key => $productvendor) {
            foreach($this->productVendors as $search_key => $search_array){
                if($search_array['title'] == $productvendor['title']) {
                    if($search_key != $key) {
                        $error = \Illuminate\Validation\ValidationException::withMessages([
                            'productVendors['.$key.'][title]' => ['Duplicates found in products'],
                        ]);
                        throw $error;                        
                    }
                }
            }
        }
    }

    public function validateTypes()
    {
        $validTypes = [];
        $types = Type::get();

        foreach($types as $type) {
            $validTypes[] = $type->name;
        }

        foreach($this->productVendors as $key => $productVendor) {
            if (! in_array($productVendor['type'], $validTypes)) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'productVendors['.$key.'][type]' => ['Invalid product type found'],
                ]);
                throw $error;     
            }
        }
    }

    public function mount()
    {   
        $this->setBooks('print-books');

        $this->productVendors = [
            ['title' => '', 'price' => 0, 'quantity' => 0, 'type' => 'print-books']
        ];
    }

    public function setBooks($type)
    {
        if($type == "print-books") {
            $this->books = PrintBook::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        } 
        else if ($type == "print-journals") {
            $this->books = PrintJournal::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        }
     
    }

    public function addProduct()
    {
        $this->productVendors[] = ['title' => '', 'price' => 0, 'quantity' => 0, 'type' => 'print-books'];
    }

    public function removeProduct($index)
    {
        unset($this->productVendors[$index]);
        $this->productVendors = array_values($this->productVendors);
    }

    public function submit()
    {
        $this->validate();

        $this->productsHaveDuplicates();

        $this->validateTypes();

        $vendor = $this->createVendor();

        if(!empty($this->productVendors)) {
            
            foreach($this->productVendors as $productVendor) {
                $type = Type::where('name', $productVendor['type'])->first();
                $product = Product::firstOrCreate(
                    ['title' => $productVendor['title']], ['type_id' => $type->id, 'is_vendor' => true],
                );

                if($type->name == 'print-books') {
                    $printBook = PrintBook::firstOrCreate(
                        ['product_id' => $product->id],
                    );
                }

                if($type->name == 'print-journals') {
                    $printJournal = PrintJournal::firstOrCreate(
                        ['product_id' => $product->id],
                    );
                }

                $vendor->products()->attach($product->id,
                    [
                        'price'     => $productVendor['price'],
                        'quantity'  => $productVendor['quantity'],
                    ]
                );                
            }
        }

        return redirect()->route('vendors.index')
        ->with('success', 'Vendor created successfully');
    }

    public function createVendor()
    {
        $vendor = Vendor::create([
            'name'              => $this->vendors['company_name'],
            'contact_person'    => $this->vendors['contact_person'],
            'mobile'            => $this->vendors['phone_number'],
        ]);

        $vendor->addresses()->create([
            'present_address'   => $this->vendors['address'],
            'website'           => $this->vendors['website'],
            'email'             => $this->vendors['email'],
            'city'              => $this->vendors['city'],
            'state'             => $this->vendors['state'],
            'zip'               => $this->vendors['zip'],
        ]);

        $vendor->contacts()->create([
            'mobile'            => $this->vendors['phone_number'],
        ]);
        
        return $vendor;
    }

    public function updateProductList($index)
    {
        $this->productVendors[$index]['title'] = '';
        $this->setBooks($this->productVendors[$index]['type']);
    }

    public function render()
    {
        return view('livewire.list-product-vendors');
    }
}
