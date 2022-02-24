@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.transactions') }}@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">{{ lang('lang.filter') }}</div>
        <div class="card-body">
            <form action="{{ route('admin-transactions-filter') }}" class="form-inline">
                <div class="form-group mb-2">
                    <label for="txt-id" class="sr-only">{{ lang('lang.id') }}</label>
                    <input type="text" name="id" class="form-control" id="txt-id" placeholder="{{ lang('lang.id') }}" value="@if(isset($inputs['id'])){{ $inputs['id'] }}@endif">
                </div>
                <div class="form-group mx-2 mb-2">
                    <label for="txt-card-number" class="sr-only">{{ lang('lang.card_number') }}</label>
                    <input type="text" name="card_number" class="form-control" id="txt-card-number" placeholder="{{ lang('lang.card_number') }}" value="@if(isset($inputs['card_number'])){{ $inputs['card_number'] }}@endif" title="0000-0000-0000-0000">
                </div>
                <div class="form-group mx-2 mb-2">
                    <label for="select-type" class="sr-only">{{ lang('lang.type') }}</label>
                    <select name="type" id="select-type" class="form-control">
                        <option value="" selected>{{ lang('lang.type') }}</option>
                        @foreach(\App\Transaction::$typeLabels as $key => $value)
                            <option value="{{ $key }}" @if(isset($inputs['type']) && $inputs['type'] == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mx-2 mb-2">
                    <label for="select-status" class="sr-only">{{ lang('lang.status') }}</label>
                    <select name="status" id="select-status" class="form-control">
                        <option value="" @if(isset($inputs['status']) && $inputs['status'] == '') selected @endif>{{ lang('lang.status') }}</option>
                        <option value="1" @if(isset($inputs['status']) && $inputs['status'] == '1') selected @endif>{{ lang('lang.success') }}</option>
                        <option value="0" @if(isset($inputs['status']) && $inputs['status'] == '0') selected @endif>{{ lang('lang.failed') }}</option>
                    </select>
                </div>
                <div class="form-group mx-2 mb-2">
                    <button class="btn btn-primary">{{ lang('lang.filter') }}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">{{ lang('lang.transactions') }}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-center table-hover table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{ lang('lang.id') }}</th>
                        <th>{{ lang('lang.type') }}</th>
                        <th>{{ lang('lang.amount') }}</th>
                        <th>{{ lang('lang.date') }}</th>
                        <th>{{ lang('lang.status') }}</th>
                        <th>{{ lang('lang.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ \App\Transaction::$typeLabels[$transaction->type] }}</td>
                            <td>{{ custom_money_format($transaction->amount) }}</td>
                            <td>{{ $transaction->full_jalali_created_at }}</td>
                            <td>
                                <span class="{{ $transaction->status && $transaction->verified ? 'text-success' : 'text-danger' }}">{{ $transaction->status && $transaction->verified ? lang('lang.success') : lang('lang.failed') }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin-transactions-detail', ['id' => $transaction->id]) }}" class="btn-popup">{{ lang('lang.details') }}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                @include('fp::extensions.pagination', ['paginator' => $transactions])
            </div>
        </div>
    </div>
@endsection
