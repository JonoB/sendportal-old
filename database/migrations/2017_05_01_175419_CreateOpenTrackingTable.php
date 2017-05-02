<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_opens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('newsletter_id');
            $table->smallInteger('counter')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts');
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
