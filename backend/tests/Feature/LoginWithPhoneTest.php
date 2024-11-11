<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginWithPhoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_invalid_phone()
    {
        // with invalid phone number
        $response = $this->postJson(route('api.v1.login'), ['phone' => '1233']);
        $response->assertStatus(422);

        // without phone number
        $response = $this->postJson(route('api.v1.login'));
        $response->assertStatus(422);
    } 

    public function test_login_with_correct_phone()
    {
        $response = $this->postJson(route('api.v1.login'), ["phone" => "9840770972"]);

        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            "message",
        ]);
    }
}
