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
    function an_authenticated_user_can_create_an_autoresponder()
    {
        $automation = factory(Automation::class)->make();

        $response = $this->post(route('automations.store'), $automation->toArray());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function an_unauthenticated_user_cannot_create_an_autoresponder()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->make();

        $response = $this->post(route('automations.store'), $automation->toArray());

        $response->assertStatus(201);
    }

    /** @test */
    function an_autoresponder_requires_a_name()
    {
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->make(['name' => null]);

        $response = $this->post(route('automations.store'), $automation->toArray());

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    function an_autoresponder_requires_a_segment()
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
    function an_autoresponder_has_a_relationship_with_a_segment()
    {
        $segment = factory(Segment::class)->create();

        $automation = factory(Automation::class)->create([
            'segment_id' => $segment->id,
        ]);

        $this->assertEquals($segment->name, $automation->segment->name);
    }
}
