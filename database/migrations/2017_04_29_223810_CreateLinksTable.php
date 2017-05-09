<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_urls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('newsletter_id', 36);
            $table->string('original_url');
            $table->mediumInteger('counter')->default(0);
            $table->timestamps();

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
