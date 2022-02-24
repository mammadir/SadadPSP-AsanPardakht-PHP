<?php

use Illuminate\Database\Seeder;
use App\Form;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Form::create([
            'title' => 'فرم پرداخت‌',
            'description' => 'توضیحاتی در مورد فرم پرداخت',
            'fields' => [
                [
                    'name' => 'input_0',
                    'label' => 'نام و نام خانوادگی‌',
                    'required' => 1
                ],
                [
                    'name' => 'input_1',
                    'label' => 'شماره موبایل‌',
                    'required' => 0
                ],
            ],
            'default' => 1
        ]);
    }
}
