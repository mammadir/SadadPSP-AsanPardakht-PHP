<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Transaction;
use App\PaymentProviders\PSP\SadadPSP;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function callbacksadad(Request $request)
    {
        $rules = [
            'id' => 'required|exists:fp_transactions,id',
            'token' => 'required',
            'ResCode' => 'required',
            'OrderId' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            abort(404);
        }
        try {
            DB::beginTransaction();
            $transaction = Transaction::lockForUpdate()->find($request->id);
            if ($transaction) {
                if ($transaction->status && $transaction->verified) {
                    return $this->showReceipt($transaction);
                } else if (!$transaction->status && !$transaction->verified && isset($transaction->payment_info['token']) && $transaction->payment_info['token'] == $request->token) {
                    $paymentProvider = new SadadPSP();
                    $verify = $paymentProvider->verify($request->token);
                    if (isset($verify['ResCode']) && $verify['ResCode'] != -1 && $verify['ResCode'] == 0 && $verify['OrderId'] == $transaction->id && $verify['Amount'] == $transaction->amount) {
                        $transaction->update([
                            'payment_info' => [
                                'status' => 1,
                                'token' => $request->token,
                                'trans_id' => $verify['RetrivalRefNo'],
                                'card_number' => "0000",
                                'system_trace_no' => $verify['SystemTraceNo'],
                                'description' => $verify['Description'],
                                'transactionDate' => $verify['TransactionDate'],
                            ],
                            'status' => 1,
                            'verified' => 1,
                            'paid_at' => date('Y-m-d H:i:s'),
                            'verified_at' => date('Y-m-d H:i:s'),
                        ]);
                        switch ($transaction->type) {
                            case Transaction::$type['form']:
                                $transaction->form()->update(['pay_count' => $transaction->form()->pay_count + 1]);
                                break;
                            case Transaction::$type['file']:
                                $transaction->file()->update(['pay_count' => $transaction->file()->pay_count + 1]);
                                break;
                            case Transaction::$type['factor']:
                                $transaction->factor()->update([
                                    'paid' => 1,
                                    'transaction_id' => $transaction->id,
                                ]);
                                break;
                        }
                        DB::commit();

                        $this->sendMail($transaction);

                        return $this->showReceipt($transaction);
                    }
                }
            }
            DB::rollBack();

            return $this->showReceipt($transaction);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function pay(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_transactions,id,status,0,verified,0',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            abort(404);
        }

        $transaction = Transaction::find($id);

        return $this->payWithPayir($transaction);
    }

    /**
     * @param Transaction $transaction
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function payWithPayir(Transaction $transaction)
    {
        $paymentProvider = new SadadPSP();
        $paymentInfo = $paymentProvider->send($transaction->amount, $transaction->id);
        if (isset($paymentProvider->paymentUrl) && $paymentProvider->paymentUrl) {
            $transaction->update([
                'payment_info' => [
                    'token' => $paymentInfo['Token'],
                ],
            ]);

            return redirect($paymentProvider->paymentUrl);
        }

        return redirect()->back()
            ->with('alert', 'danger')
            ->with('message', isset($paymentProvider->errorMessage) ? $paymentProvider->errorMessage : 'Error');
    }

    /**
     * @param Transaction $transaction
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function showReceipt(Transaction $transaction)
    {
        return view('fp::home.receipt')
            ->with('transaction', $transaction);
    }

    /**
     * @param Transaction $transaction
     * @throws \Exception
     */
    private function sendMail(Transaction $transaction)
    {
        $user = User::first();
        if ($user) {
            try {
                Mail::send('emails.transaction', ['user' => $user, 'transaction' => $transaction], function ($m) use ($user) {
                    $m->to($user->email, $user->name)->subject(lang('lang.new_transaction'));
                });
            } catch (\Exception $e) {
                if (app('site_configs')['APP_ENV'] === 'local') {
                    throw $e;
                }
            }
        }
    }
}
