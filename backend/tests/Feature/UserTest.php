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
        $response = $this->postJson(route("api.v1.login"), ["phone" => "9840770972"]);
        $response->assertStatus(200);

        $user = User::where("phone", "9840770972")->first();

        if (!$user) {
            // user doesn't exits
            $this->assertTrue(false);
        }

        $response = $this->postJson(route("api.v1.verify"), ["login_code" => $user->login_code]);
        $response->assertStatus(200);

        $this->withHeaders([
            "Authorization" => "Bearer " . $response->decodeResponseJson()["data"]["auth_token"],
        ])->getJson(route("api.v1.auth.user"));
        $response->assertStatus(200);
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
