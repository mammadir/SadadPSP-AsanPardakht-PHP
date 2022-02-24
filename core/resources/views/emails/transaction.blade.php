<div dir="rtl">
    <h1>{{ lang('lang.new_transaction') }}</h1>
    <div>
        <table style="table-layout: fixed; text-align: center;" border="1">
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
                    {{ lang('lang.card_number') }}
                </td>
                <td style="direction: ltr">
                    <span>{{ $transaction->payment_info['card_number'] }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    {{ lang('lang.date') }}
                </td>
                <td>{{ $transaction->full_jalali_created_at }}</td>
            </tr>
            <tr>
                <td>
                    {{ lang('lang.transaction_details') }}
                </td>
                <td>
                    <a href="{{ route('admin-transactions-detail', ['id' => $transaction->id]) }}" target="_blank">{{ lang('lang.view') }}</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
