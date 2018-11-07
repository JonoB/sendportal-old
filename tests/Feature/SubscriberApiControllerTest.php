<?php

namespace Tests\Feature;

use App\Models\Segment;
use App\Models\Subscriber;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriberApiControllerTest extends TestCase
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
        $response = $this->getJson(route('api.subscribers.index'));

        $response->assertStatus(401);
    }

    /** @test */
    function the_show_endpoint_is_authenticated()
    {
        $response = $this->getJson(route('api.subscribers.show', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_store_endpoint_is_authenticated()
    {
        $response = $this->postJson(route('api.subscribers.store'));

        $response->assertStatus(401);
    }

    /** @test */
    function the_update_endpoint_is_authenticated()
    {
        $response = $this->putJson(route('api.subscribers.update', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_destroy_endpoint_is_authenticated()
    {
        $response = $this->deleteJson(route('api.subscribers.destroy', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_index_endpoint_returns_a_paginated_list_of_subscribers()
    {
        $subscriberCount = 10;

        factory(Subscriber::class, $subscriberCount)->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('api.subscribers.index'));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [],
            'links' => [],
            'meta' => [
                'total' => $subscriberCount
            ]
        ]);
    }

    /** @test */
    function the_index_endpoint_does_not_include_segments()
    {
        factory(Subscriber::class)->state('segmented')->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('api.subscribers.index'));

        $response->assertJsonMissing([
            'data' => [
                [
                    'segments' => []
                ]
            ]
        ]);
    }

    /** @test */
    function the_show_endpoint_returns_a_single_subscriber()
    {
        $subscriber = factory(Subscriber::class)->state('segmented')->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('api.subscribers.show', $subscriber->id));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'first_name' => $subscriber->first_name,
                'last_name' => $subscriber->last_name,
                'email' => $subscriber->email,
                'segments' => []
            ]
        ]);
    }

    /** @test */
    function the_store_endpoint_is_validated()
    {
        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.store'));

        $response->assertStatus(422);
    }

    /** @test */
    function the_store_endpoint_creates_a_new_subscriber()
    {
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.store', $data));

        $response->assertStatus(201);
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('subscribers', $data);
    }

    /** @test */
    function the_store_endpoint_updates_an_existing_subscriber_when_email_already_exists()
    {
        $subscriber = factory(Subscriber::class)->create();

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $subscriber->email,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.store', $data));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => $subscriber->email,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);

        $this->assertDatabaseMissing('subscribers', [
            'email' => $subscriber->email,
            'first_name' => $subscriber->first_name,
            'last_name' => $subscriber->last_name
        ]);
    }

    /** @test */
    function the_store_endpoint_updates_an_existing_subscriber_when_supplied_with_an_id()
    {
        $subscriber = factory(Subscriber::class)->create();

        $data = [
            'id' => $subscriber->id,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.store', $data));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);

        $this->assertDatabaseMissing('subscribers', [
            'email' => $subscriber->email,
            'first_name' => $subscriber->first_name,
            'last_name' => $subscriber->last_name
        ]);
    }

    /** @test */
    function the_store_endpoint_can_associate_segments_with_a_new_subscriber()
    {
        $segment = factory(Segment::class)->create();

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'segments' => [
                $segment->id
            ]
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.store', $data));

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'email' => $data['email'],
                'segments' => [
                    ['id' => $segment->id]
                ]
            ],
        ]);

        $this->assertDatabaseHas('subscribers', ['email' => $data['email']]);
        $this->assertDatabaseHas('segment_subscriber', ['segment_id' => $segment->id, 'subscriber_id' => $response->json()['data']['id']]);
    }

    /** @test */
    function the_store_endpoint_does_not_associate_segments_when_updating_via_id()
    {
        $subscriber = factory(Subscriber::class)->create();
        $segment = factory(Segment::class)->create();

        $data = [
            'id' => $subscriber->id,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'segments' => [
                $segment->id
            ]
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.store', $data));

        $response->assertStatus(200);
        $response->assertJsonMissing([
            'data' => [
                'segments' => []
            ]
        ]);

        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $subscriber->id
        ]);
    }

    /** @test */
    function the_store_endpoint_does_not_associate_segments_when_updating_via_email()
    {
        $subscriber = factory(Subscriber::class)->create();
        $segment = factory(Segment::class)->create();

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $subscriber->email,
            'segments' => [
                $segment->id
            ]
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.store', $data));

        $response->assertStatus(200);
        $response->assertJsonMissing([
            'data' => [
                'segments' => []
            ]
        ]);

        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $subscriber->id
        ]);
    }

    /** @test */
    function the_update_endpoint_is_validated()
    {
        $subscriber = factory(Subscriber::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route('api.subscribers.update', $subscriber->id));

        $response->assertStatus(422);
    }

    /** @test */
    function the_update_endpoint_updates_an_existing_subscriber()
    {
        $subscriber = factory(Subscriber::class)->create();

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route('api.subscribers.update', $subscriber->id), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $data
        ]);

        $this->assertDatabaseHas('subscribers', $data);
        $this->assertDatabaseMissing('subscribers', ['email' => $subscriber->email]);
    }

    /** @test */
    function the_destroy_endpoint_deletes_a_subscriber()
    {
        $subscriber = factory(Subscriber::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson(route('api.subscribers.destroy', $subscriber->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('subscribers', ['email' => $subscriber->email]);
    }
}
