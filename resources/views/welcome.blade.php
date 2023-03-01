<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    @include('includes.head')

    </head>

    <body>
    <div class="container">
        @yield('page')
    </div>

    </body>

</html>
