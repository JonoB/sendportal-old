<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('provider_types', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->mediumText('fields');
            $table->timestamps();
        });

        \Schema::create('providers', function($table)
        {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->unsignedInteger('type_id');
            $table->mediumText('settings');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('provider_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
        Schema::dropIfExists('provider_types');
    }
}
