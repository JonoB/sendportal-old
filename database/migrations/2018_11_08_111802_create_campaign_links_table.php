<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_links', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campaign_id');
            $table->string('identifier')->unique();
            $table->text('link')->nullable();
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamps();

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
        Schema::dropIfExists('campaign_links');
    }
}
