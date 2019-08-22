<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('mailable_id')->index();
            $table->string('mailable_type');
            $table->unsignedInteger('template_id')->nullable();
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->mediumInteger('sent_count')->nullable()->default(0);
            $table->mediumInteger('open_count')->nullable()->default(0);
            $table->mediumInteger('click_count')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steps');
    }
}
