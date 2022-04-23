<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Guest CSS -->
        <link href="{{ asset('css/guest.css') }}" rel="stylesheet">
    </head>
    <body class="bg-white">
    <div id="app">                
        <div class="container">
            <div class="row">
                <div class="col-md-4 mt-4">
                    <img src="{{ asset('img/megatexts-logo.png') }}" height="40px">
                </div>
            </div>
        </div>
                          
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
   $(document).on('click', '.toggle-password', function() {
        $(this).toggleClass("bi-eye bi-eye-slash");
            var input = $("#password");
        input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
    });
</script>
</html>
