<?php

use App\Models\ProviderType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendgridProviderType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ProviderType::unguard();

        ProviderType::create([
            'id' => ProviderType::SENDGRID,
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
