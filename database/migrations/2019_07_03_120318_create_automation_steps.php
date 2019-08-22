<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomationSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automation_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('automation_id');
            $table->unsignedInteger('template_id');
            $table->string('delay_type');
            $table->smallInteger('delay');
            $table->unsignedInteger('delay_seconds')->index();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->unsignedInteger('open_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamps();

            $table->foreign('automation_id')->references('id')->on('automations');
            $table->foreign('template_id')->references('id')->on('templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('automation_steps');
    }
}
