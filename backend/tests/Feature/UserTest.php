<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_from_token()
    {
        $response = $this->postJson(route("api.v1.login"), 
            [
                "country_code" => "NEP",
                "phone" => "9840770972"
            ]
        );
        $response->assertStatus(200);

        $user = User::where("phone", "+9779840770972")->first();

        if (!$user) {
            // user doesn't exits
            $this->assertTrue(false);
        }

        $response = $this->postJson(route("api.v1.verify"), ["login_code" => $user->login_code]);
        $response->assertStatus(200);

        // Get user data with valid auth header
        $this->withHeaders([
            "Authorization" => "Bearer " . $response->decodeResponseJson()["data"]["auth_token"],
        ])->getJson(route("api.v1.auth.user"));
        $response->assertStatus(200);

        // Try to login with used code
        $response = $this->postJson(route("api.v1.verify"), ["login_code" => $user->login_code]);
        $response->assertStatus(422);

    }

    public function test_get_user_with_wrong_token()
    {
        $response = $this->withHeaders(
            [
                "Authorization" => "Bearer " . "somerandomshit",
            ],
        )->getJson(route("api.v1.auth.user"));

        $response->assertStatus(401);
    }
}
