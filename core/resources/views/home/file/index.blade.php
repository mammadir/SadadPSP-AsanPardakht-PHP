@extends('fp::layouts.home')

@section('page-title')
    {{ $file->title }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col col-md-{{ $file->form_size ? $file->form_size : '4' }}">
                <div class="card mt-5">
                    <form action="{{ route('file', ['id' => $file->id]) }}" method="post">
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $file->title }}</h4>
                            @if($file->description)
                                <p>{!! $file->description !!}</p>
                            @endif
                            @if($file->image)
                                <div class="text-center">
                                    <a href="{{ asset($file->image) }}" target="_blank">
                                        <img src="{{ asset($file->image) }}" class="img-thumbnail mb-3" alt="{{ $file->title }}">
                                    </a>
                                </div>
                            @endif
                            @if($file->fields)
                                @foreach($file->fields as $key => $f)
                                    <div class="file-group">
                                        <input class="form-control" type="text" name="{{ $f['name'] }}" placeholder="{{ $f['label'] }}" @if($f['required']==1) required @endif autocomplete="off" value="{{ old($f['name']) }}">
                                    </div>
                                @endforeach
                            @endif
                            <div class="file-group">
                                <input id="amount" name="amount" type="text" placeholder="{{ lang('lang.amount') }}" class="form-control" autocomplete="off" @if($file->amount) disabled="disabled" value="{{ custom_money_format($file->amount) }} {{ lang('lang.rial') }}" @else value="{{ old('amount') }}" @endif required>
                            </div>
                            @include('fp::extensions.alert')
                            <div class="file-group mt-2">
                                <button class="btn btn-primary">{{ lang('lang.pay') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
