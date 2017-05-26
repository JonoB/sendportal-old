<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsletterSubscriberListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_subscriber_list', function (Blueprint $table) {
            $table->increments('id');
            $table->char('subscriber_list_id', 36);
            $table->char('newsletter_id', 36);
            $table->timestamps();

            $table->foreign('subscriber_list_id')->references('id')->on('subscriber_lists');
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
