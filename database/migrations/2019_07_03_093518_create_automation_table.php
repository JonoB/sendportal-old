<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('provider_id');
            $table->string('name');
            $table->string('from_name');
            $table->string('from_email');
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('automation');
    }
}
