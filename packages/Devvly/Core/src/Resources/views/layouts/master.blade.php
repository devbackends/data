<!DOCTYPE html>
<html  lang="en" >
    <head>
        <!-- Required Meta Tags -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('css/style.css')}}" />
        <script baseUrl="{{ url()->to('/') }}" src="{{ asset('themes/default/assets/js/core.js') }}"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>2AData |
        @hasSection('page_title')
        @yield('page_title')
        @endif
        </title>

        @yield('head')

        @section('seo')

        @show

        @stack('css')

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png')  }}">

        <!-- Google Tag Manager -->
        <!-- End Google Tag Manager -->

    </head>

<body>
<!-- Google Tag Manager (noscript) -->
<!-- End Google Tag Manager (noscript) -->

    @include('core::layouts.header.index')
    <div id="app">
        <div class="main-container-wrapper">
        @yield('content-wrapper')
        </div>
    </div>

    @section('footer')

    @include('core::layouts.footer.index')

    <!-- JS FILES -->

    <script type="text/javascript" src="https://cdn.rawgit.com/igorlino/elevatezoom-plus/1.1.6/src/jquery.ez-plus.js"></script>
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!-- custom js scripts -->
    <!-- END JS FILES -->


    @show

    @stack('scripts')


</body>

</html >