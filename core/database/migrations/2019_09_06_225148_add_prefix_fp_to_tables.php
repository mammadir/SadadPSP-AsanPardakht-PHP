<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrefixFpToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('users', 'fp_users');
        Schema::rename('password_resets', 'fp_password_resets');
        Schema::rename('configs', 'fp_configs');
        Schema::rename('transactions', 'fp_transactions');
        Schema::rename('forms', 'fp_forms');
        Schema::rename('factors', 'fp_factors');
        Schema::rename('files', 'fp_files');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('fp_users', 'users');
        Schema::rename('fp_password_resets', 'password_resets');
        Schema::rename('fp_configs', 'configs');
        Schema::rename('fp_transactions', 'transactions');
        Schema::rename('fp_forms', 'forms');
        Schema::rename('fp_factors', 'factors');
        Schema::rename('fp_files', 'files');
    }
}
