<?php

use App\Models\ProviderType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMailgunProviderType extends Migration
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
            'id' => ProviderType::MAILGUN,
            'name' => 'Mailgun',
            'fields' => [
                'API Key' => 'key',
                'Domain' => 'domain'
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
