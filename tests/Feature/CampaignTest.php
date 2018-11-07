<?php

namespace Tests\Feature;

use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Campaign;
use App\Models\Email;
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
    function an_authenticated_user_can_create_a_campaign()
    {
        $this->actingAs($this->user);

        $emailData = [
            'subject' => 'A Subject',
            'content' => 'Some content',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
        ];

        $campaign = factory(Campaign::class)->make($emailData);

        $this->post(route('campaigns.store'), $campaign->toArray());

        $this->assertDatabaseHas('campaigns', ['name' => $campaign->name]);
        $this->assertDatabaseHas('emails', $emailData);
    }

    /** @test */
    function an_unauthenticated_user_cannot_create_a_campaign()
    {
        $emailData = [
            'subject' => 'A Subject',
            'content' => 'Some content',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
        ];

        $campaign = factory(Campaign::class)->make($emailData);

        $response = $this->post(route('campaigns.store'), $campaign->toArray());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_update_a_campaign()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();

        factory(Email::class)->create([
            'mailable_id' => $campaign->id,
            'mailable_type' => 'App\Models\Campaign',
            'subject' => 'A Subject',
            'content' => 'Some content.',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
        ]);

        $modifiedData = [
            'name' => 'New Name',
            'subject' => 'New Subject',
            'from_name' => 'New From Name',
            'from_email' => 'from@newemail.io',
        ];

        $this->put(route('campaigns.update', ['id' => $campaign->id]), $modifiedData);

        $this->assertDatabaseHas('campaigns', ['name' => $modifiedData['name']]);
        $this->assertDatabaseHas('emails', array_except($modifiedData, ['name']));
    }

    /** @test */
    function an_unauthenticated_user_cannot_update_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        factory(Email::class)->create([
            'mailable_id' => $campaign->id,
            'mailable_type' => 'App\Models\Campaign',
            'subject' => 'A Subject',
            'content' => 'Some content.',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
        ]);

        $modifiedData = [
            'name' => 'New Name',
            'subject' => 'New Subject',
            'from_name' => 'New From Name',
            'from_email' => 'from@newemail.io',
        ];

        $response = $this->put(route('campaigns.update', ['id' => $campaign->id]), $modifiedData);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
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

        foreach ($emailData as $key => $value)
        {
            $this->assertEquals($createdCampaign->email->$key, $emailData[$key]);
        }
    }
}
