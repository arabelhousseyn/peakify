<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    LoginController,
};

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('throttle:login')->group(function (){
    Route::post('login',LoginController::class);
});


Route::middleware('auth:sanctum')->group(function (){

});
