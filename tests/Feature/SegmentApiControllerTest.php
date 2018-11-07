<?php

namespace Tests\Feature;

use App\Models\Segment;
use App\Models\Subscriber;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SegmentApiControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var User
     */
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    function the_index_endpoint_is_authenticated()
    {
        $response = $this->getJson(route('api.segments.index'));

        $response->assertStatus(401);
    }

    /** @test */
    function the_show_endpoint_is_authenticated()
    {
        $response = $this->getJson(route('api.segments.show', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_store_endpoint_is_authenticated()
    {
        $response = $this->postJson(route('api.segments.store'));

        $response->assertStatus(401);
    }

    /** @test */
    function the_update_endpoint_is_authenticated()
    {
        $response = $this->putJson(route('api.segments.update', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_destroy_endpoint_is_authenticated()
    {
        $response = $this->deleteJson(route('api.segments.destroy', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_index_endpoint_returns_a_paginated_list_of_segments()
    {
        $segmentCount = 10;

        factory(Segment::class, $segmentCount)->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('api.segments.index'));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [],
            'links' => [],
            'meta' => [
                'total' => $segmentCount
            ]
        ]);
    }

    /** @test */
    function the_show_endpoint_returns_a_single_segment()
    {
        $segment = factory(Segment::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('api.segments.show', $segment->id));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'name' => $segment->name
            ]
        ]);
    }

    /** @test */
    function the_store_endpoint_is_validated()
    {
        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.segments.store'));

        $response->assertStatus(422);
    }

    /** @test */
    function the_store_endpoint_creates_a_new_segment()
    {
        $data = [
            'name' => ucwords($this->faker->word)
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.segments.store', $data));

        $response->assertStatus(201);
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('segments', $data);
    }

    /** @test */
    function the_store_endpoint_can_associate_subscribers_with_a_new_segment()
    {
        $subscriber = factory(Subscriber::class)->create();

        $data = [
            'name' => ucwords($this->faker->word),
            'subscribers' => [
                $subscriber->id
            ]
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.segments.store', $data));

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'subscribers' => [
                    [
                        'id' => $subscriber->id
                    ]
                ]
            ]
        ]);

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $response->json()['data']['id'],
            'subscriber_id' => $subscriber->id
        ]);
    }

    /** @test */
    function the_update_endpoint_is_validated()
    {
        $segment = factory(Segment::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route('api.segments.update', $segment->id));

        $response->assertStatus(422);
    }

    /** @test */
    function the_update_endpoint_updates_an_existing_segment()
    {
        $segment = factory(Segment::class)->create();

        $data = [
            'name' => ucwords($this->faker->word)
        ];

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route('api.segments.update', $segment->id), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('segments', $data);
        $this->assertDatabaseMissing('segments', ['name' => $segment->name]);
    }

    /** @test */
    function the_destroy_endpoint_deletes_a_segment()
    {
        $segment = factory(Segment::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson(route('api.segments.destroy', $segment->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('segments', ['name' => $segment->name]);
    }
}
