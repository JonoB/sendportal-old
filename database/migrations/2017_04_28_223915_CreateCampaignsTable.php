<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('template_id', 36)->nullable();
            $table->unsignedInteger('status_id')->default(1);
            $table->string('name');
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->unsignedInteger('config_id');
            $table->boolean('track_opens')->default(1);
            $table->boolean('track_clicks')->default(1);
            $table->mediumInteger('sent_count')->nullable()->default(0);
            $table->mediumInteger('open_count')->nullable()->default(0);
            $table->mediumInteger('click_count')->nullable()->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('templates');
            $table->foreign('status_id')->references('id')->on('campaign_statuses');
            $table->foreign('config_id')->references('id')->on('configs');
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
