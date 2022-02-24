<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::orderBy('id', 'desc')->paginate(20);

        return view('fp::admin.transactions.index')
            ->with('activeMenu', 'transactions')
            ->with('transactions', $transactions);
    }

    public function filter(Request $request)
    {
        if ($request->id || $request->type || $request->transaction_id || $request->card_number || $request->status || $request->status == '0') {
            $transactions = Transaction::where(function ($query) use ($request) {
                if ($request->id) {
                    $query->where('id', '=', $request->id);
                }
                if ($request->type) {
                    $query->where('type', '=', $request->type);
                }
                if ($request->transaction_id) {
                    $query->where('payment_info->transaction_id', '=', $request->transaction_id);
                }
                if ($request->card_number) {
                    $query->where('payment_info->card_number', '=', mask_card_number($request->card_number));
                }
                if ($request->status || $request->status == '0') {
                    $query->where('status', '=', $request->status)->where('verified', '=', $request->status);
                }
            })->orderBy('id', 'desc')->paginate(15);
        } else {
            $transactions = Transaction::orderBy('id', 'desc')->paginate(15);
        }

        return view('fp::admin.transactions.index')
            ->with('activeMenu', 'transactions')
            ->with('transactions', $transactions)
            ->with('inputs', $request->all());
    }

    public function detail(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_transactions',
        ];
        $this->validate($request, $rules);

        $transaction = Transaction::find($id);

        return view('fp::admin.transactions.detail')
            ->with('activeMenu', 'transactions')
            ->with('transaction', $transaction);
    }
}
