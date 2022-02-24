@extends('fp::layouts.home')

@section('page-title')
    {{ $form->title }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col col-md-{{ $form->form_size ? $form->form_size : '4' }}">
                <div class="card mt-5">
                    <form action="{{ route('form', ['id' => $form->id]) }}" method="post">
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $form->title }}</h4>
                            @if($form->description)
                                <p>{!! $form->description !!}</p>
                            @endif
                            @if($form->image)
                                <div class="text-center">
                                    <a href="{{ asset($form->image) }}" target="_blank">
                                        <img src="{{ asset($form->image) }}" class="img-thumbnail mb-3" alt="{{ $form->title }}">
                                    </a>
                                </div>
                            @endif
                            @if($form->fields)
                                @foreach($form->fields as $key => $f)
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="{{ $f['name'] }}" placeholder="{{ $f['label'] }}" @if($f['required']==1) required @endif autocomplete="off" value="{{ old($f['name']) }}">
                                    </div>
                                @endforeach
                            @endif
                            <div class="form-group">
                                <input id="amount" name="amount" type="text" placeholder="{{ lang('lang.amount') }}" class="form-control" autocomplete="off" @if($form->amount) disabled="disabled" value="{{ custom_money_format($form->amount) }} {{ lang('lang.rial') }}" @else value="{{ old('amount') }}" @endif required>
                            </div>
                            @include('fp::extensions.alert')
                            <div class="form-group">
                                <button class="btn btn-primary">{{ lang('lang.pay') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
