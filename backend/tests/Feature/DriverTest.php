<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Driver;
use App\Services\LoginCodeService;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertNotNull;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    public function create_user_token()
    {
        $user = User::create([
            "phone" => "+9779840770972",
            "login_code" => LoginCodeService::generate()
        ]);

        $response = $this->postJson(route("api.v1.verify"), ["login_code" => $user->login_code]); 
        $response->assertStatus(200);

        $authorization = ["Authorization" => "Bearer " . $response->decodeResponseJson()["data"]["auth_token"]];

        return [$user, $authorization];

    }

    public function test_get_driver_without_driver()
    {
        // Get driver without auth token
        $response = $this->getJson(route("api.v1.auth.driver"));
        $response->assertStatus(401);

        // Get driver without driver account
        $user_token = $this->create_user_token(); 

        $response = $this->withHeaders($user_token[1])->getJson(
            route("api.v1.auth.driver")
        );

        $response
        ->assertStatus(200)
        ->assertJsonPath('data.0.phone', '+9779840770972')
        ->assertJsonPath('data.0.name', null)
        ->assertJsonPath('data.0.driver', null);
    }

    public function test_get_driver_by_updating_driver_info()
    {
        $user_token = $this->create_user_token();

        // Create without detail
        $response = $this->withHeaders($user_token[1])->postJson(
            route("api.v1.auth.driver.edit")
        );
        $response->assertStatus(422);

        // Create with incomplete detail
        $response = $this->withHeaders($user_token[1])->postJson(
            route("api.v1.auth.driver.edit"),
            [
                "name" => "abhinas regmi"
            ]
        );
        $response->assertStatus(422);

        // Create with all details
        $response = $this->withHeaders($user_token[1])->postJson(
            route("api.v1.auth.driver.edit"),
            [
                "name" => "abhinas regmi",
                "make" => "2001",
                "model" => "X",
                "color" => "mettalic black",
                "license_plate" => "34444"
            ]
        );

        // Find the driver
        $response = $this->withHeaders($user_token[1])->getJson(
            route("api.v1.auth.driver")
        );

        $response
        ->assertStatus(200)
        ->assertJsonPath("data.0.name", "abhinas regmi")
        ->assertJsonPath("data.0.driver.model", "X");
    }

}
