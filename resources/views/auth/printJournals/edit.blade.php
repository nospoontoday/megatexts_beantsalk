@extends('layouts.sidebar')
@section('page-specific-js')
    <!-- printBooks/create -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Edit Print Journals</a></div>
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
                    
                    {!! Form::model($printJournal, ['method' => 'PATCH','route' => ['print.print-journals.update', $printJournal->id], 'enctype' => 'multipart/form-data']) !!}
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">ISSN</label>
                            {!! Form::text('issn', null, array('placeholder' => 'ISSN','class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-2">
                            <label class="form-label mb-0 fw-bold">Upload Book Cover</label>
                            <div class="img-holder">
                                <img src="{{ asset('products/' . $photo ) }}" alt="Book Cover" width="60px">
                            </div>
                            <input type="file" name="image">                     
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-8">
                            <label class="form-label mb-0 fw-bold">Editor</label>
                            {!! Form::text('editor[name]', null, array('placeholder' => 'Editor','class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-8">
                            <label class="form-label mb-0 fw-bold">Title/ED</label>
                            {!! Form::text('product[title]', null, array('placeholder' => 'Title','class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-8">
                            <label class="form-label mb-0 fw-bold">Issue No.</label>
                            {!! Form::text('issue_number', null, array('placeholder' => 'Issue Number','class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">QTY</label>
                            {!! Form::text('product[quantity]', null, array('placeholder' => 'Quantity','class' => 'form-control bg-white')) !!}
                        </div>
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Unit Price Per Issue</label>
                            {!! Form::text('product[price]', null, array('placeholder' => 'Unit Price','class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-4">
                            <label class="form-label mb-0 fw-bold">Subject</label>
                            {!! Form::text('product[subject]', null, array('placeholder' => 'Subject','class' => 'form-control bg-white')) !!}
                        </div>
                    </div>
                    <div class="float-end mt-4">
                          <a type="button" href="{{url('print/print-journals')}}" class="btn bg-light rounded-pill mx-4 px-4"> Cancel</a>
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
<script defer>
    $(document).ready(function(){

        //Reset input file
        $('input[type="file"][name="image"]').val('');
        //Image preview
        $('input[type="file"][name="image"]').on('change', function(){
            var img_path = $(this)[0].value;
            var img_holder = $('.img-holder');
            var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();
            if(extension == 'jpeg' || extension == 'jpg' || extension == 'png'){
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
                $(img_holder).empty();
            }
        });
    });

</script>
@endsection

