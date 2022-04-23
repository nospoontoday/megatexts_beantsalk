@extends('layouts.sidebar')


@section('content')


@section('content')
<div class="container">
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
    <div class="row justify-content-center px-2">
        <div class="col-md-12">
                <div class="tabs">
                @foreach($roles as $role)
                    @if(app('request')->input('role') == null && $role->name == config('constants.roles.ADMINISTRATOR'))
                    <div class="tab active">
                        <a href="{{ route('admin-dashboard') }}" class="fw-bold text-primary">Users</a>
                    </div>
                    @else
                    <div class="tab {{ app('request')->input('role') == $role->id ? 'active' : '' }}">
                        @if($role->id == 1)
                        <a href="{{ route('admin-dashboard') }}" class="fw-bold">Users</a>
                        @elseif($role->id == 2)
                        <a href="{{ route('admin-dashboard') }}?role=2" class="fw-bold {{ app('request')->input('role') == $role->id ? 'text-primary' : '' }}">Warehouse Admin</a>
                        @elseif($role->id == 3)
                        <a href="{{ route('admin-dashboard') }}?role=3" class="fw-bold {{ app('request')->input('role') == $role->id ? 'text-primary' : '' }}">Senior Sales Rep</a>
                        @elseif($role->id == 4)
                        <a href="{{ route('admin-dashboard') }}?role=4" class="fw-bold {{ app('request')->input('role') == $role->id ? 'text-primary' : '' }}">Sales Rep</a>
                        @else
                        
                        @endif
                    </div>
                    @endif
                @endforeach
                </div>
                <div class="card">
                    <div class="card-body p-4">
                        <div class="container">
                            <div class="col-md-8 mx-auto">
                                <div class="float-end mb-4">
                                    <button type="button"  class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addusers">Add Users</button>
                                </div>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">Administrator</th>
                                        </tr>
                                        @foreach($admins as $role => $admin)
                                        <tr>
                                            <td>
                                                <div>{{ $admin->fullName() }}<div>
                                                <small>Admin Head</small>
                                            </td>
                                            <td><button type="button" class="btn btn-light rounded-pill">Edit</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                


                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">
                                                <div class="py-4">
                                                    <div class="btn-group">
                                                        @foreach($branches as $branch_data)
                                                            @if(app('request')->input('role'))
                                                            <a 
                                                                href="{{ route('admin-dashboard') }}?branch={{ $branch_data->name }}&role={{ app('request')->input('role') }}" 

                                                                class="btn btn-primary {{ $branch_name['name'] == $branch_data->name ? 'active' : ''}}" 

                                                                aria-current="page">{{ $branch_data->name }}
                                                            </a>
                                                            @else
                                                            <a 
                                                                href="{{ route('admin-dashboard') }}?branch={{ $branch_data->name }}" 

                                                                class="btn btn-primary {{ $branch_name['name'] == $branch_data->name ? 'active' : ''}}" 

                                                                aria-current="page">{{ $branch_data->name }}
                                                            </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>

                                @foreach($users as $role => $data)
                                @if($role != config('constants.roles.ADMINISTRATOR'))
                                
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th colspan="2">{{ $role }}</th>
                                        </tr>
                                        @foreach($data as $user)
                                        <tr>
                                            <td>
                                                <div>{{ $user->full_name }}<div>
                                                <small>{{ $role }}</small>
                                            </td>
                                            <td><button type="button" class="btn btn-light rounded-pill">Edit</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                @endif
                                @endforeach
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addusers" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
      <div class="modal-body">
        <div class="px-4">
          <i type="submit" class="bi bi-x-circle float-end" data-bs-dismiss="modal" aria-label="Close"></i>
        </div>
        <div class="px-4 pt-4">
          <b class="fs-3">ADD USERS</b>
        </div> 
        {!! Form::open(array('route' => 'admin.users.store','method'=>'POST', 'class' => 'p-4')) !!}
            <div class="mb-4">
                <input type="text" name="first_name" class="form-control bg-white" placeholder="First Name">
            </div>
            <div class="mb-4">
                <input type="text" name="last_name" class="form-control bg-white" placeholder="Last Name">
            </div>
            <div class="mb-4">
                <input type="email" name="email" class="form-control bg-white" placeholder="Email">
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control bg-white" placeholder="Password">
            </div>
            <div class="mb-4">
                <input type="password" name="confirm-password" class="form-control bg-white" placeholder="Confirm Password">
            </div>
            <div class="mb-4">
                <select name="roles" id="roles" class="form-control bg-white">
                    <option value="" disabled selected>Please Select a Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id}}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <select name="branches" id="branches" class="form-control bg-white">
                    <option value="" disabled selected>Please Select a Branch</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id}}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
           
            <button type="submit" class="btn btn-primary text-center rounded-pill">Create Role</button>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection