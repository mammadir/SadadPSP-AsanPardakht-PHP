<?php

namespace App\Http\Controllers;

use App\Factor;
use App\Transaction;
use Illuminate\Support\Facades\DB;

class FactorController extends Controller
{
    public function index($id = null)
    {
        if ($id) {
            $factor = Factor::where('id', '=', $id)->where('status', '=', Factor::$status['active'])->first();

            if ($factor) {
                return view('fp::home.factor.index')
                    ->with('factor', $factor);
            }
        }

        abort(404);
    }

    public function pay($id = null)
    {
        if ($id) {
            $factor = Factor::where('id', '=', $id)->where('status', '=', Factor::$status['active'])->where('paid', '=', 0)->first();
            if ($factor) {
                try {
                    return DB::transaction(function () use ($factor) {
                        $transaction = Transaction::create([
                            'type' => Transaction::$type['factor'],
                            'amount' => $factor->amount,
                            'details' => [
                                'factor_id' => $factor->id,
                                'factor_tax' => $factor->tax,
                                'factor_items' => $factor->items,
                            ],
                        ]);

                        return redirect()->route('pg-pay', ['id' => $transaction->id]);
                    });
                } catch (\Exception $e) {
                    return handle_exception($e);
                }
            }
        }

        abort(404);
    }
}
