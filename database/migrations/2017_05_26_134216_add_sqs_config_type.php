<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ConfigType;

class AddSqsConfigType extends Migration
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
            'id' => ConfigType::AWS_SNS,
            'name' => 'AWS SQS',
            'fields' => [
                'AWS access key' => 'key',
                'AWS secret access key' => 'secret',
                'AWS Region' => 'region',
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
