<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Entities\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);
    }

    /**
     * Sign in using Passport
     */
    public function signIsUsingPassport()
    {
        Passport::actingAs(factory(User::class)->create());
    }

    /**
     * Sign in using Passport as specific user
     *
     * @param User $user
     */
    public function signIsUsingPassportAsUser(User $user)
    {
        Passport::actingAs($user);
    }

    /**
     * Disable authorization
     */
    public function disableAuthorization()
    {
        Gate::before(function () {
            return true;
        });
    }
}
