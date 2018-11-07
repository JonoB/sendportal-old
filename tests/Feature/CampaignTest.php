<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Template;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    protected function __setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
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
}
