<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;

class SecuritySettingController extends Controller
{
    public function index()
    {
        return view('fp::admin.security-settings.index')
            ->with('activeMenu', 'security-settings');
    }

    public function changePassword(Request $request)
    {
        if (app('site_configs')['APP_ENV'] === 'demo') {
            return redirect()->route('admin-dashboard')
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $rules = [
            'current_password' => 'required',
            'password' => 'min:6|confirmed'
        ];
        $this->validate($request, $rules);

        $user = User::find($request->user()->id);
        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);

            return redirect()->back()
                ->with('alert', 'success')
                ->with('message', lang('lang.changes_saved'));
        }

        return redirect()->back()
            ->with('alert', 'danger')
            ->with('message', lang('lang.invalid_password'));
    }
}
