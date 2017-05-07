<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactSegmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_segment', function (Blueprint $table) {
            $table->increments('id');
            $table->char('contact_id', 36);
            $table->char('segment_id', 36);
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('segment_id')->references('id')->on('segments');
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
