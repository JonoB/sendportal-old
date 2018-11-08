<?php

namespace Tests\Feature;

use App\Models\Segment;
use App\Models\Subscriber;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberSegmentsApiControllerTest extends TestCase
{
    use RefreshDatabase;

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
        $response = $this->getJson(route('api.subscribers.segments.index', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_store_endpoint_is_authenticated()
    {
        $response = $this->postJson(route('api.subscribers.segments.store', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_update_endpoint_is_authenticated()
    {
        $response = $this->putJson(route('api.subscribers.segments.update', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_destroy_endpoint_is_authenticated()
    {
        $response = $this->deleteJson(route('api.subscribers.segments.destroy', [1, 1]));

        $response->assertStatus(401);
    }

    /** @test */
    function the_index_endpoint_lists_segments_associated_with_the_subscriber()
    {
        $subscriber = factory(Subscriber::class)->state('segmented')->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('api.subscribers.segments.index', $subscriber->id));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'id' => $subscriber->segments->first()->id
                ]
            ]
        ]);
    }

    /** @test */
    function the_store_endpoint_can_add_a_subscriber_to_one_or_more_segments()
    {
        $subscriber = factory(Subscriber::class)->create();
        $segments = factory(Segment::class, 2)->create();

        $data = [
            'segments' => $segments->pluck('id')
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.subscribers.segments.store', $subscriber->id), $data);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segments[0]->id,
            'subscriber_id' => $subscriber->id
        ]);

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segments[1]->id,
            'subscriber_id' => $subscriber->id
        ]);
    }

    /** @test */
    function the_destroy_endpoint_can_remove_a_subscriber_from_one_or_more_segments()
    {
        $subscriber = factory(Subscriber::class)->state('segmented')->create();

        $data = [
            'segments' => [$subscriber->segments->first()->id]
        ];

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson(route('api.subscribers.segments.destroy', $subscriber->id), $data);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $subscriber->segments->first()->id,
            'subscriber_id' => $subscriber->id
        ]);
    }

    /** @test */
    function the_update_endpoint_syncs_a_subscribers_segments()
    {
        $subscriber = factory(Subscriber::class)->state('segmented')->create();
        $segments = factory(Segment::class, 2)->create();

        $originalSegments = $subscriber->segments;

        $data = [
            'segments' => $segments->pluck('id')
        ];

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route('api.subscribers.segments.update', $subscriber->id), $data);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonMissing([
            'data' => [
                [
                    'id' => $originalSegments[0]->id
                ]
            ]
        ]);

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segments[0]->id,
            'subscriber_id' => $subscriber->id
        ]);
        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segments[1]->id,
            'subscriber_id' => $subscriber->id
        ]);
        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $originalSegments[0]->id,
            'subscriber_id' => $subscriber->id
        ]);
        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $originalSegments[1]->id,
            'subscriber_id' => $subscriber->id
        ]);
    }
}
