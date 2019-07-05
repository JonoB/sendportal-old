<?php

namespace App\Console\Commands;

use App\Events\AutomationDispatch;
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
        foreach ($this->getAutomationSchedules() as $schedule)
        {
            $this->info('Dispatching schedule ID:' . $schedule->id);

            event(new AutomationDispatch($schedule));
        }
    }

    protected function getAutomationSchedules()
    {
        return AutomationSchedule::where('scheduled_at', '<=', now())
            ->whereNull('started_at')
            ->get();
    }
}
