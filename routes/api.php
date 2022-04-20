<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    LoginController,
    RequestResetPassowrdController,
    ResetPasswordController,
    LogoutController,
    UserController
};

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// login
Route::middleware('throttle:login')->group(function (){
    Route::post('login',LoginController::class);
});

// reset password

Route::post('request-reset-password',RequestResetPassowrdController::class);
Route::put('reset-password/{token}',ResetPasswordController::class);


Route::middleware('auth:sanctum')->group(function (){
    // admin
    Route::prefix('admin')->group(function (){
        Route::get('logout',LogoutController::class);
    });

    // user

    Route::controller(UserController::class)->prefix('user')->group(function (){
        Route::put('ban/{user_id}/{status}','banUser')->whereAlphaNumeric('user_id');
        Route::put('define-hours/{user_id}','defineHours')->whereAlphaNumeric('user_id');
    });

    // resource controllers

    Route::resources([
        'user' => UserController::class
    ]);
});
