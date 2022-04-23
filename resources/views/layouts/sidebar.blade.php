<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

     <!-- Scripts -->
     <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Sidebar -->
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    @yield('page-specific-css')
    @yield('page-specific-js')
    @livewireStyles
</head>
<body>
    <nav class="navbar navbar-expand-lg  bg-white p-3">
        <div class="container-fluid">
            <div class="col-md-10">
                <a href="{{route('dashboard')}}" class="navbar-brand py-0 px-3"><img src="{{ asset('img/megatexts-logo.png') }}" height="40px"></a>
                <a id="sidebarToggle"  class="btn btn-sm btn-primary"><i class="bi bi-list"></i></a>
            </div>
            <div>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                    
                    <a class="nav-link dropdown-toggle text-dark" href="#"  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();    
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>

                    </ul>
                    </li>
                </ul>
        
            </div>
        </div>
    </nav>
    
    <div class="d-flex" id="wrapper">   
        <div class="border-end-0 bg-white" id="sidebar-wrapper">
            <div class="list-group list-group-flush pt-4">
                <a href="{{route('dashboard')}}" class="disabled list-group-item list-group-item-action border-0 {{ (request()->is('dashboard')) ? 'bg-primary text-white' : '' }}"><b class="ps-4"><i class="bi bi-columns-gap pe-4"></i>   Dashboard</b></a>
                <a href="{{url('sales-order')}}" class="list-group-item list-group-item-action border-0"><b class="ps-4"><i class="bi bi-receipt pe-4"></i>   Sales Order</b></a>
                <a href="{{url('customers')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('customers')) ? 'bg-primary text-white' : '' }}"><b class="ps-4"><i class="bi bi-file-person pe-4"></i> Customer</b></a>
                <div class="dropdown list-group-item list-group-item-action border-0 {{ (request()->is('print/print-books')) ? 'bg-primary' : '' }}">
                    <a class="dropbtn">
                        <b class="ps-4 {{ (request()->is('print/print-books')) ? 'text-white' : '' }}"><i class="bi bi-cart3 pe-4"></i> Products</b>
                    </a>
                    <div class="dropdown-content">
                        <a class="list-group-item list-group-item-action" href="{{url('print/print-books')}}">Print</a>
                        <a class="list-group-item list-group-item-action" href="{{url('digital/e-books')}}">Digital</a>
                    </div>
                </div>
                <a href="{{url('vendors')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('vendors')) ? 'bg-primary text-white' : '' }}"><b class="ps-4"><i class="bi bi-people pe-4"></i> Vendors</b></a>
                @if(request()->is('vendors', 'product-list'))
                <a href="{{ url('product-list') }}"  class="list-group-item list-group-item-action border-0 {{ (request()->is('product-list')) ? 'bg-primary text-white' : '' }}" style="text-indent: 40px;"><b class="ps-4"> Product List</b></a>
                @endif
                <a href="{{url('quotations')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('quotations')) ? 'bg-primary text-white' : '' }}"><b class="ps-4"><i class="bi bi-file-text pe-4"></i> Quotations</b></a>
                <a href="" class="disabled list-group-item list-group-item-action border-0"><b class="ps-4"><i class="bi bi-cash-coin pe-4"></i> Purchase Orders</b></a>
                <a href="" class="disabled list-group-item list-group-item-action border-0"><b class="ps-4"><i class="bi bi-box pe-4"></i> Inventory</b></a>
                <a href="{{route('admin-dashboard')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('settings')) ? 'bg-primary text-white' : '' }}"><b class="ps-4"><i class="bi bi-gear pe-4"></i> Settings</b></a>
                @if(request()->is('settings', 'profile', 'branch'))
                <a href="{{url('profile')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('profile')) ? 'bg-primary text-white' : '' }}" style="text-indent: 60px;"><b class="ps-4"> Profile</b></a>
                <a href="#" class="list-group-item list-group-item-action border-0 {{ (request()->is('branch')) ? 'bg-primary text-white' : '' }}" style="text-indent: 60px;"><b class="ps-4"> Branch</b></a>
                @endif
                
            </div>

            
            
        </div>

         <div id="page-content-wrapper">
            
           
    
            <main class="py-4">
                @yield('content')
            </main>
                
            </div>
        </div>
    </div>
    @livewireScripts
    <script src="{{ asset('js/sidebar.js') }}">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('alert', function(e){
            Swal.fire(e.detail);
        });

        @if($message = session('success'))
        Swal.fire(
        'Good job!',
        '{{ $message }}',
        'success'
        )
        @endif
</script>    
    </script>
</body>
@yield('custom-non-head-js')
</html>
