@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.forms') }}@endsection

@section('content')
<div class="card">
  <div class="card-header">
    {{ lang('lang.forms') }}
    <a href="{{ route('admin-forms-add') }}" class="btn btn-success btn-sm float-left">{{ lang('lang.add_new_form') }}</a>
  </div>
  <div class="card-body">
    <div class="">
      <table class="table text-center table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th>{{ lang('lang.id') }}</th>
            <th>{{ lang('lang.title') }}</th>
            <th>{{ lang('lang.amount') }}</th>
            <th>{{ lang('lang.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($forms as $form)
          <tr>
            <td>{{ $form->id }}</td>
            <td>
              <a href="{{ route('form', ['id' => $form->id]) }}" target="_blank">{{ $form->title }}</a>
              @if($form->default)
              <span class="badge badge-primary">{{ lang('lang.default') }}</span>
              @endif
            </td>
            <td>{{ $form->amount ? custom_money_format($form->amount) : 'دلخواه' }}</td>
            <td>
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{ lang('lang.share') }}
                </button>
                <div class="dropdown-menu">
                  <a href="https://t.me/share/url?url={{ site_config('site_url') . '/form/' . $form->id }}" class="dropdown-item" target="_blank">{{ lang('lang.telegram') }}</a>
                  <a href="https://api.whatsapp.com/send?text={{ site_config('site_url') . '/form/' . $form->id }}" class="dropdown-item" target="_blank">{{ lang('lang.whatsapp') }}</a>
                </div>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{ lang('lang.actions') }}
                </button>
                <div class="dropdown-menu">
                  @if(!$form->default)
                  <a href="{{ route('admin-forms-default', ['id' => $form->id]) }}" class="dropdown-item">{{ lang('lang.make_default') }}</a>
                  @endif
                  <a href="{{ route('admin-forms-edit', ['id' => $form->id]) }}" class="dropdown-item">{{ lang('lang.edit') }}</a>
                  <a href="{{ route('admin-forms-delete', ['id' => $form->id]) }}" class="dropdown-item">{{ lang('lang.delete') }}</a>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="text-center">
      @include('fp::extensions.pagination', ['paginator' => $forms])
    </div>
  </div>
</div>
@endsection
