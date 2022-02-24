<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Form;
use App\Transaction;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index($id = null)
    {
        if ($id) {
            $form = Form::where('id', '=', $id)->where('status', '=', Form::$status['active'])->first();

            if ($form) {
                return view('fp::home.form.index')
                    ->with('form', $form);
            }

            abort(404);
        }

        $form = Form::where('default', '=', 1)->where('status', '=', Form::$status['active'])->first();

        if ($form) {
            return view('fp::home.form.index')
                ->with('form', $form);
        }

        abort(404);
    }

    public function pay(Request $request, $id = null)
    {
        if ($id) {
            $form = Form::where('id', '=', $id)->where('status', '=', Form::$status['active'])->first();
            if (!$form) {
                abort(404);
            }
        }

        if (!isset($form)) {
            $form = Form::where('default', '=', 1)->where('status', '=', Form::$status['active'])->first();
        }

        if ($form) {
            if ($form->pay_limit && $form->pay_count >= $form->pay_limit) {
                return redirect()->back()
                    ->with('alert', 'danger')
                    ->with('message', lang('lang.pay_limit'));
            }

            if ($form->amount) {
                $amount = persian_number_to_latin($form->amount);
            } else {
                $amount = $request->amount;
                $request->request->remove('amount');
                $request->request->add(['amount' => persian_number_to_latin(str_replace(',', '', $amount))]);
                $rules = [
                    'amount' => 'required|numeric|greater_than_rial:1000',
                ];
                $this->validate($request, $rules);
                $amount = $request->amount;
            }

            $inputs = [];
            if ($form->fields) {
                foreach ($form->fields as $input) {
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
                return DB::transaction(function () use ($form, $amount, $inputs) {
                    $transaction = Transaction::create([
                        'type' => Transaction::$type['form'],
                        'amount' => $amount,
                        'details' => [
                            'form_id' => $form->id,
                            'form_fields' => $inputs,
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
}
