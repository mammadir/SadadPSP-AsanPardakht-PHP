<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Config;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = Config::where('visible', '=', 1)->get();

        return view('fp::admin.configs.index')
            ->with('activeMenu', 'configs')
            ->with('configs', $configs);
    }

    public function edit(Request $request)
    {
        if (app('site_configs')['APP_ENV'] === 'demo') {
            return redirect()->back()
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $inputs = $request->input();

        foreach ($inputs as $key => $value) {
            Config::where('key', '=', $key)->update([
                'value' => $value,
            ]);
        }

        return redirect()->back()
            ->with('alert', 'success')
            ->with('message', lang('lang.changes_saved'));
    }

    public function scripts(Request $request)
    {
        if (app('site_configs')['APP_ENV'] === 'demo') {
            return redirect()->back()
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $scripts = Config::where('key', '=', 'scripts')->first();
        if (!$scripts) {
            $scripts = Config::create(['key' => 'scripts', 'value' => '', 'visible' => 0]);
        }

        $scripts->update(['value' => $request->scripts, 'visible' => 0]);

        return redirect()->back()
            ->with('alert', 'success')
            ->with('message', lang('lang.changes_saved'));
    }

    public function styles(Request $request)
    {
        if (app('site_configs')['APP_ENV'] === 'demo') {
            return redirect()->back()
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $styles = Config::where('key', '=', 'styles')->first();
        if (!$styles) {
            $styles = Config::create(['key' => 'styles', 'value' => '', 'visible' => 0]);
        }

        $styles->update(['value' => $request->styles, 'visible' => 0]);

        return redirect()->back()
            ->with('alert', 'success')
            ->with('message', lang('lang.changes_saved'));
    }
}
