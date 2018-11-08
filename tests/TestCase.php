<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertRedirectToLogin($response)
    {
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
