<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignSubscriberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_subscriber', function (Blueprint $table) {
            $table->increments('id');
            $table->char('subscriber_id', 36);
            $table->char('campaign_id', 36);
            $table->string('ip')->nullable();
            $table->smallInteger('open_count')->default(0);
            $table->smallInteger('click_count')->default(0);
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers');
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
