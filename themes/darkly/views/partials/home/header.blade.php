<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title')</title>
    <link href="{{ theme_asset('assets/css/styles.min.css') }}" rel="stylesheet">
    @if(site_config('styles'))
        <style>
            {!! site_config('styles') !!}
        </style>
    @endif
</head>