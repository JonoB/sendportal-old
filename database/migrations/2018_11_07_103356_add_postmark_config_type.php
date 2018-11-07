<?php

use App\Models\ConfigType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostmarkConfigType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ConfigType::unguard();

        ConfigType::create([
            'id' => ConfigType::POSTMARK,
            'name' => 'Mailgun',
            'fields' => [
                'API Key' => 'key',
            ]
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
