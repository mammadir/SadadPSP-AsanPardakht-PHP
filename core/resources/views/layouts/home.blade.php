<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('fp::partials.home.header')

<body>
@yield('content')
@include('fp::partials.home.footer')
</body>

</html>
