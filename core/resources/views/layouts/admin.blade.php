<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('fp::partials.admin.header')

<body>
@include('fp::partials.admin.navbar')
@include('fp::partials.admin.main')
@include('fp::partials.admin.footer')
</body>

</html>
