<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\File;
use App\Transaction;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function index($id = null)
    {
        if ($id) {
            $file = File::where('id', '=', $id)->where('status', '=', File::$status['active'])->first();

            if ($file) {
                return view('fp::home.file.index')
                    ->with('file', $file);
            }

            abort(404);
        }

        $file = File::where('default', '=', 1)->where('status', '=', File::$status['active'])->first();

        if ($file) {
            return view('fp::home.file.index')
                ->with('file', $file);
        }

        abort(404);
    }

    public function pay(Request $request, $id)
    {
        $file = File::where('id', '=', $id)->where('status', '=', File::$status['active'])->first();
        if (!$file) {
            abort(404);
        }

        if ($file) {
            if ($file->pay_limit && $file->pay_count >= $file->pay_limit) {
                return redirect()->back()
                    ->with('alert', 'danger')
                    ->with('message', lang('lang.pay_limit'));
            }

            $inputs = [];
            if ($file->fields) {
                foreach ($file->fields as $input) {
                    if ($input['required'] == 1) {
                        if (!$request->input($input['name'])) {
                            return redirect()->back()
                                ->with('alert', 'danger')
                                ->with('message', lang('lang.entering') . ' ' . $input['label'] . ' ' . lang('lang.is_required'));
                        }
                    }
                    $input['value'] = $request->input($input['name']);
                    array_push($inputs, [
                        'label' => $input['label'],
                        'value' => $request->input($input['name']),
                    ]);
                }
            }

            try {
                return DB::transaction(function () use ($file, $inputs) {
                    $transaction = Transaction::create([
                        'type' => Transaction::$type['file'],
                        'amount' => $file->amount,
                        'details' => [
                            'file_id' => $file->id,
                            'file_fields' => $inputs,
                        ],
                    ]);

                    return redirect()->route('pg-pay', ['id' => $transaction->id]);
                });
            } catch (\Exception $e) {
                return handle_exception($e);
            }
        }

        abort(404);
    }

    public function download(Request $request, $id)
    {
        $file = File::where('id', '=', $id)->where('status', '=', File::$status['active'])->first();
        if (!$file || !$request->token) {
            abort(404);
        }

        $transaction = Transaction::where('id', '=', Crypt::decrypt($request->token))->where('type', '=', Transaction::$type['file'])->where('status', '=', 1)->first();
        if ($transaction && isset($transaction->details['file_id']) && $transaction->details['file_id'] == $id) {
            $dateDiff = date_diff_in_days(new Carbon($transaction->paid_at), Carbon::now());
            if ($file->expire_day >= $dateDiff) {
                return response()->download(storage_path($file->file));
            }
        }

        abort(404);
    }
}
