<?php

namespace Tests\Feature;

use App\Models\Automation;
use App\Models\AutomationStep;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomationStepTest extends TestCase
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
    function the_create_page_can_be_viewed()
    {
        $this->actingAs($this->user);
        $automation = factory(Automation::class)->create();
        $response = $this->get(route('automations.steps.create', [$automation->id]));

        $response->assertStatus(200);
    }

    /** @test */
    function an_unauthorised_user_cannot_view_the_create_page()
    {
        $automation = factory(Automation::class)->create();
        $response = $this->get(route('automations.steps.create', [$automation->id]));

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function an_automation_step_can_be_created(){
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();
        $automationStep = factory(AutomationStep::class)->make([
            'automation_id' => $automation->id,
        ]);

        $response = $this->post(route('automations.steps.store', [$automation->id]), $automationStep->toArray());

        $this->assertDatabaseHas('automation_steps', $automationStep->toArray());
        $response->assertRedirect(route('automations.steps.email.create', [$automation->id, $automation->steps->first()->id]));
    }

    /** @test */
    function an_automation_step_can_have_one_email()
    {
        $automationStep = factory(AutomationStep::class)->create();

        $email = [
            'subject' => 'Test Email 1',
            'from_email' => 'test1@email.com',
            'from_name' => 'Test 1',
        ];

        $automationStep->email()->create($email);

        foreach ($email as $key => $value)
        {
            $this->assertEquals($automationStep->email->toArray()[$key], $value);
        }
    }
}