<?php

namespace Tests\Feature;

use App\Models\AutomationStep;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomationStepEmailTest extends TestCase
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
        $automationStep = factory(AutomationStep::class)->create();
        $response = $this->get(route('automations.steps.email.create', [$automationStep->automation->id, $automationStep->id]));

        $response->assertStatus(200);
    }

    /** @test */
    function an_unauthorised_user_cannot_view_the_create_page()
    {
        $automationStep = factory(AutomationStep::class)->create();
        $response = $this->get(route('automations.steps.email.create', [$automationStep->automation->id, $automationStep->id]));

        $this->assertRedirectToLogin($response);
    }

    /** @test */
    function automation_step_emails_can_be_created()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create();

        $emailData = [
            'subject' => 'Test Email',
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $this->post(route('automations.steps.email.store', [$automationStep->automation->id, $automationStep->id]), $emailData);

        $this->assertDatabaseHas('emails', $emailData);
        foreach ($emailData as $key => $value)
        {
            $this->assertEquals($automationStep->email->toArray()[$key], $value);
        }
    }

    /** @test */
    function an_automation_step_email_requires_a_subject()
    {
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create();

        $emailData = [
            'subject' => null,
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.steps.email.store', [$automationStep->automation->id, $automationStep->id]), $emailData);

        $response->assertSessionHasErrors('subject');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_requires_a_template_id()
    {
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => null,
            'from_email' => 'test@email.com',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.steps.email.store', [$automationStep->automation->id, $automationStep->id]), $emailData);

        $response->assertSessionHasErrors('template_id');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_requires_a_from_email()
    {
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => null,
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.steps.email.store', [$automationStep->automation->id, $automationStep->id]), $emailData);

        $response->assertSessionHasErrors('from_email');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_from_email_must_be_an_email()
    {
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => 'what did you call me',
            'from_name' => 'Seymour Greentests',
        ];

        $response = $this->post(route('automations.steps.email.store', [$automationStep->automation->id, $automationStep->id]), $emailData);

        $response->assertSessionHasErrors('from_email');
        $this->assertDatabaseMissing('emails', $emailData);
    }

    /** @test */
    function an_automation_email_requires_a_from_name()
    {
        $this->actingAs($this->user);

        $automationStep = factory(AutomationStep::class)->create();

        $emailData = [
            'subject' => 'Test Subject',
            'template_id' => 1,
            'from_email' => 'test@email.com',
            'from_name' => null,
        ];

        $response = $this->post(route('automations.steps.email.store', [$automationStep->automation->id, $automationStep->id]), $emailData);

        $response->assertSessionHasErrors('from_name');
        $this->assertDatabaseMissing('emails', $emailData);
    }
}