<?php

namespace Tests\Feature;

use App\Entities\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class AuthTest extends TestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * Set up
     */
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install');

        factory(User::class)->create(['email' => 'admin@mail.com']);
    }

    /**
     * Logged in successfully
     *
     * @return void
     */
    public function testLogin()
    {
        dump('test_login_successfully');

        $payload = [
            'email' => 'admin@mail.com',
            'password' => 'pass'
        ];

        $response = $this->login($payload);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'token'
                    ]
                ]);

        $content = $response->getContent();

        $responseArray = json_decode($content, true);

        $this->token = $responseArray['data']['token'];

        dump('test_logout_successfully');

        $response = $this->logout();

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Token invalidated',
            ]);
    }

    /**
     * Log out auth error
     */
    public function testLogoutAuthError()
    {
        dump('test_logout_auth_error');

        $response = $this->logout();

        $response->assertStatus(401);
    }

    /**
     * Send login request
     *
     * @param $payload
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function login($payload)
    {
        $response = $this->json('GET', "/api/login", $payload);

        return $response;
    }

    /**
     * Send logout request
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function logout()
    {
        $response = $this->post("api/logout", [], ['Authorization' => "Bearer {$this->token}"]);

        return $response;
    }
}
