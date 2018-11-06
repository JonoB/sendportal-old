<?php

use Illuminate\Database\Seeder;

class CampaignStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('campaign_statuses')->insert([
            ['name' => 'Draft'],
            ['name' => 'Queued'],
            ['name' => 'Sending'],
            ['name' => 'Sent'],
        ]);
    }
}
