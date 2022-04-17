<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        if($request->validated())
        {
            $data = ($request->has_email) ? $request->only('email','password') : $request->only('username','password');

            if(Auth::attempt($data))
            {
                $user = Auth::user();
                $token = $user->createToken('peakify')->plainTextToken;
                $user['token'] = $token;
                return response(['data' => $user],200);
            }

            return response(['message' => 'Une de vos informations est incorrecte.'],422);
        }
    }
}
