<?php

namespace Tests\Feature;

use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Campaign;
use App\Models\Template;
use App\Repositories\CampaignEloquentRepository;
use App\Repositories\SegmentEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    private $campaignRepository;

    private $segmentRepository;

    protected function __setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->campaignRepository = $this->app->make(CampaignEloquentRepository::class);
    }

    /** @test */
    function a_campaign_can_have_an_email()
    {
        $campaign = factory(Campaign::class)->create([
            'template_id' => factory(Template::class)->create()->id,
            'subject' => 'A Subject',
            'content' => 'Some content',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
            'track_opens' => true,
            'track_clicks' => true,
            'open_count' => 0,
            'click_count' => 0,
            'status_id' => 1,
        ]);

        $this->assertNotNull($campaign->template_id);
        $this->assertNotNull($campaign->subject);
        $this->assertNotNull($campaign->content);
        $this->assertNotNull($campaign->from_name);
        $this->assertNotNull($campaign->from_email);
        $this->assertNotNull($campaign->track_opens);
        $this->assertNotNull($campaign->track_clicks);
        $this->assertNotNull($campaign->open_count);
        $this->assertNotNull($campaign->click_count);
        $this->assertNotNull($campaign->status);
    }

    /** @test */
    function an_email_is_created_when_a_campaign_is_stored()
    {
        $emailData = [
            'template_id' => factory(Template::class)->create()->id,
            'subject' => 'A Subject',
            'content' => 'Some content',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
            'track_opens' => true,
            'track_clicks' => true,
            'open_count' => 0,
            'click_count' => 0,
            'status_id' => 1,
        ];

        $campaign = factory(Campaign::class)->make($emailData);

        $this->post(route('campaigns.store'), $campaign->toArray());

        $createdCampaign = $this->campaignRepository->all();

        $this->assertContains($emailData, $campaign->email);
    }
}
