<?php

use Illuminate\Database\Seeder;

class NewsletterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('newsletter_statuses')->insert([
            ['name' => 'Draft'],
            ['name' => 'Queued'],
            ['name' => 'Sending'],
            ['name' => 'Sent'],
        ]);
    }
}
