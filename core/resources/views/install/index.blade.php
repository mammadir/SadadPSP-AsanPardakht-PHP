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
          <label for="site_url">آدرس سایت</label>
          <input type="text" id="site_url" name="site_url" class="form-control ltr" required value="{{ old('site_url') }}"/>
        </div>
        <div class="form-group">
          <label for="site_title">عنوان سایت</label>
          <input type="text" id="site_title" name="site_title" class="form-control" required value="{{ old('site_title') }}"/>
        </div>
        <div class="form-group">
          <label for="site_description">توضیحات سایت</label>
          <input type="text" id="site_description" name="site_description" class="form-control" required value="{{ old('site_description') }}"/>
        </div>
        <hr>
        <div class="form-group">
          <label for="db_host">آدرس دیتابیس</label>
          <input type="text" id="db_host" name="db_host" class="form-control ltr" required value="{{ old('db_host') ? old('db_host') : 'localhost' }}"/>
        </div>
        <div class="form-group">
          <label for="db_name">نام دیتابیس</label>
          <input type="text" id="db_name" name="db_name" class="form-control ltr" required value="{{ old('db_name') }}"/>
        </div>
        <div class="form-group">
          <label for="db_username">نام کاربری دیتابیس</label>
          <input type="text" id="db_username" name="db_username" class="form-control ltr" value="{{ old('db_username') }}"/>
        </div>
        <div class="form-group">
          <label for="db_password">کلمه عبور دیتابیس</label>
          <input type="text" id="db_password" name="db_password" class="form-control ltr" value="{{ old('db_password') }}"/>
        </div>
        <hr>
        <div class="form-group">
          <label for="admin_email">ایمیل مدیر</label>
          <input type="text" id="admin_email" name="admin_email" class="form-control ltr" value="{{ old('admin_email') }}"/>
        </div>
        <div class="form-group">
          <label for="admin_password">کلمه عبور مدیر</label>
          <input type="text" id="admin_password" name="admin_password" class="form-control ltr" value="{{ old('admin_password') }}"/>
        </div>
      </div>
    </div>
    <br>
    <button type="submit" class="btn btn-success" name="submit">نصب</button>
  </form>
</div>
</body>

</html>
