@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.configs') }}@endsection

@section('content')
    <div class="card">
        <div class="card-header">{{ lang('lang.configs') }}</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <form method="post" action="{{ route('admin-configs') }}">
                        {{ csrf_field() }}
                        @foreach($configs as $config)
                            <div class="form-group">
                                <label>{{ $config->label }}</label>
                                <input type="text" class="form-control" name="{{ $config->key }}" value="{{ $config->value }}">
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">{{ lang('lang.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">{{ lang('lang.scripts') }}</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin-configs-scripts') }}">
                        {{ csrf_field() }}
                        <textarea name="scripts" id="" class="form-control ltr" rows="10">{!! site_config('scripts') !!}</textarea>
                        <button type="submit" class="btn btn-primary mt-2">{{ lang('lang.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">{{ lang('lang.styles') }}</div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin-configs-styles') }}">
                        {{ csrf_field() }}
                        <textarea name="styles" id="" class="form-control ltr" rows="10">{!! site_config('styles') !!}</textarea>
                        <button type="submit" class="btn btn-primary mt-2">{{ lang('lang.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
