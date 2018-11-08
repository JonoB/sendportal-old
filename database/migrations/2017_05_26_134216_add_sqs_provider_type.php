<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ProviderType;

class AddSqsProviderType extends Migration
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
            'id' => ProviderType::AWS_SNS,
            'name' => 'AWS SQS',
            'fields' => [
                'AWS Access Key' => 'key',
                'AWS Secret Access Key' => 'secret',
                'AWS Region' => 'region',
                'Configuration Set Name' => 'configuration_set_name',
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
