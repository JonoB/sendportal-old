<?php

namespace Tests\Feature;

use App\Models\Segment;
use App\Models\Subscriber;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SegmentSubscribersApiControllerTest extends TestCase
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
        $response = $this->getJson(route('api.segments.subscribers.index', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_store_endpoint_is_authenticated()
    {
        $response = $this->postJson(route('api.segments.subscribers.store', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_update_endpoint_is_authenticated()
    {
        $response = $this->putJson(route('api.segments.subscribers.update', 1));

        $response->assertStatus(401);
    }

    /** @test */
    function the_destroy_endpoint_is_authenticated()
    {
        $response = $this->deleteJson(route('api.segments.subscribers.destroy', [1, 1]));

        $response->assertStatus(401);
    }

    /** @test */
    function the_index_endpoint_lists_subscribers_associated_with_the_segment()
    {
        $segment = factory(Segment::class)->state('subscribed')->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('api.segments.subscribers.index', $segment->id));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'id' => $segment->subscribers->first()->id
                ]
            ]
        ]);
    }

    /** @test */
    function the_store_endpoint_can_add_one_or_more_subscribers_to_a_segment()
    {
        $segment = factory(Segment::class)->create();
        $subscribers = factory(Subscriber::class, 2)->create();

        $data = [
            'subscribers' => $subscribers->pluck('id')
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson(route('api.segments.subscribers.store', $segment->id), $data);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $subscribers[0]->id
        ]);

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $subscribers[0]->id
        ]);
    }

    /** @test */
    function the_destroy_endpoint_can_remove_one_or_more_subscribers_from_a_segment()
    {
        $segment = factory(Segment::class)->state('subscribed')->create();

        $data = [
            'subscribers' => [$segment->subscribers->first()->id]
        ];

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson(route('api.segments.subscribers.destroy', $segment->id), $data);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $segment,
            'subscriber_id' => $segment->subscribers->first()->id
        ]);
    }

    /** @test */
    function the_update_endpoint_syncs_a_segments_subscribers()
    {
        $segment = factory(Segment::class)->state('subscribed')->create();
        $subscribers = factory(Subscriber::class, 2)->create();

        $originalSubscribers = $segment->subscribers;

        $data = [
            'subscribers' => $subscribers->pluck('id')
        ];

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route('api.segments.subscribers.update', $segment->id), $data);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonMissing([
            'data' => [
                [
                    'id' => $originalSubscribers[0]->id
                ]
            ]
        ]);

        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $subscribers[0]->id
        ]);
        $this->assertDatabaseHas('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $subscribers[1]->id
        ]);
        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $originalSubscribers[0]->id
        ]);
        $this->assertDatabaseMissing('segment_subscriber', [
            'segment_id' => $segment->id,
            'subscriber_id' => $originalSubscribers[1]->id
        ]);
    }
}
