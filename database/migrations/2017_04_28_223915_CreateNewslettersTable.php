<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('list_id');
            $table->unsignedInteger('template_id');
            $table->unsignedInteger('newsletter_status_id');
            $table->string('name');
            $table->string('subject');
            $table->text('content');
            $table->string('from_name');
            $table->string('from_email');
            $table->boolean('track_opens');
            $table->boolean('track_links');
            $table->timestamp('scheduled_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
