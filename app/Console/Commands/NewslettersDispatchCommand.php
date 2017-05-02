<?php

namespace App\Console\Commands;

use App\Interfaces\NewsletterRepositoryInterface;
use App\Models\Newsletter;
use App\Models\NewsletterStatus;
use App\Models\Segment;
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

    protected $newsletterRepo;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->newsletterRepo = app()->make(NewsletterRepositoryInterface::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $newsletters = $this->getQueuedNewsletters();

        if ( ! $newsletters)
        {
            $this->info('No queued newsletters; nothing more to do here');
            return;
        }

        $this->info('Number of newsletters in queued status: ' . count($newsletters));

        foreach ($newsletters as $newsletter)
        {

        }
    }

    protected function handleNewsletter(Newsletter $newsletter)
    {
        foreach ($newsletter->segments as $segment)
        {

        }
    }

    protected function handleSegment(Segment $segment)
    {
        foreach ($segment->contacts as $contact) {

        }
    }

    protected function getQueuedNewsletters()
    {
        return $this->newsletterRepo->findBy('status_id', NewsletterStatus::STATUS_QUEUED, ['segments']);
    }
}
