<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\{
    LoginController,
    RequestResetPassowrdController,
    ResetPasswordController,
    LogoutController,
    UserController,
    ClientController,
    CategoryController,
    ProductController,
    OptionController
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
        Route::put('restore/{user_id}','restore')->whereAlphaNumeric('user_id');
        Route::put('change-password/{user_id}','changePassword')->whereAlphaNumeric('user_id');
        Route::get('user-details/{user_id}','userDetails')->whereAlphaNumeric('user_id');
        Route::get('filter/{filter}','filterUsers')->whereNumber('filter');
    });

    // client

    Route::controller(ClientController::class)->prefix('client')->group(function (){
        Route::put('restore/{client_id}','restore')->whereAlphaNumeric('client_id');
        Route::get('client-details/{client_id}','clientDetails')->whereAlphaNumeric('client_id');
        Route::get('filter/{filter}','filter')->whereNumber('filter');
    });

    // category

    Route::controller(CategoryController::class)->prefix('category')->group(function (){
        Route::get('all','getAllCategories');
        Route::put('restore/{category_id}','restore')->whereAlphaNumeric('category_id');
        Route::get('category-details/{category_id}','categoryDetails')->whereAlphaNumeric('category_id');
        Route::get('filter/{filter}','filter')->whereNumber('filter');
    });

    // option
    Route::controller(OptionController::class)->prefix('option')->group(function (){
        Route::get('all','getAll');
        Route::get('values/{option_id}','getValuesByOption')->whereAlphaNumeric('option_id');
        Route::put('restore/{option_id}','restore')->whereAlphaNumeric('option_id');
        Route::get('option-details/{option_id}','optionDetails')->whereAlphaNumeric('option_id');
        Route::get('filter/{filter}','filter')->whereNumber('filter');
        Route::get('values-by-option/{option_id}','valuesByOption')->whereAlphaNumeric('option_id');
        Route::post('store-values','storeValues');
        Route::put('update-option-value/{option_id}','updateValue')->whereAlphaNumeric('option_id');
        Route::delete('destory-option-value/{option_id}','destroyValue')->whereAlphaNumeric('option_id');
        Route::put('restore-option-value/{option_id}','restoreValue')->whereAlphaNumeric('option_id');
        Route::get('values/filter/{filter}/{option_id}','filterValues')->whereNumber('filter','option_id');
        Route::get('option-value-details/{option_value_id}','optionValueDetails')->whereAlphaNumeric('option_value_id');
    });

    // product

    Route::controller(ProductController::class)->prefix('product')->group(function (){
        Route::put('restore/{product_id}','restore')->whereAlphaNumeric('product_id');
        Route::get('product-details/{product_id}','productDetails')->whereAlphaNumeric('product_id');
        Route::get('filter/{filter}','filter')->whereNumber('filter');

        Route::prefix('offers')->group(function (){
            Route::get('{product_id}','offers')->whereAlphaNumeric('product_id');
            Route::post('store','storeOffers');
            Route::put('update/{product_offer_id}','updateOffers')->whereAlphaNumeric('product_offer_id');
            Route::get('filter/{filter}/{product_id}','filterOffers')->whereNumber('filter','product_id');
        });
    });

    // resource controllers

    Route::resources([
        'user' => UserController::class,
        'client' => ClientController::class,
        'category' => CategoryController::class,
        'product' => ProductController::class,
        'option' => OptionController::class
    ]);
});
