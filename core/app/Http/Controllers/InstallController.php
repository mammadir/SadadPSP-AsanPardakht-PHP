<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\User;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    public function index()
    {
        if (!$this->isAllowed()) {
            return redirect()->route('login');
        }

        return view('install.index');
    }

    public function install(Request $request)
    {
        if (!$this->isAllowed()) {
            return redirect()->route('login');
        }

        $rules = [
            'site_url' => 'required|url',
            'site_title' => 'required|max:255',
            'site_description' => 'required|max:255',
            'db_host' => 'required',
            'db_name' => 'required',
            'db_username' => 'required',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:6',
        ];
        $this->validate($request, $rules);

        $sampleConfig = require(base_path('config-sample.php'));
        foreach ($sampleConfig as $key => $value) {
            $sampleConfig[$key] = '"' . $value . '",';
        }
        $sampleConfig['APP_URL'] = '"' . $request->site_url . '",';
        $sampleConfig['DB_HOST'] = '"' . $request->db_host . '",';
        $sampleConfig['DB_DATABASE'] = '"' . $request->db_name . '",';
        $sampleConfig['DB_USERNAME'] = '"' . $request->db_username . '",';
        $sampleConfig['DB_PASSWORD'] = '"' . $request->db_password . '",';

        $config = print_r($sampleConfig, true);
        $config = str_replace("[", '"', $config);
        $config = str_replace("]", '"', $config);

        file_put_contents(base_path('config.php'), '<?php return ' . $config . ';');

        return redirect()->route('install-complete')
            ->withInput($request->all());
    }

    public function showComplete()
    {
        if (!$this->isAllowed()) {
            return redirect()->route('login');
        }

        return view('install.complete');
    }

    public function complete(Request $request)
    {
        if (!$this->isAllowed()) {
            return redirect()->route('login');
        }

        $rules = [
            'site_url' => 'required|url',
            'site_title' => 'required|max:255',
            'site_description' => 'required|max:255',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:6',
        ];
        $this->validate($request, $rules);

        try {
            Artisan::call('migrate:refresh', ['--force' => '--force']);
            Artisan::call('key:generate');

            return DB::transaction(function () use ($request) {
                User::create([
                    'name' => 'مدیر سیستم',
                    'email' => $request->admin_email,
                    'password' => bcrypt($request->admin_password),
                ]);

                Config::create([
                    'key' => 'site_url',
                    'value' => $request->site_url,
                    'label' => 'آدرس سایت',
                ]);
                Config::create([
                    'key' => 'site_title',
                    'value' => $request->site_title,
                    'label' => 'عنوان سایت',
                ]);
                Config::create([
                    'key' => 'site_description',
                    'value' => $request->site_description,
                    'label' => 'توضیحات سایت',
                ]);

                Artisan::call('db:seed', ['--force' => '--force']);

                return redirect()->to('login');
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('install')
                ->with('alert', 'danger')
                ->with('message', $e->getMessage());
        }
    }

    public function completeEZInstallation(Request $request)
    {
        if (file_exists(base_path('../install.php'))) {
            unlink(base_path('../install.php'));
        }

        if (file_exists(base_path('../install.css'))) {
            unlink(base_path('../install.css'));
        }

        if (file_exists(base_path('../latest.zip'))) {
            unlink(base_path('../latest.zip'));
        }

        if (!$this->isAllowed()) {
            return redirect()->route('login');
        }

        try {
            Artisan::call('migrate:refresh', ['--force' => '--force']);
            Artisan::call('key:generate');

            return DB::transaction(function () use ($request) {
                User::create([
                    'name' => 'مدیر سیستم',
                    'email' => $request->admin_email,
                    'password' => bcrypt($request->admin_password),
                ]);

                Config::create([
                    'key' => 'site_url',
                    'value' => $request->site_url,
                    'label' => 'آدرس سایت',
                ]);
                Config::create([
                    'key' => 'site_title',
                    'value' => $request->site_title,
                    'label' => 'عنوان سایت',
                ]);
                Config::create([
                    'key' => 'site_description',
                    'value' => '',
                    'label' => 'توضیحات سایت',
                ]);

                Artisan::call('db:seed', ['--force' => '--force']);

                return redirect()->to('login');
            });
        } catch (\Exception $e) {
            return redirect()->route('install')
                ->with('alert', 'danger')
                ->with('message', $e->getMessage());
        }
    }

    private function isAllowed()
    {
        try {
            if (site_config('site_url')) {
                return false;
            }
        } catch (\Exception $e) {
            //
        }

        return true;
    }
}
