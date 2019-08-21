<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountsToAutomationSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('automation_steps', function (Blueprint $table) {
            $table->unsignedInteger('open_count')->default(0)->after('content');
            $table->unsignedInteger('click_count')->default(0)->after('open_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('automation_steps', function (Blueprint $table) {
            $table->dropColumn('open_count');
            $table->dropColumn('click_count');
        });
    }
}
