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
    function the_campaign_index_tells_you_if_a_campaign_does_not_have_an_email()
    {
        $this->actingAs($this->user);

        factory(Campaign::class)->create();

        $response = $this->get(route('campaigns.index'));

        $response->assertSee('No Email');
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
        $emailData = [
            'subject' => 'A Subject',
            'content' => 'Some content',
            'from_name' => 'Josh',
            'from_email' => 'josh@mettle.io',
        ];

        $campaign = factory(Campaign::class)->make($emailData);

        $response = $this->post(route('campaigns.store'), $campaign->toArray());

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function campaigns_can_be_updated()
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

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function a_user_is_redirected_to_the_email_creation_wizard_when_a_campaign_is_created()
    {
        $this->actingAs($this->user);
        $campaign = factory(Campaign::class)->make();

        $response = $this->post(route('campaigns.store'), $campaign->toArray());
        $campaign = $this->campaignRepository->findBy('name', $campaign->name);

        $response->assertStatus(302);
        $response->assertRedirect("/campaigns/{$campaign->id}/emails/create");
    }

    /** @test */
    function a_campaign_can_have_one_emails()
    {
        $automation = factory(Campaign::class)->create();

        $email = [
            'subject' => 'Test Email 1',
            'from_email' => 'test1@email.com',
            'from_name' => 'Test 1',
        ];

        $automation->email()->create($email);

        foreach ($email as $key => $value)
        {
            $this->assertEquals($automation->email->toArray()[$key], $value);
        }
    }

    /** @test */
    function the_campaign_email_create_view_can_viewed()
    {
        $this->actingAs($this->user);

        $campaign = factory(Campaign::class)->create();

        $response = $this->get(route('campaigns.emails.create', ['id' => $campaign->id]));

        $response->assertStatus(200);
    }

    /** @test */
    function an_authenticated_user_cannot_view_the_campaign_email_create_view()
    {
        $campaign = factory(Campaign::class)->create();

        $response = $this->get(route('campaigns.emails.create', ['id' => $campaign->id]));

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function a_user_can_create_an_email_for_a_campaign()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->user);

        $automation = factory(Campaign::class)->create();

        $emailData = [
            'subject' => 'Test Email',
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $this->post(route('campaigns.emails.store', [$automation->id]), $emailData);

        $this->assertDatabaseHas('emails', $emailData);
    }

    /** @test */
    function a_campaign_email_requires_a_subject()
    {
        $this->actingAs($this->user);

        $automation = factory(Campaign::class)->create();

        $emailData = [
            'subject' => null,
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('campaigns.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('subject');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function a_campaign_email_requires_a_template_id()
    {
        $this->actingAs($this->user);

        $automation = factory(Campaign::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => null,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('campaigns.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('template_id');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function a_campaign_email_requires_a_from_email()
    {
        $this->actingAs($this->user);

        $automation = factory(Campaign::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => null,
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('campaigns.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('from_email');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function a_campaign_email_from_email_must_be_an_email()
    {
        $this->actingAs($this->user);

        $automation = factory(Campaign::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => 'what did you call me',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('campaigns.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('from_email');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function a_campaign_email_requires_a_from_name()
    {
        $this->actingAs($this->user);

        $automation = factory(Campaign::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => null,
        ];

        $response = $this->post(route('campaigns.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('from_name');
        $this->assertDatabaseMissing('emails', $emailData);
    }
}
