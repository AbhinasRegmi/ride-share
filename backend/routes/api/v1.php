<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(["auth:sanctum"])
    ->as("auth.")
    ->group(
        function () {

            Route::get("/user", [AuthController::class, "user"])->name("user");
        }
    );

Route::post("/login", [AuthController::class, "loginWithPhone"])->name("login");
Route::post("/verify", [AuthController::class, "verifyLoginCode"])->name("verify");
