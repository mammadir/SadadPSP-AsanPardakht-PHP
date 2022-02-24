@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.add_new_file') }}@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ lang('lang.add_new_file') }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="txt-title" class="label">{{ lang('lang.title') }} ({{ lang('lang.required') }})</label>
                            <input type="text" class="form-control" id="txt-title" name="title" value="{{ old('title') }}">
                        </div>
                        <div class="form-group">
                            <label for="txt-description" class="label">{{ lang('lang.description') }} ({{ lang('lang.optional') }})</label>
                            <textarea name="description" id="txt-description">{{ old('description') }}</textarea>
                            @include('extensions.editor', ['element' => '#txt-description'])
                        </div>
                        <div class="form-group">
                            <label for="txt-amount" class="label">{{ lang('lang.amount') }}</label>
                            <input type="text" class="form-control" id="txt-amount" name="amount" value="{{ old('amount') }}">
                        </div>
                        <div class="form-group">
                            <label for="txt-expire-day" class="label">{{ lang('lang.expire_day_title') }}</label>
                            <input type="text" class="form-control" id="txt-expire-day" name="expire_day" value="{{ old('expire_day') ? old('expire_day') : '1'}}">
                        </div>
                        <div class="form-group">
                            <label for="file-file" class="label">{{ lang('lang.file') }}</label>
                            <input type="file" class="form-control" id="file-file" name="file">
                        </div>
                        <div id="advanced" style="display: none">
                            <div class="form-group">
                                <label for="txt-pay-limit" class="label">{{ lang('lang.form_size') }}</label>
                                <div>
                                    <div class="form-check form-check-inline pl-0 ml-0 mr-2">
                                        <input class="form-check-input ml-0 mr-2" type="radio" name="form_size" id="radio-lg" value="12">
                                        <label class="form-check-label" for="radio-lg">{{ lang('lang.form_size_lg') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline pl-0 ml-0 mr-2">
                                        <input class="form-check-input ml-0 mr-2" type="radio" name="form_size" id="radio-md" value="8">
                                        <label class="form-check-label" for="radio-md">{{ lang('lang.form_size_md') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline pl-0 ml-0 mr-2">
                                        <input class="form-check-input ml-0 mr-2" type="radio" name="form_size" id="radio-sm" value="4" checked>
                                        <label class="form-check-label" for="radio-sm">{{ lang('lang.form_size_sm') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="file-image" class="label">{{ lang('lang.image') }} ({{ lang('lang.optional') }})</label>
                                <input type="file" class="form-control" id="file-image" name="image" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="txt-pay-limit" class="label">{{ lang('lang.pay_limit') }} ({{ lang('lang.optional') }})</label>
                                <input type="text" class="form-control" id="txt-pay-limit" name="pay_limit" value="{{ old('pay_limit') }}" placeholder="{{ lang('lang.to_unlimited_payment_leave_empty') }}">
                            </div>
                            <div class="form-group">
                                <label class="label">{{ lang('lang.fields') }} ({{ lang('lang.optional') }})</label>
                                <a href="javascript:" class="float-left" onclick="addNewField()">{{ lang('lang.add_new_field') }}</a>
                                <div id="fields">
                                    <div class="form-group" id="field0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text">
                                                    <input type="checkbox" name="required_fields[]" value="required_0" class="mr-1"> {{ lang('lang.required_field') }}
                                                </label>
                                            </div>
                                            <input type="text" name="fields[]" class="form-control" placeholder="عنوان فیلد - مثال: شماره موبایل">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a id="btn-advanced" href="javascript:" onclick="showAdvanced()">{{ lang('lang.advanced') }}</a>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success">{{ lang('lang.add') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .field {
            position: relative;
        }

        .delete-field {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 9999;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let fields = 1;

        function showAdvanced() {
            $('#advanced').show();
            $('#btn-advanced').hide();
        }

        function addNewField() {
            $('#fields').append('<div class="form-group field" id="field' + (fields + 1) + '"><div class="input-group"><div class="input-group-prepend"><label class="input-group-text"><input type="checkbox" name="required_fields[]" value="required_' + fields + '" class="mr-1"> فیلد اجباری</label></div><input type="text" name="fields[]" class="form-control" placeholder="عنوان فیلد"><a href="javascript:" class="btn btn-danger delete-field">حذف</a></div></div>')
            fields++;
        }

        $('#fields').on('click', '.delete-field', function () {
            $(this).parent().parent('.field').remove();
        })
    </script>
@endpush
