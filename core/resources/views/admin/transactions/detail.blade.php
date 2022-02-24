<!DOCTYPE html>
<html>

@include('fp::partials.admin.header')

<body class="window">
<div>
  <br>
  <h2 class="text-center text-info"><b>{{ lang('lang.transaction_details') }}</b></h2>
  <br>
  <div class="table-responsive">
    <table class="table table-bordered text-center" style="table-layout: fixed;">
      <tbody>
      <tr>
        <td width="200">
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
        <td>
          <span class="{{ $transaction->status && $transaction->verified ? 'text-success' : 'text-danger' }}">{{ $transaction->status && $transaction->verified ? lang('lang.success') : lang('lang.failed') }}</span>
        </td>
      </tr>
      @if(isset($transaction->payment_info['trans_id']))
        <tr>
          <td>
            {{ lang('lang.payir_transaction_id') }}
          </td>
          <td>
            {{ $transaction->payment_info['trans_id'] }}
          </td>
        </tr>
      @endif
      @if(isset($transaction->payment_info['card_number']))
        <tr>
          <td>
            {{ lang('lang.card_number') }}
          </td>
          <td style="direction: ltr">
            <span>{{ $transaction->payment_info['card_number'] }}</span>
          </td>
        </tr>
      @endif
      @if($transaction->type == \App\Transaction::$type['form'] && $transaction->form())
        <tr>
          <td>{{ lang('lang.form_details') }}</td>
          <td>
            <a href="{{ route('form', ['id' => $transaction->form()->id]) }}" target="_blank">{{ $transaction->form()->title }}</a>
          </td>
        </tr>
        @if($transaction->details && isset($transaction->details['form_fields']))
          <tr>
            <td>
              {{ lang('lang.inputs') }}
            </td>
            <td>
              @foreach ($transaction->details['form_fields'] as $input)
                <b>{{ $input['label'] }} : </b> {{ $input['value'] }} <br>
              @endforeach
            </td>
          </tr>
        @endif
      @endif
      @if($transaction->type == \App\Transaction::$type['file'] && $transaction->file())
        <tr>
          <td>{{ lang('lang.form_details') }}</td>
          <td>
            <a href="{{ route('file', ['id' => $transaction->file()->id]) }}" target="_blank">{{ $transaction->file()->title }}</a>
          </td>
        </tr>
        @if($transaction->details && isset($transaction->details['file_fields']))
          <tr>
            <td>
              {{ lang('lang.inputs') }}
            </td>
            <td>
              @foreach ($transaction->details['file_fields'] as $input)
                <b>{{ $input['label'] }} : </b> {{ $input['value'] }} <br>
              @endforeach
            </td>
          </tr>
        @endif
      @endif
      @if($transaction->type == \App\Transaction::$type['factor'] && $transaction->factor())
        <tr>
          <td>{{ lang('lang.title') }}</td>
          <td>
            <a href="{{ route('admin-factors-filter', ['id' => $transaction->factor()->id]) }}" target="_blank">{{ $transaction->factor()->title }}</a>
          </td>
        </tr>
        @if($transaction->details && isset($transaction->details['factor_items']))
          <tr>
            <td>
              {{ lang('lang.factor') }}
            </td>
            <td>
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
                @foreach($transaction->details['factor_items'] as $key => $item)
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
                  <td>{{ $transaction->factor()->tax }}</td>
                  <th>{{ lang('lang.total_amount') }}</th>
                  <td>{{ custom_money_format($transaction->factor()->amount) }}</td>
                </tr>
                </tbody>
              </table>
            </td>
          </tr>
        @endif
      @endif
      <tr>
        <td>
          {{ lang('lang.date') }}
        </td>
        <td>{{ $transaction->full_jalali_created_at }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
</body>
