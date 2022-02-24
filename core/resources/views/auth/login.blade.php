@extends('fp::layouts.admin')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">{{ lang('lang.login') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
              {{ csrf_field() }}
              @include('fp::extensions.alert')
              <div class="form-group">
                <label for="email">{{ lang('lang.email') }}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
              </div>
              <div class="form-group">
                  <label for="password">{{ lang('lang.password') }}</label>
                  <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
              </div>
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">
                    {{ lang('lang.remember_me') }}
                  </label>
                </div>
              </div>
              <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary">
                  {{ lang('lang.login') }}
                </button>
                <a href="{{ route('password.email') }}" style="margin-right: 10px">{{ lang('lang.forget_your_password') }}</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
