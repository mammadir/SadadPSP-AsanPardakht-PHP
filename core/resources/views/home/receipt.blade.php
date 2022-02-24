@extends('fp::layouts.home')

@section('page-title')
    {{ lang('lang.payment_receipt') }}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-6 offset-md-3">
                <div class="card mt-5">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ lang('lang.receipt') }}</h4>
                        @if($transaction && $transaction->status && $transaction->verified)
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" style="table-layout: fixed;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ lang('lang.id') }}
                                            </td>
                                            <td>{{ $transaction->id }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ lang('lang.amount') }}
                                            </td>
                                            <td>{{ custom_money_format($transaction->amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ lang('lang.status') }}
                                            </td>
                                            <td>{{ $transaction->status ? lang('lang.success') : lang('lang.failed') }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ lang('lang.payir_transaction_id') }}
                                            </td>
                                            <td>{{ $transaction->payment_info['trans_id'] }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                {{ lang('lang.date') }}
                                            </td>
                                            <td>{{ $transaction->full_jalali_created_at }}</td>
                                        </tr>
                                        @if($transaction->type == \App\Transaction::$type['file'])
                                        <tr>
                                          <td>
                                            {{ lang('lang.download') }}
                                          </td>
                                          <td>
                                            <a href="{{ route('file-download', ['id' => $transaction->details['file_id']]) . '?token=' . Crypt::encrypt($transaction->id) }}">{{ lang('lang.download_link') }}</a>
                                          </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                {{ lang('lang.transaction_failed') }}
                            </div>
                            @if($transaction)
                                <a href="{{ route('pg-pay', ['id' => $transaction->id]) }}" class="btn btn-primary">{{ lang('lang.repay') }}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
