@extends('layouts.sidebar')

@section('content')
<style>
.footer-widget {
    height: 100%;
    width: 100%;
}
#scroll {
  margin-top: 10px;
  height: 150px;
  overflow: auto;
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
                                            {{ $user->username }}
                                        </div>
                                    </div>
                                    <div class="mb-2 col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Contact:</strong>
                                            {{ $user->contact ? $user->contact->mobile : "" }}
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
                                            <a class="nav-link text-primary" href="{{url('profile')}}">Info</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active text-primary" href="{{ route('activities.show', $user->id) }}">User Activity Log</a>
                                        </li>
                                    </ul>
                                    <div class="container p-4">
                                        <div class="row">
                                            <div class="col" id="scroll">
                                                @foreach($activitiesFormatted as $activity)
                                                <div>
                                                    <div>{{ $activity }}</div>
                                                </div>
                                                @endforeach
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
    <div class="modal-content p-4">
      <div class="modal-body ">
      <div>
         <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
            <div class="mb-4">
              <b class="fs-3">Upload Profile Picture</b>
            </div> 
            <div class="mb-4">
            {!! Form::model($user, ['method' => 'PATCH','route' => ['profile.update', $user->id], 'enctype' => 'multipart/form-data']) !!}
                <input type="file" name="image">
                <input type="submit" class="btn-primary rounded-pill px-4" value="Update">
            {!! Form::close() !!}
            </div>
      </div>
    </div>
  </div>
</div>
@endsection