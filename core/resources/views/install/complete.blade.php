<!DOCTYPE html>
<html>

<head>
  <title>نصب سیستم آسان پرداخت الکترونیک سداد</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/styles.css') }}">
</head>

<body>
<div id="wrapper" class="container py-4">
  <div class="row">
    <div class="col">
      <h1>نصب اسکریپت</h1>
    </div>
  </div>
  <hr>
  <br>
  <form method="post">
    {{ csrf_field() }}
    <div class="row">
      <div class="col">
        @include('extensions.alert')
        <div class="form-group">
          <label for="site_url">آدرس سایت</label> : {{ old('site_url') }}
          <input type="hidden" name="site_url" value="{{ old('site_url') }}">
        </div>
        <div class="form-group">
          <label for="site_title">عنوان سایت</label> : {{ old('site_title') }}
          <input type="hidden" name="site_title" value="{{ old('site_title') }}">
        </div>
        <div class="form-group">
          <label for="site_description">توضیحات سایت</label>: {{ old('site_description') }}
          <input type="hidden" name="site_description" value="{{ old('site_description') }}">
        </div>
        <hr>
        <div class="form-group">
          <label for="db_host">آدرس دیتابیس</label> : {{ old('db_host') }}
        </div>
        <div class="form-group">
          <label for="db_name">نام دیتابیس</label> : {{ old('db_name') }}
        </div>
        <div class="form-group">
          <label for="db_username">نام کاربری دیتابیس</label> : {{ old('db_username') }}
        </div>
        <div class="form-group">
          <label for="db_password">کلمه عبور دیتابیس</label> : ******
        </div>
        <hr>
        <div class="form-group">
          <label for="admin_email">ایمیل مدیر</label> : {{ old('admin_email') }}
          <input type="hidden" name="admin_email" value="{{ old('admin_email') }}">
        </div>
        <div class="form-group">
          <label for="admin_password">کلمه عبور مدیر</label> : ******
          <input type="hidden" name="admin_password" value="{{ old('admin_password') }}">
        </div>
      </div>
    </div>
    <br>
    <a href="{{ route('install') }}" class="btn btn-info">ویرایش اطلاعات‌</a>
    <button type="submit" class="btn btn-success" name="submit">شروع نصب‌</button>
  </form>
</div>
</body>

</html>
