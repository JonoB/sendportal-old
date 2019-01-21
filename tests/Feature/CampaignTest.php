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

    /**
     * @var CampaignRepositoryInterface
     */
    private $campaignRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->campaignRepository = $this->app->make(CampaignEloquentRepository::class);
    }

    /** @test */
    function the_campaign_index_can_be_viewed()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('campaigns.index'));

        $response->assertStatus(200);
    }

    /** @test */
    function unauthenticated_users_can_not_view_the_campaign_index()
    {
        $response = $this->get(route('campaigns.index'));

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function campaigns_can_be_created()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->make();

        $this->post(route('campaigns.store'), $campaign->toArray());

        $this->assertDatabaseHas('campaigns', ['name' => $campaign->name]);
    }

    /** @test */
    function an_unauthenticated_user_cannot_create_a_campaign()
    {
        $campaign = factory(Campaign::class)->make();

        $response = $this->post(route('campaigns.store'), $campaign->toArray());

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function campaigns_can_be_updated()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();

        $modifiedData = [
            'name' => 'New Name',
            'subject' => $campaign->subject,
            'from_name' => $campaign->from_name,
            'from_email' => $campaign->from_email,
            'provider_id' => $campaign->provider_id,
        ];

        $this->put(route('campaigns.update', $campaign->id), $modifiedData);

        $this->assertDatabaseHas('campaigns', ['name' => $modifiedData['name']]);
    }

    /** @test */
    function an_unauthenticated_user_cannot_update_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        $modifiedData = [
            'name' => 'New Name',
            'subject' => $campaign->subject,
            'from_name' => $campaign->from_name,
            'from_email' => $campaign->from_email,
            'provider_id' => $campaign->provider_id,
        ];

        $response = $this->put(route('campaigns.update', $campaign->id), $modifiedData);

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function a_user_is_redirected_to_the_campaign_content_creation_wizard_when_a_campaign_is_created()
    {
        $this->actingAs($this->user);
        $campaign = factory(Campaign::class)->make();

        $response = $this->post(route('campaigns.store'), $campaign->toArray());
        $campaign = $this->campaignRepository->findBy('name', $campaign->name);

        $response->assertStatus(302);
        $response->assertRedirect(route('campaigns.content.edit', $campaign->id));
    }

    /** @test */
    function the_campaign_content_creation_wizard_can_be_viewed()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();

        $response = $this->get(route('campaigns.content.edit', $campaign->id));

        $response->assertStatus(200);
    }

    /** @test */
    function an_authenticated_user_cannot_view_the_campaign_email_create_view()
    {
        $campaign = factory(Campaign::class)->create();

        $response = $this->get(route('campaigns.content.edit', $campaign->id));

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function the_campaign_confirmation_page_can_be_viewed()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->state('withContent')->create();

        $response = $this->get(route('campaigns.confirm', $campaign->id));

        $response->assertStatus(200);
    }
}
