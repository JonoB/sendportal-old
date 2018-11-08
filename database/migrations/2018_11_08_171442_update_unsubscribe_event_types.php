<?php

use App\Models\UnsubscribeEventType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUnsubscribeEventTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UnsubscribeEventType::where('id', UnsubscribeEventType::MANUAL_BY_ADMIN)
            ->update(['name' => 'Manual By Admin']);

        DB::table('unsubscribe_event_types')->insert([
            'id' => UnsubscribeEventType::MANUAL_BY_SUBSCRIBER,
            'name' => 'Manual By Subscriber'
        ]);
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
