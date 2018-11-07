<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
}
