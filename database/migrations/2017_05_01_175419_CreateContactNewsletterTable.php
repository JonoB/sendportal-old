<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactNewsletterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_newsletter', function (Blueprint $table) {
            $table->increments('id');
            $table->char('contact_id', 36);
            $table->char('newsletter_id', 36);
            $table->string('ip')->nullable();
            $table->smallInteger('open_count')->default(0);
            $table->smallInteger('click_count')->default(0);
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts');
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
