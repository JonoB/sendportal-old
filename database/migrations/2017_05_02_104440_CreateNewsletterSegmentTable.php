<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsletterSegmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_segment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('segment_id');
            $table->unsignedInteger('newsletter_id');
            $table->timestamps();

            $table->foreign('segment_id')->references('id')->on('segments');
            $table->foreign('newsletter_id')->references('id')->on('newsletters');
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
