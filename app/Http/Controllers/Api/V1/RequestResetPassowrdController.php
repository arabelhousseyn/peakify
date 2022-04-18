<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestResetPasswordRequest;
use App\Mail\RequestPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RequestResetPassowrdController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RequestResetPasswordRequest $request)
    {
        if($request->validated())
        {
            $token = hash('sha256', Str::random(40));

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token
            ]);
            $url = env('app_url') . '/reset?token=' . $token;
            Mail::to($request->email)->send(new RequestPassword($url));
            return response(['token' => $token],200);
        }
    }
}
