<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsletterListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_newsletter', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('list_id')->nullable();
            $table->unsignedInteger('newsletter_id')->nullable();
            $table->timestamps();

            $table->foreign('list_id')->references('id')->on('lists');
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
