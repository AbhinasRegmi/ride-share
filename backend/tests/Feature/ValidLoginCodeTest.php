<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\LoginCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidLoginCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_without_login_code(): void
    {
        $response = $this->postJson(route('api.v1.verify'));

        $response->assertStatus(422);
    }

    public function test_with_incorrect_login_code(): void
    {
        // with alpha
        $response = $this->postJson(route("api.v1.verify", ["login_code" => "abcde"]));
        $response->assertStatus(422);

        // with incorrect length
        $response = $this->postJson(route("api.v1.verify", ["login_code" => "11134"]));
        $response->assertStatus(422);
    }

    public function test_with_valid_code(): void
    {
        $login_code = LoginCodeService::generate();
        User::create(
            [
                "phone" => "9840770972",
                "login_code" => $login_code,
            ]
        );

        $response = $this->postJson(route("api.v1.verify", ["login_code" => $login_code]));
        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'auth_token',
            ],
        ]);
    }
}
