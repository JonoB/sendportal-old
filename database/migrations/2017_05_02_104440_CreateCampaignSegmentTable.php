<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignSegmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_segment', function (Blueprint $table) {
            $table->increments('id');
            $table->char('segment_id', 36);
            $table->char('campaign_id', 36);
            $table->timestamps();

            $table->foreign('segment_id')->references('id')->on('segments');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_segment');
    }
}
