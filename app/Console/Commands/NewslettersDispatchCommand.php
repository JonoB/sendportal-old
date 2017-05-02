<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NewslettersDispatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:newsletters:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch all newsletters waiting in the queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
