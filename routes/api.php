<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    LoginController,
    RequestResetPassowrdController
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


Route::middleware('auth:sanctum')->group(function (){

});
