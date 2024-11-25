<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\DriverController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:sanctum"])
    ->as("auth.")
    ->group(
        function () {

            Route::get("/user", [AuthController::class, "user"])->name("user");

            Route::get("/driver", [DriverController::class, "show"])->name("driver");
            Route::post("/driver", [DriverController::class, "edit"])->name("driver.edit");
        }
    );

Route::post("/login", [AuthController::class, "loginWithPhone"])->name("login");
Route::post("/verify", [AuthController::class, "verifyLoginCode"])->name("verify");
