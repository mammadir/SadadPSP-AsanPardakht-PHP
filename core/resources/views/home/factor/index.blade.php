@extends('fp::layouts.home')

@section('page-title')
    {{ $factor->title }}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-12">
                <div class="card mt-5">
                    <form action="{{ route('factor', ['id' => $factor->id]) }}" method="post">
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <h4 class="card-title mb-4">
                                {{ $factor->title }}
                                @if($factor->paid)
                                    <span class="float-left badge badge-success">{{ lang('lang.factor_is_paid') }}</span>
                                @endif
                            </h4>
                            <div class="table-responsive">
                                <table class="table text-center table-hover table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{ lang('lang.row') }}</th>
                                        <th>{{ lang('lang.item_name') }}</th>
                                        <th>{{ lang('lang.item_count') }}</th>
                                        <th>{{ lang('lang.item_price') }}</th>
                                        <th>{{ lang('lang.item_description') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($factor->items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['count'] }}</td>
                                            <td>{{ $item['price'] }}</td>
                                            <td>{{ $item['description'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <table class="table text-center table-hover table-striped table-bordered">
                                    <tbody>
                                    <tr>
                                        <th>{{ lang('lang.tax') }}</th>
                                        <td>{{ $factor->tax }}</td>
                                        <th>{{ lang('lang.total_amount') }}</th>
                                        <td>{{ custom_money_format($factor->amount) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            @include('fp::extensions.alert')
                            @if(!$factor->paid)
                                <div class="form-group">
                                    <button class="btn btn-primary">{{ lang('lang.pay') }}</button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
