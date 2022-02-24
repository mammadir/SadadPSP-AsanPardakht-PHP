<?php

use Illuminate\Database\Seeder;
use App\Config;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::create([
            'key' => 'sadad_merchent',
            'value' => '',
            'label' => 'شماره پذیرنده'
        ]);
		
		Config::create([
            'key' => 'sadad_terminal',
            'value' => '',
            'label' => 'شماره ترمینال'
        ]);
		
		Config::create([
            'key' => 'sadad_api_keys',
            'value' => '',
            'label' => 'کلید ترمینال'
        ]);
		
		
        Config::create([
            'key' => 'live_stats',
            'value' => false,
            'label' => lang('lang.live_stats'),
            'visible' => 0
        ]);
    }
}
