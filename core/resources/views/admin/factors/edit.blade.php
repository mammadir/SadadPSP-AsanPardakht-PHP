@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.edit_factor') }}@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      {{ lang('lang.edit_factor') }}
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <form method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="txt-title" class="label">{{ lang('lang.title') }} ({{ lang('lang.required') }})</label>
              <input type="text" class="form-control col-md-6" id="txt-title" name="title" value="{{ $factor->title }}">
            </div>
            <div class="form-group">
              <label for="txt-title" class="label">{{ lang('lang.tax') }} ({{ lang('lang.required') }})</label>
              <input type="number" class="form-control col-md-6" id="txt-tax" name="tax" value="{{ $factor->tax }}">
            </div>
            <div class="form-group">
              <label class="label">{{ lang('lang.items') }} ({{ lang('lang.required') }})</label>
              <a href="javascript:" class="float-left" onclick="addNewField()">{{ lang('lang.add_new_item') }}</a>
              <div id="items">
                @foreach($factor->items as $key => $item)
                  <div class="form-group item" id="item0">
                    <div class="input-group">
                      <input type="text" name="items_name[]" class="form-control col-md-3" placeholder="نام محصول" value="{{ $item['name'] }}">
                      <input type="number" name="items_count[]" class="form-control col-md-1" placeholder="تعداد" value="{{ $item['count'] }}">
                      <input type="number" name="items_price[]" class="form-control col-md-3" placeholder="قیمت واحد (ریال) بدون لحاظ کردن مالیات" value="{{ $item['price'] }}">
                      <input type="text" name="items_description[]" class="form-control col-md-5" placeholder="توضیحات" value="{{ $item['description'] }}">
                      @if($key > 0)
                        <a href="javascript:" class="btn btn-danger delete-item">حذف</a>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="form-group">
              <button class="btn btn-primary">{{ lang('lang.edit') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .item {
      position: relative;
    }

    .delete-item {
      position: absolute;
      left: 0;
      top: 0;
    }
  </style>
@endpush

@push('scripts')
  <script>
    let items = 1;

    function showAdvanced() {
      $('#advanced').show();
      $('#btn-advanced').hide();
    }

    function addNewField() {
      $('#items').append('' +
        '<div class="form-group item" id="item' + (items + 1) + '">\n' +
        '  <div class="input-group">\n' +
        '    <input type="text" name="items_name[]" class="form-control col-md-3" placeholder="نام محصول">\n' +
        '    <input type="number" name="items_count[]" class="form-control col-md-1" placeholder="تعداد">\n' +
        '    <input type="number" name="items_price[]" class="form-control col-md-3" placeholder="قیمت واحد (ریال) بدون لحاظ کردن مالیات">\n' +
        '    <input type="text" name="items_description[]" class="form-control col-md-5" placeholder="توضیحات">\n' +
        '    <a href="javascript:" class="btn btn-danger delete-item">حذف</a>\n' +
        '  </div>\n' +
        '</div>');
      items++;
    }

    $('#items').on('click', '.delete-item', function () {
      $(this).parent().parent('.item').remove();
    })
  </script>
@endpush
