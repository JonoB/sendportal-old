<?php

namespace Tests\Feature;

use App\Models\CampaignStatus;
use App\Models\Email;
use App\Models\Template;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
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
    function an_authenticated_user_can_visit_the_create_page()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('automations.create'));

        $response->assertStatus(200);
    }

    /** @test */
    function an_unauthenticated_user_cannot_visit_the_create_page()
    {
        $response = $this->get(route('automations.create'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function an_email_has_a_related_status()
    {
        $email = factory(Email::class)->create([
            'status_id' => CampaignStatus::STATUS_DRAFT,
        ]);

        $this->assertNotNull($email->status->name);
    }

    /** @test */
    function an_email_has_a_related_template()
    {
        $email = factory(Email::class)->create([
            'template_id' => factory(Template::class)->create()->id,
        ]);

        $this->assertNotNull($email->template->name);
    }
}
