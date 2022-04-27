<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    LoginController,
    RequestResetPassowrdController,
    ResetPasswordController,
    LogoutController,
    UserController,
    ClientController
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


Route::middleware(['auth:sanctum','check.hours'])->group(function (){
    // admin
    Route::prefix('admin')->group(function (){
        Route::get('logout',LogoutController::class);
    });

    // user

    Route::controller(UserController::class)->prefix('user')->group(function (){
        Route::put('ban/{user_id}/{status}','banUser')->whereAlphaNumeric('user_id');
        Route::put('define-hours/{user_id}','defineHours')->whereAlphaNumeric('user_id');
        Route::get('restore/{user_id}','restore')->whereAlphaNumeric('user_id');
        Route::put('change-password/{user_id}','changePassword')->whereAlphaNumeric('user_id');
        Route::get('user-details/{user_id}','userDetails')->whereAlphaNumeric('user_id');
        Route::get('filter/{filter}','filterUsers')->whereNumber('filter');
    });

    // client

    Route::controller(ClientController::class)->prefix('client')->group(function (){
        Route::put('restore/{client_id}','restore')->whereAlphaNumeric('client_id');
    });

    // resource controllers

    Route::resources([
        'user' => UserController::class,
        'client' => ClientController::class
    ]);
});
