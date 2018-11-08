<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UnsubscribeEventType;

class AddUnsubscribedTablesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('unsubscribe_event_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $types = [
            UnsubscribeEventType::BOUNCE => 'Bounce',
            UnsubscribeEventType::COMPLAINT => 'Complaint',
            UnsubscribeEventType::MANUAL => 'Manual',
        ];

        foreach($types as $id => $name)
        {
            \DB::table('unsubscribe_event_types')->insert([
                'id' => $id,
                'name' => $name
            ]);
        }

        \Schema::table('subscribers', function(Blueprint $table)
        {
            $table->timestamp('unsubscribed_at')->nullable()->after('meta');
            $table->unsignedInteger('unsubscribe_event_id')->nullable()->after('unsubscribed_at');

            $table->foreign('unsubscribe_event_id')->references('id')->on('unsubscribe_event_types');
        });

        \Schema::table('campaign_subscriber', function(Blueprint $table)
        {
            $table->timestamp('delivered_at')->nullable()->after('click_count');
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
