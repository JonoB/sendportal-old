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
    function an_email_has_a_related_template()
    {
        $email = factory(Email::class)->create([
            'template_id' => factory(Template::class)->create()->id,
        ]);

        $this->assertNotNull($email->template->name);
    }
}
