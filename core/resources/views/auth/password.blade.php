@extends('fp::layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ lang('lang.reset_password') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
                            @include('fp::extensions.alert')
                            <div class="form-group">
                                <label for="email">{{ lang('lang.email') }}</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    {{ lang('lang.reset_password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection