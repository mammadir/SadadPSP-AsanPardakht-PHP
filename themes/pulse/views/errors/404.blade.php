@extends('fp::layouts.home')

@section('page-title')
  {{ lang('lang.not_found') }}
@endsection

@section('content')
  <div class="text-center py-5">
    <h1 class="text-center">{{ lang('lang.not_found') }}</h1>
  </div>
@endsection
