@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.security_settings') }}@endsection

@section('content')
  <div class="card">
    <div class="card-header">{{ lang('lang.security_settings') }}</div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4 offset-md-4">
          <form method="post" action="{{ route('admin-security-settings-change-password') }}">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="txt-current-password">{{ lang('lang.current_password') }}</label>
              <input type="password" name="current_password" class="form-control ltr" id="txt-current-password">
            </div>
            <div class="form-group">
              <label for="txt-password">{{ lang('lang.new_password') }}</label>
              <input type="password" name="password" class="form-control ltr" id="txt-password">
            </div>
            <div class="form-group">
              <label for="txt-password-confirmation">{{ lang('lang.confirm_password') }}</label>
              <input type="password" name="password_confirmation" class="form-control ltr" id="txt-password-confirmation">
            </div>
            <button type="submit" class="btn btn-primary">{{ lang('lang.save') }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
