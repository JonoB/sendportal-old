<?php

namespace Tests\Feature;

use App\Models\Automation;
use App\Models\Segment;
use App\Repositories\AutomationEloquentRepository;
use App\Repositories\SegmentEloquentRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    /**
     * @var SegmentEloquentRepository
     */
    private $segmentRepository;

    /**
     * @var AutomationEloquentRepository
     */
    private $automationRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->segmentRepository = $this->app->make(SegmentEloquentRepository::class);
        $this->automationRepository = $this->app->make(AutomationEloquentRepository::class);
    }

    /** @test */
    function an_authenticated_user_can_view_the_index()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('automations.index'));

        $response->assertStatus(200);
    }

    /** @test */
    function an_unauthenticated_user_cannot_view_the_index()
    {
        $response = $this->get(route('automations.index'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function the_index_page_lists_existing_automations()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $response = $this->get(route('automations.index'));

        $response->assertStatus(200);
        $response->assertSee($automation->name);
        $response->assertSee($automation->segment->name);
    }

    /** @test */
    function an_authenticated_user_can_view_the_create_page()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);
        $response = $this->get(route('automations.create'));

        $response->assertStatus(200);
    }

    /** @test */
    function an_unauthenticated_user_cannot_view_the_create_page()
    {
        $response = $this->get(route('automations.create'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function the_create_page_presents_a_list_of_segments()
    {
        $this->actingAs($this->user);

        $segment1 = factory(Segment::class)->create();
        $segment2 = factory(Segment::class)->create();
        $segments = $this->segmentRepository->pluck();

        $response = $this->get(route('automations.create', ['segments' => $segments]));

        $response->assertSee($segment1->id);
        $response->assertSee($segment1->name);
        $response->assertSee($segment2->id);
        $response->assertSee($segment2->name);
    }

    /** @test */
    function an_authenticated_user_can_create_an_automation()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->make();

        $this->post(route('automations.store'), $automation->toArray());

        $this->assertDatabaseHas('automations', $automation->toArray());
    }

    /** @test */
    function an_unauthenticated_user_cannot_create_an_autoamation()
    {
        $automation = factory(Automation::class)->make();

        $response = $this->post(route('automations.store'), $automation->toArray());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function an_automation_requires_a_name()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->make(['name' => null]);

        $response = $this->post(route('automations.store'), $automation->toArray());

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    function an_automation_requires_a_segment()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->make(['segment_id' => null]);

        $response = $this->post(route('automations.store'), $automation->toArray());

        $response->assertSessionHasErrors('segment_id');
    }

    /** @test */
    function an_authenticated_user_can_view_a_automation()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $response = $this->get(route('automations.show', ['id' => $automation->id]));

        $response->assertStatus(200);
        $response->assertSee($automation->name);
    }

    /** @test */
    function an_unauthenticated_user_cannot_view_a_automation()
    {
        $automation = factory(Automation::class)->create();

        $response = $this->get(route('automations.show', ['id' => $automation->id]));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function an_automation_has_a_relationship_with_a_segment()
    {
        $segment = factory(Segment::class)->create();

        $automation = factory(Automation::class)->create([
            'segment_id' => $segment->id,
        ]);

        $this->assertEquals($segment->name, $automation->segment->name);
    }

    /** @test */
    function an_automation_can_have_many_emails()
    {
        $automation = factory(Automation::class)->create();

        $emails = [
            [
                'subject' => 'Test Email 1',
                'from_email' => 'test1@email.com',
                'from_name' => 'Test 1',
            ],
            [
                'subject' => 'Test Email 2',
                'from_email' => 'test2@email.com',
                'from_name' => 'Test 2',
            ],
        ];

        $automation->emails()->createMany($emails);

        foreach($emails as $index => $email)
        {
            foreach($email as $key => $value)
            {
                $this->assertEquals($automation->emails->toArray()[$index][$key], $value);
            }
        }
    }

    /** @test */
    function a_user_can_create_an_email_for_an_automation()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $emailData = [
            'subject' => 'Test Email',
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $this->post(route('automations.emails.store', [$automation->id]), $emailData);

        $this->assertDatabaseHas('emails', $emailData);
    }

    /** @test */
    function an_automation_email_requires_a_subject()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $emailData = [
            'subject' => null,
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('subject');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_requires_a_template_id()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => null,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('template_id');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_requires_a_from_email()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => null,
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('from_email');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_from_email_must_be_an_email()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => 'what did you call me',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('from_email');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_requires_a_from_name()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => null,
        ];

        $response = $this->post(route('automations.emails.store', [$automation->id]), $emailData);

        $response->assertSessionHasErrors('from_name');
        $this->assertDatabaseMissing('emails', $emailData);
    }
}
