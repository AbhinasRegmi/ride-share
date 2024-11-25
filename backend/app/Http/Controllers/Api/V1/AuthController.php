<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginWithPhoneRequest;
use App\Http\Requests\VerifyLoginCodeRequest;
use App\Models\User;
use App\Notifications\VerifyPhone;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function user(Request $request)
    {
        return $request->user();
    }

    public function loginWithPhone(LoginWithPhoneRequest $request)
    { 

        $user = User::firstOrCreate([
            'phone' => $request->phone,
        ]);

        if (!$user) {
            return response()->json(
                [
                    'message' => 'Could not process the phone number',
                ],
                422,
            );
        }

        $user->notify(new VerifyPhone());

        return response()
            ->json(
                [
                    "message" => "Text notification sent",
                ],
                200,
            );
    }

    public function verifyLoginCode(VerifyLoginCodeRequest $request)
    {
        $user = User::where("login_code", $request->login_code)->first();

        if (!$user) {
            return response()
                ->json(
                    [
                        "message" => "The provided login_code is invalid",
                        "errors" => [
                            "login_code" => [
                                "The provided login_code is invalid",
                            ],
                        ],
                    ],
                    422
                );
        }

        return response()
            ->json(
                [
                    "message" => "Login code verification is successfull.",
                    "data" => [
                        "auth_token" => $user->createToken("access_code")->plainTextToken,
                    ],
                ],
                200,
            );
    }
}
