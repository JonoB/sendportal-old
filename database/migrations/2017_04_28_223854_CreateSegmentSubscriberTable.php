<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegmentSubscriberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segment_subscriber', function (Blueprint $table) {
            $table->increments('id');
            $table->char('segment_id', 36);
            $table->char('subscriber_id', 36);
            $table->timestamp('unsubscribed_at')->nullable()->index();
            $table->timestamps();

            $table->foreign('segment_id')->references('id')->on('segments');
            $table->foreign('subscriber_id')->references('id')->on('subscribers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriber_segment');
    }
}
