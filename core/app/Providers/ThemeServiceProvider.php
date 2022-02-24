<?php

namespace App\Providers;

use App\Config;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $theme = Config::where('key', '=', 'theme')->first();
            if (!$theme) {
                $theme = Config::create([
                    'key' => 'theme',
                    'value' => 'pulse',
                    'label' => 'قالب سایت',
                    'visible' => false,
                ]);
            }

            if (file_exists(base_path('../themes/' . $theme->value . '/langs'))) {
                $this->loadTranslationsFrom(base_path('../themes/' . $theme->value . '/langs'), 'fp');
            }

            $views = [
                base_path('../themes/' . $theme->value . '/views'),
                base_path('resources/views'),
            ];

            /** @var string $views */
            $this->loadViewsFrom($views, 'fp');
        } catch (\Exception $e) {
            //
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
