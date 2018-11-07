<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriberTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->char('subscriber_id', 36);
            $table->char('tag_id', 36);
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriber_tag');
    }
}
