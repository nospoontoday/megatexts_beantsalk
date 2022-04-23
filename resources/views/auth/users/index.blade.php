@extends('layouts.sidebar')
@section('page-specific-js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
@endsection
@section('content')
<style>
.footer-widget {
    height: 100%;
    width: 100%;
}

</style>
<div class="container">
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
            <div class="tabs">
                <div class="tab active"><a class="fw-bold text-primary" href="#">Profile</a></div>
              </div>
            <div class="card">
                <div class="card-body p-4">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="footer-widget bg-info rounded-4 p-4">
                                    <div class="text-center">
                                        <img src="{{ asset('profile_pictures/' . $user->profile_pic) }}" alt="Profile Pic" width="60px">
                                    </div>
                                    <div class="text-center mb-3">{{Auth::user()->username}}</div>
                                    <div class="text-center mb-4"><button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#updateprofile"><i class="bi bi-upload"></i>  Upload</button></div>
                                    <div class="mb-2 col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Username:</strong>
                                            {{ $user->username ?? "" }}
                                        </div>
                                    </div>
                                    <div class="mb-2 col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Contact:</strong>
                                            {{ $user->contact->mobile ?? "" }}
                                        </div>
                                    </div>
                                    <div class="mb-2 col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Email:</strong>
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="footer-widget border rounded-4 pb-4">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active text-primary" href="#">Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-primary" href="{{ route('activities.show', $user->id) }}">User Activity Log</a>
                                        </li>
                                    </ul>
                                    <div class="container p-4">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <strong>Name:</strong>
                                                    {{ $user->first_name }} {{ $user->last_name }}
                                                </div>
                                                <div class="form-group mb-3">
                                                    <strong>Role:</strong>
                                                    @if(!empty($user->getRoleNames()))
                                                        @foreach($user->getRoleNames() as $v)
                                                            {{ $v }}
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="form-group mb-3">
                                                    <strong>Present Address:</strong>
                                                    {{ $user->presentAddress->present_address ?? "" }}
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group mb-3">
                                                    <strong>Gender:</strong>
                                                    {{ $user->gender }}
                                                </div>
                                                <div class="form-group mb-3">
                                                    <strong>Birth Date:</strong>
                                                    {{ $user->birth_date }}
                                                </div>
                                                <div class="form-group mb-3">
                                                    <strong>Permanent Address:</strong>
                                                    {{ $user->permanent_address }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="updateprofile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-4 rounded-4">
      <div class="modal-body ">
      <div>
         <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
            <div class="mb-4">
              <b class="fs-3">Upload Profile Picture</b>
            </div> 
            <div class="mb-4">
            {!! Form::model($user, ['method' => 'PATCH','route' => ['profile.update', $user->id], 'enctype' => 'multipart/form-data']) !!}
                <div class="img-holder"></div>
                <input type="file" name="image">
                <input type="submit" class="btn-primary rounded-pill px-4" value="Update">
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