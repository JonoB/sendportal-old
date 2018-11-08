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
    function the_automation_step_create_page_shows_a_list_of_automation_period_units()
    {
        $this->actingAs($this->user);
        $automationStep = factory(AutomationStep::class)->create();
        $units = AutomationStep::$units;

        $response = $this->get(route('automations.steps.create', [$automationStep->automation->id, $automationStep->id]));

        $response->assertStatus(200);

        foreach($units as $unit)
        {
            $response->assertSee($unit);
        }
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
    function an_automation_step_can_have_a_delay_applied_to_it(){
        $this->actingAs($this->user);

        $automation = factory(Automation::class)->create();
        $automationStep = factory(AutomationStep::class)->make([
            'automation_id' => $automation->id,
            'delay' => 5,
            'delay_unit' => AutomationStep::UNIT_DAYS,
        ]);

        $response = $this->post(route('automations.steps.store', [$automation->id]), $automationStep->toArray());

        $this->assertDatabaseHas('automation_steps', $automationStep->toArray());
        $this->assertTrue($automation->steps->first()->is_delayed, true);
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

    /** @test */
    function the_edit_page_can_be_viewed()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create();

        $response = $this->get(route('automations.steps.edit', [$automationStep->automation->id, $automationStep->id]));

        $response->assertStatus(200);
    }

    /** @test */
    function an_automation_step_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create([
            'name' => 'Automation Step 1',
            'delay' => 1,
            'delay_unit' => AutomationStep::UNIT_HOURS,
        ]);

        $newData = [
            'name' => 'A Better Name',
            'delay' => 7,
            'delay_unit' => AutomationStep::UNIT_DAYS,
        ];

        $response = $this->put(route('automations.steps.update', [$automationStep->automation->id, $automationStep->id]), $newData);

        $response->assertStatus(302);
        $response->assertRedirect(route('automations.steps.edit', [$automationStep->automation->id, $automationStep->id]));
    }
}