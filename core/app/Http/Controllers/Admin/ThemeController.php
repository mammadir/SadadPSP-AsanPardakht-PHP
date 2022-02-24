<?php

namespace App\Http\Controllers\Admin;

use App\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = [];
        $themesDirectories = glob(base_path('../themes') . '/*', GLOB_ONLYDIR);
        foreach ($themesDirectories as $directory) {
            if (file_exists($directory . '/theme.json')) {
                $theme = file_get_contents($directory . '/theme.json');
                $theme = json_decode($theme, true);
                if (isset($theme['slug']) && isset($theme['name']) && isset($theme['version']) && isset($theme['author'])) {
                    if (file_exists($directory . '/screenshot.jpg')) {
                        $theme['screenshot'] = asset('themes/' . $theme['slug'] . '/screenshot.jpg');
                    }
                    array_push($themes, $theme);
                }
            }
        }

        return view('fp::admin.theme.index')
            ->with('activeMenu', 'themes')
            ->with('themes', $themes);
    }

    public function update($slug)
    {
        if (file_exists(base_path('../themes/' . $slug) . '/theme.json')) {
            Config::where('key', '=', 'theme')->update([
                'value' => $slug,
            ]);

            return redirect()->back()
                ->with('alert', 'success')
                ->with('message', lang('lang.theme_changed'));
        }

        return redirect()->back()
            ->with('alert', 'danger')
            ->with('message', lang('lang.theme_not_found'));
    }

    public function installFromSource(Request $request)
    {
        if (app('site_configs')['APP_ENV'] == 'demo') {
            return redirect()->back()
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $rules = [
            'file' => 'required|mimes:zip',
        ];
        $this->validate($request, $rules);

        $file = $request->file('file');

        try {
            if (file_exists(base_path('../tmp'))) {
                File::deleteDirectory(base_path('../tmp'));
            }
            mkdir(base_path('../tmp'));
            $file->move(base_path('../tmp/'), $file->getClientOriginalName());
            $zip = new ZipArchive;
            if ($zip->open(base_path('/../tmp/' . $file->getClientOriginalName()))) {
                $zip->extractTo(base_path('/../tmp'));
                $zip->close();
            }

            $dirs = scandir(base_path('../tmp'));
            if (count($dirs) > 2) {
                $themeSrc = base_path('../tmp/' . $dirs[2]);
                $theme = file_get_contents($themeSrc . '/theme.json');
                $theme = json_decode($theme, true);
                if (isset($theme['slug']) && isset($theme['name']) && isset($theme['version']) && isset($theme['author'])) {
                    if (file_exists(base_path('../themes/' . $dirs[2]))) {
                        $this->rollbackInstallFromSource();

                        return redirect()->back()
                            ->with('alert', 'danger')
                            ->with('message', lang('lang.theme_already_installed'));
                    }

                    rename($themeSrc, base_path('../themes/' . $dirs[2]));
                    $this->rollbackInstallFromSource();

                    Config::where('key', '=', 'theme')->update([
                        'value' => $theme['slug'],
                    ]);

                    return redirect()->back()
                        ->with('alert', 'success')
                        ->with('message', lang('lang.theme_installed'));
                }
            }
        } catch (\Exception $e) {
            $this->rollbackInstallFromSource();

            return handle_exception($e);
        }

        $this->rollbackInstallFromSource();

        return redirect()->back()
            ->with('alert', 'danger')
            ->with('message', lang('lang.theme_not_installed'));
    }

    private function rollbackInstallFromSource()
    {
        if (file_exists(base_path('../tmp'))) {
            File::deleteDirectory(base_path('../tmp'));
        }
    }
}
