<?php

namespace Tests\Feature;

use App\Interfaces\CampaignRepositoryInterface;
use App\Models\Campaign;
use App\Models\Email;
use App\Models\Template;
use App\Repositories\CampaignEloquentRepository;
use App\Repositories\SegmentTenantRepository;
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
    function a_user_is_redirected_to_the_template_selection_page_when_a_campaign_is_created()
    {
        $this->actingAs($this->user);
        $campaign = factory(Campaign::class)->make();

        $response = $this->post(route('campaigns.store'), $campaign->toArray());
        $campaign = $this->campaignRepository->findBy('name', $campaign->name);

        $response->assertStatus(302);
        $response->assertRedirect(route('campaigns.template.create', $campaign->id));
    }

    /** @test */
    function the_campaign_template_selection_page_lists_all_templates()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();
        factory(Template::class)->create([
            'name' => 'Template 1',
        ]);

        factory(Template::class)->create([
            'name' => 'Template 2',
        ]);

        $response = $this->get(route('campaigns.template.create', $campaign->id));

        $response->assertStatus(200);
        $response->assertSee('Template 1');
        $response->assertSee('Template 2');
    }

    /** @test */
    function a_campaigns_template_can_be_updated()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();
        $template = factory(Template::class)->create();

        $this->put(route('campaigns.template.update', $campaign->id), [
            'template_id' => $template->id
        ]);

        $this->assertEquals($template->id, $campaign->fresh()->template_id);
    }

    /** @test */
    function a_user_is_redirected_to_the_campaign_content_creation_wizard_when_a_campaign_template_is_updated()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();
        $template = factory(Template::class)->create();

        $response = $this->put(route('campaigns.template.update', $campaign->id), [
            'template_id' => $template->id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('campaigns.content.edit', $campaign->id));
    }

    /** @test */
    function the_campaign_content_creation_wizard_can_be_viewed()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->state('withTemplate')->create();

        $response = $this->get(route('campaigns.content.edit', $campaign->id));

        $response->assertStatus(200);
    }

    /** @test */
    function the_campaign_content_creation_wizard_redirects_to_the_template_selection_view_if_no_template_is_selected()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();

        $response = $this->get(route('campaigns.content.edit', $campaign->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('campaigns.template.create', $campaign->id));
    }

    /** @test */
    function the_campaign_content_creation_wizard_uses_the_selected_template()
    {
        $this->actingAs($this->user);

        $template = factory(Template::class)->create([
            'name' => 'Test Template',
            'content' => 'Hello',
        ]);
        $campaign = factory(Campaign::class)->create([
            'template_id' => $template->id,
        ]);

        $response = $this->get(route('campaigns.content.edit', $campaign->id));

        $response->assertStatus(200);
        $response->assertSee('Hello');
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

    /** @test */
    function the_campaign_index_has_a_link_to_the_template_selection_page_if_a_draft_campaign_has_no_template_selected()
    {
        $this->actingAs($this->user);

        factory(Campaign::class)->create();

        $response = $this->get(route('campaigns.index'));

        $response->assertStatus(200);
        $response->assertSee('Select Template');
    }

    /** @test */
    function the_campaign_index_has_a_link_to_the_content_edit_page_if_a_draft_campaign_has_a_template_selected()
    {
        $this->actingAs($this->user);

        factory(Campaign::class)->state('withTemplate')->create();

        $response = $this->get(route('campaigns.index'));

        $response->assertStatus(200);
        $response->assertSee('Edit Content');
    }

    /** @test */
    function if_a_campaign_has_been_sent_the_content_edit_page_redirects_to_the_reports_page()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->states(['sent', 'withTemplate'])->create();

        $response = $this->get(route('campaigns.content.edit', $campaign->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('campaigns.report', $campaign->id));
    }

    /** @test */
    function the_campaign_report_page_can_be_viewed_if_the_campaign_has_been_sent()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->states(['sent', 'withTemplate'])->create();

        $response = $this->get(route('campaigns.report', $campaign->id));

        $response->assertStatus(200);
    }

    /** @test */
    function the_campaign_report_page_cannot_be_viewed_if_the_campaign_has_not_been_sent()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->states(['draft', 'withTemplate'])->create();

        $response = $this->get(route('campaigns.report', $campaign->id));

        $response->assertStatus(302);
    }
}
