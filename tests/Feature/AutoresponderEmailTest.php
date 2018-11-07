<?php

namespace Tests\Feature;

use App\Models\Autoresponder;
use App\Models\AutoResponderEmail;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoresponderEmailTest extends TestCase
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

    function an_authenticated_user_can_create_an_autoresponder_email()
    {
        $this->actingAs($this->user);

        // Set Up
        $autoresponder = factory(Autoresponder::class)->create();
        $autoresponderEmail = factory(AutoresponderEmail::class)->make([
            'autoresponder_id' => $autoresponder->id,
        ]);

        $response = $this->post(route('autoresponderemail.store'), $autoresponderEmail->toArray());

        $response->assertStatus(201);
    }
}
