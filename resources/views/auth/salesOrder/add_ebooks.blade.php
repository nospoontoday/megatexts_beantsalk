@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Add E-Books</a></div>
            </div>
            <div class="card pt-0">
                <!-- Livewire -->
                <div class="card-body p-4">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form wire:submit.prevent="">
                        @csrf
                    <div class="col-md-12 offset-md-1">
                        <div class="mb-2 row">
                            <div class="col-md-4">
                                <label class="form-label mb-0 fw-bold">Customer</label>
                                <select name="" id="" class="form-control bg-white">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label class="form-label mb-0 fw-bold">Branch</label>
                                <input 
                                    type="text"
                                    class="form-control bg-white"
                                />
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-4">
                                <label class="form-label mb-0 fw-bold">Address</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                />
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 fw-bold">Date</label>
                                        <input 
                                            type="date"
                                            class="form-control bg-white"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 fw-bold">IORF No.</label>
                                        <input 
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-2 row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 fw-bold">City</label>
                                        <input 
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 fw-bold">State</label>
                                        <input 
                                            type="text"
                                            class="form-control"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label class="form-label mb-0 fw-bold">PO/PR Number</label>
                                <input 
                                    type="text"
                                    class="form-control bg-white"
                                />
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-4">
                                <label class="form-label mb-0 fw-bold">Email Address</label>
                                <input 
                                    type="text"
                                    class="form-control bg-white"
                                />
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label class="form-label mb-0 fw-bold">Sales Rep</label>
                                <input 
                                    type="text"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-4">
                                <label class="form-label mb-0 fw-bold">Contact Number</label>
                                <input 
                                    type="number"
                                    class="form-control bg-white"
                                />
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-4">
                                <label class="form-label mb-0 fw-bold">Buyer Name</label>
                                <input 
                                    type="text"
                                    class="form-control bg-white"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="card" wire:key="products">

                        <div class="card-body px-0">
                            <div class="table-responsive">
                                <table class="table text-center" id="products_table">
                                    <thead class="bg-info">
                                    <tr>
                                        <th>S/N</th>
                                        <th>E-ISBN</th>
                                        <th>Author</th>
                                        <th>Publisher</th>
                                        <th>Platform</th>
                                        <th>Access Model</th>
                                        <th>Title/ED</th>
                                        <th>Pub Yr</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total Amount</th>
                                        <th>Subject</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <td colspan="12"></td>
                                        <td><a class="btn btn-primary rounded-pill bg-white text-primary" href="">Edit</a></td>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <!-- End Livewire -->
            </div>
        </div>
    </div>
</div>
@endsection