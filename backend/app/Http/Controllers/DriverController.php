<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverUpdateRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $user->load('driver');

        return response()
            ->json(
                [
                    "message" => "Success",
                    "data" => [
                        $user,
                    ]
                ],
                200
            );
    }

    public function edit(DriverUpdateRequest $request)
    {
        $user = $request->user();
        $user->update(
            $request->only('name'),
        );

        $user->driver()->updateOrCreate(
            $request->only([
                'make',
                'model',
                'color',
                'license_plate'
            ])
        );

        $user->load('driver');

        return response()
            ->json([
                "message" => "Success",
                "data" => [
                    $user
                ]
            ],
            200
        );
    }
}
