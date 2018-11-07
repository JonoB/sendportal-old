<?php

use App\Models\ConfigType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendgridConfigType extends Migration
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
            'id' => ConfigType::SENDGRID,
            'name' => 'SendGrid',
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
