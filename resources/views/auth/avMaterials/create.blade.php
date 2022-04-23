@extends('layouts.sidebar')
@section('page-specific-js')
    <!-- printBooks/create -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="{{ asset('js/autocomplete.js') }}" defer></script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Add AV Materials</a></div>
            </div>
            <div class="card pt-0">
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

                    {!! Form::open(array('route' => 'print.av-materials.store','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Item Code</label>
                            {!! Form::text('item_code', null,array('class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-2">
                            <label class="form-label mb-0 fw-bold">Upload Book Cover</label>
                            <div class="img-holder"></div>
                            <input type="file" name="image">                     
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Author</label>
                            {!! Form::text('author', null,array('class' => 'form-control bg-white', 'id' => 'author')) !!}
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Publisher</label>
                            {!! Form::text('publisher', null, array('class' => 'form-control bg-white', 'id' => 'publisher')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-8">
                            <label class="form-label mb-0 fw-bold">Title/ED</label>
                            {!! Form::text('title', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Pub Yr</label>
                            {!! Form::text('publication_year', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Quantity</label>
                            {!! Form::number('quantity', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Unit Price</label>
                            {!! Form::number('price', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Discount</label>
                            {!! Form::number('discount', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Subject</label>
                            {!! Form::text('subject', null, array('class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="float-end mt-4">
                          <a type="button" href="{{url('print/av-materials')}}" class="btn bg-light rounded-pill mx-4 px-4"> Cancel</a>
                          <button type="submit"  class="btn btn-primary rounded-pill px-4"> Save</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-non-head-js')
<!-- avMaterials/create -->
<script defer>
    $(document).ready(function(){
        const field = document.getElementById('author');
        const field2 = document.getElementById('publisher');

        const ac = new Autocomplete(field, {
            data: [{label: "I'm a label", value: 42}],
            maximumItems: 5,
            threshold: 1,
            onSelectItem: ({label, value}) => {

            }
        });

        const ac2 = new Autocomplete(field2, {
            data: [{label: "I'm a label", value: 42}],
            maximumItems: 5,
            threshold: 1,
            onSelectItem: ({label, value}) => {

            }
        });

        var authors_url = {!! json_encode(url('/api/authors')) !!}
        var publishers_url = {!! json_encode(url('/api/publishers')) !!}
        $.get(authors_url, function(data, status){
            ac.setData(
                data
            );
        });
        $.get(publishers_url, function(data, status){
            ac2.setData(
                data
            );
        });
    });

</script>

<script defer>
    $(document).ready(function(){
        // Preview book cover
        //Reset input file
        $('input[type="file"][name="image"]').val('');
        //Image preview
        $('input[type="file"][name="image"]').on('change', function(){
            var img_path = $(this)[0].value;
            var img_holder = $('.img-holder');
            var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();
            if(extension == 'jpeg' || extension == 'jpg' || extension == 'png'){
                    console.log((typeof(FileReader) != 'undefined'));
                    if(typeof(FileReader) != 'undefined'){
                        img_holder.empty();
                        var reader = new FileReader();
                        reader.onload = function(e){
                            $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:100px;margin-bottom:10px;'}).appendTo(img_holder);
                        }
                        img_holder.show();
                        reader.readAsDataURL($(this)[0].files[0]);
                    }else{
                        $(img_holder).html('This browser does not support FileReader');
                    }
            }else{
                $(img_holder).html("<span>The image must be a file of type: png, jpg, jpeg.</span>");
            }
        });
    });

</script>
@endsection

