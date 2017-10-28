<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignSubscriberListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_subscriber_list', function (Blueprint $table) {
            $table->increments('id');
            $table->char('subscriber_list_id', 36);
            $table->char('campaign_id', 36);
            $table->timestamps();

            $table->foreign('subscriber_list_id')->references('id')->on('subscriber_lists');
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
        //
    }
}
