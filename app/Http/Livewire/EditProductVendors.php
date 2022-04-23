<?php

namespace App\Http\Livewire;

use App\Models\PrintBook;
use App\Models\PrintJournal;
use App\Models\Product;
use App\Models\Type;
use App\Models\Vendor;
use Livewire\Component;

class EditProductVendors extends Component
{
    public $vendorId;
    public $vendor;
    public $name;
    public $contact_person;
    public $phone_number;
    public $email;
    public $website;
    public $present_address;
    public $city;
    public $state;
    public $zip;
    public $productVendors = [];
    public $books = [];
    public $products;

    public function rules()
    {
        return [
            'name' => 'required',
            'contact_person' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'website' => 'sometimes|required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'productVendors.*.title' => 'sometimes|required',
            'productVendors.*.quantity' => 'required|numeric|min:1',
            'productVendors.*.type' => 'required',
        ];
    }

    public function mount($vendor)
    {
        $this->vendorId = $vendor->id;
        $this->vendor = $vendor;
        $this->name = $vendor->name;
        $this->contact_person = $vendor->contact_person;
        $this->phone_number = $vendor->contact->mobile;
        $this->email = $vendor->presentAddress->email;
        $this->website = $vendor->presentAddress->website;
        $this->address = $vendor->presentAddress->present_address;
        $this->city = $vendor->presentAddress->city;
        $this->state = $vendor->presentAddress->state;
        $this->zip = $vendor->presentAddress->zip;
        $this->products = $vendor->products;

        foreach ($this->products as $key => $product) {

            $this->productVendors[$key] = [
                'product_id' => $product->id,
                'title' => $product->title, 
                'price' => $product->pivot->price, 
                'quantity' => $product->pivot->quantity, 
                'type' => $product->type->name,
            ];

            $this->setBooks($product->type->name, $key);
        }
    }

    public function productsHaveDuplicates() {
        foreach($this->productVendors as $key => $productVendor) {
            foreach($this->productVendors as $search_key => $search_array){
                if($search_array['title'] == $productVendor['title'] && $search_array['type'] == $productVendor['type']) {
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

    public function update($id)
    {
        $this->validate();

        $this->productsHaveDuplicates();

        $this->validateTypes();

        $vendor = $this->updateVendor($id);

        if(!empty($this->productVendors)) {
            
            foreach($this->productVendors as $key=> $productVendor) {
                $type = Type::where('name', $productVendor['type'])->first();

                if($productVendor['product_id'] != null) {
                    $product = Product::updateOrCreate(
                        [
                            'id' => $productVendor['product_id'],
                        ], 
                        [
                            'type_id' => $type->id, 
                            'title' => $productVendor['title']
                        ],
                    );

                    ($product->wasRecentlyCreated)
                        ? $this->attachProducts($vendor, $productVendor, $product)
                        : $this->vendor->products()->updateExistingPivot($productVendor['product_id'],
                        [
                            'price'     => $productVendor['price'],
                            'quantity'  => $productVendor['quantity'],
                        ]);

                } else {
                    $product = Product::firstOrCreate(
                        ['title' => $productVendor['title'], 'type_id' => $type->id, 'is_vendor' => true],
                    );
    
                    $this->attachProducts($vendor, $productVendor, $product);                      
                }               
            }
        }

        return redirect()->route('vendors.index')
        ->with('success', 'Vendor Updated successfully');
    }

    public function updateVendor($id)
    {
        $vendor = Vendor::find($id);
        $vendor->name = $this->name;
        $vendor->contact_person = $this->contact_person;
        $vendor->save();

        $vendor->contacts()->update([
            'mobile' => $this->phone_number,
        ]);

        $vendor->addresses()->update([
            'website' => $this->website,
            'present_address' => $this->address,
            'email' => $this->email,
            'city'  => $this->city,
            'state'  => $this->state,
            'zip'  => $this->zip,
        ]);

        $this->vendor = $vendor;

        return $vendor;
    }

    public function attachProducts($vendor, $productVendor, $product)
    {
        $vendor->products()->attach($product->id,
            [
                'price'     => $productVendor['price'],
                'quantity'  => $productVendor['quantity'],
            ]
        );  
    }

    public function removeProduct($index)
    {
        if($this->productVendors[$index]['product_id'] != null) {
            $product = Product::find($this->productVendors[$index]['product_id']);
            $product->vendors()->detach();

            if($product->is_vendor) {
                $product->delete();
                
                if($this->productVendors[$index]['type'] == "print-books") {
                    $product->printBook->delete();
                }

                else if($this->productVendors[$index]['type'] == "print-journals") {
                    $product->printJournal->delete();
                }
            }
        }

        unset($this->productVendors[$index]);
        $this->productVendors = array_values($this->productVendors);
    }

    public function addProduct()
    {
        $this->productVendors[] = ['title' => '', 'price' => 0, 'quantity' => 0, 'type' => 'print-books', 'product_id' => null];
    }

    public function updateProductList($index)
    {
        $this->productVendors[$index]['title'] = '';
        $this->setBooks($this->productVendors[$index]['type'], $index);
    }

    public function setBooks($type, $index)
    {
        if($type == "print-books") {
            $this->books[$index] = PrintBook::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        } 
        else if ($type == "print-journals") {
            $this->books[$index] = PrintJournal::with([
                'product',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();   
        }
     
    }

    public function render()
    {
        return view('livewire.edit-product-vendors');
    }
}
