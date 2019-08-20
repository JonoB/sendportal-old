<?php

namespace App\Console\Commands;

use App\Events\AutomationDispatchEvent;
use App\Models\AutomationSchedule;
use App\Models\AutomationStep;
use Illuminate\Console\Command;

class AutomationDispatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:automations:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch automations to queue workers';

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
        $count = 0;

        foreach ($this->getAutomationSchedules() as $schedule)
        {
            $message = 'Dispatching schedule id=' . $schedule->id;

            $this->info($message);
            \Log::info($message);
            $count++;

            event(new AutomationDispatchEvent($schedule));
        }

        $message = 'Dispatched messages count=' . $count;
        $this->info($message);
        \Log::info($message);
    }

    protected function getAutomationSchedules()
    {
        return AutomationSchedule::where('scheduled_at', '<=', now())
            ->whereNull('started_at')
            ->take(10000)
            ->get();
    }
}
