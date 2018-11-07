<?php

namespace Tests\Feature;

use App\Models\CampaignStatus;
use App\Models\Email;
use App\Models\Template;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var User
     */
    private $user;

    protected function __setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
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
