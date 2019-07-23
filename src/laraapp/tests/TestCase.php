<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use App\Entities\User;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Sign in using Passport
     */
    public function signIsUsingPassport()
    {
        Passport::actingAs(factory(User::class)->create());
    }
}
