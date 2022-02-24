<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Factor;
use Illuminate\Support\Facades\DB;

class FactorController extends Controller
{
    public function index()
    {
        $factors = Factor::where('status', '=', Factor::$status['active'])->orderBy('id', 'desc')->paginate(15);

        return view('fp::admin.factors.index')
            ->with('activeMenu', 'factors')
            ->with('factors', $factors);
    }

    public function filter(Request $request)
    {
        if ($request->id) {
            $factors = Factor::where(function ($query) use ($request) {
                if ($request->id) {
                    $query->where('id', '=', $request->id);
                }
            })->orderBy('id', 'desc')->paginate(15);
        } else {
            $factors = Factor::where('status', '=', Factor::$status['active'])->orderBy('id', 'desc')->paginate(15);
        }

        return view('fp::admin.factors.index')
            ->with('activeMenu', 'factors')
            ->with('factors', $factors)
            ->with('inputs', $request->all());
    }

    public function showAdd()
    {
        return view('fp::admin.factors.add')
            ->with('activeMenu', 'factors');
    }

    public function add(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'tax' => 'required|numeric|min:0|max:100',
        ];
        $this->validate($request, $rules);

        if (count($request->items_name) && $request->items_name[0]) {
            try {
                return DB::transaction(function () use ($request) {
                    $items = [];
                    foreach ($request->items_name as $key => $item) {
                        if ($item) {
                            array_push($items, [
                                'key' => 'item_' . $key,
                                'name' => $item,
                                'count' => $request->items_count[$key],
                                'price' => $request->items_price[$key],
                                'description' => $request->items_description[$key],
                            ]);
                        }
                    }

                    $amount = 0;
                    foreach ($items as $item) {
                        $amount += ($item['price'] * $item['count']);
                    }

                    Factor::create([
                        'title' => $request->title,
                        'amount' => $amount + (($amount * $request->tax) / 100),
                        'tax' => $request->tax,
                        'items' => $items,
                    ]);

                    return redirect()->back()
                        ->with('alert', 'success')
                        ->with('message', lang('lang.added'));
                });
            } catch (\Exception $e) {
                return handle_exception($e);
            }
        }

        return redirect()->back()
            ->with('alert', 'danger')
            ->with('message', lang('lang.factor_min_items'));
    }

    public function showEdit(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_factors,id',
        ];
        $this->validate($request, $rules);

        $factor = Factor::find($id);

        return view('fp::admin.factors.edit')
            ->with('activeMenu', 'factors')
            ->with('factor', $factor);
    }

    public function edit(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_factors,id',
            'title' => 'required|max:255',
            'tax' => 'required|numeric|min:0|max:100',
        ];
        $this->validate($request, $rules);

        try {
            return DB::transaction(function () use ($request, $id) {
                $factor = Factor::find($id);

                $items = [];
                foreach ($request->items_name as $key => $item) {
                    if ($item) {
                        array_push($items, [
                            'key' => 'item_' . $key,
                            'name' => $item,
                            'count' => $request->items_count[$key],
                            'price' => $request->items_price[$key],
                            'description' => $request->items_description[$key],
                        ]);
                    }
                }

                $amount = 0;
                foreach ($items as $item) {
                    $amount += ($item['price'] * $item['count']);
                }

                $factor->update([
                    'title' => $request->title,
                    'amount' => $amount + (($amount * $request->tax) / 100),
                    'tax' => $request->tax,
                    'items' => $items,
                ]);

                return redirect()->route('admin-factors')
                    ->with('alert', 'success')
                    ->with('message', lang('lang.changes_saved'));
            });
        } catch (\Exception $e) {
            return handle_exception($e);
        }
    }

    public function delete(Request $request, $id)
    {
        if (app('site_configs')['APP_ENV'] === 'demo') {
            return redirect()->back()
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_factors,id',
        ];
        $this->validate($request, $rules);

        $factor = Factor::find($id);

        if ($factor->default) {
            return redirect()->back()
                ->with('alert', 'danger')
                ->with('message', lang('lang.cannot_delete_default_factor'));
        }

        $factor->update([
            'status' => Factor::$status['deleted'],
        ]);

        return redirect()->back()
            ->with('alert', 'success')
            ->with('message', lang('lang.changes_saved'));
    }
}
