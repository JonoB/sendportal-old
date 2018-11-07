<?php

namespace Tests\Feature;

use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Campaign;
use App\Models\Template;
use App\Repositories\CampaignEloquentRepository;
use App\Repositories\SegmentEloquentRepository;
use App\User;
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

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->campaignRepository = $this->app->make(CampaignEloquentRepository::class);
    }

    /** @test */
    function an_email_is_created_when_a_campaign_is_stored()
    {
        $this->actingAs($this->user);

        $emailData = [
            'template_id' => factory(Template::class)->create()->id,
            'subject' => 'A Subject',
            'content' => 'Some content',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
            'track_opens' => 1,
            'track_clicks' => 1,
            'open_count' => 0,
            'click_count' => 0,
            'status_id' => 1,
        ];

        $campaign = factory(Campaign::class)->make($emailData);

        $this->post(route('campaigns.store'), $campaign->toArray());

        $createdCampaign = $this->campaignRepository->all()->first();

        foreach($emailData as $key => $value)
        {
            $this->assertEquals($createdCampaign->email->first()->$key, $emailData[$key]);
        }
    }
}
