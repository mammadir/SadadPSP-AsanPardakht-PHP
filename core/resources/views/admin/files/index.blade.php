@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.files') }}@endsection

@section('content')
<div class="card">
  <div class="card-header">
    {{ lang('lang.files') }}
    <a href="{{ route('admin-files-add') }}" class="btn btn-success btn-sm float-left">{{ lang('lang.add_new_file') }}</a>
  </div>
  <div class="card-body">
    <div class="">
      <table class="table text-center table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th>{{ lang('lang.id') }}</th>
            <th>{{ lang('lang.title') }}</th>
            <th>{{ lang('lang.amount') }}</th>
            <th>{{ lang('lang.sell_count') }}</th>
            <th>{{ lang('lang.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($files as $file)
          <tr>
            <td>{{ $file->id }}</td>
            <td>
              <a href="{{ route('file', ['id' => $file->id]) }}" target="_blank">{{ $file->title }}</a>
              @if($file->default)
              <span class="badge badge-primary">{{ lang('lang.default') }}</span>
              @endif
            </td>
            <td>{{ custom_money_format($file->amount) }}</td>
            <td>{{ $file->pay_count }}</td>
            <td>
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{ lang('lang.share') }}
                </button>
                <div class="dropdown-menu">
                  <a href="https://t.me/share/url?url={{ site_config('site_url') . '/file/' . $file->id }}" class="dropdown-item" target="_blank">{{ lang('lang.telegram') }}</a>
                  <a href="https://api.whatsapp.com/send?text={{ site_config('site_url') . '/file/' . $file->id }}" class="dropdown-item" target="_blank">{{ lang('lang.whatsapp') }}</a>
                </div>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{ lang('lang.actions') }}
                </button>
                <div class="dropdown-menu">
                  <a href="{{ route('admin-files-edit', ['id' => $file->id]) }}" class="dropdown-item">{{ lang('lang.edit') }}</a>
                  <a href="{{ route('admin-files-delete', ['id' => $file->id]) }}" class="dropdown-item">{{ lang('lang.delete') }}</a>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="text-center">
      @include('fp::extensions.pagination', ['paginator' => $files])
    </div>
  </div>
</div>
@endsection
