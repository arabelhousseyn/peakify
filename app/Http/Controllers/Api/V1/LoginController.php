<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
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
                if($user->type == 'admin')
                {
                    $token = $user->createToken('peakify')->plainTextToken;
                    $user['token'] = $token;
                    return response(['data' => $user],200);
                }

                if($user->type == 'agent')
                {
                    $data = User::with('permissions')->find($user->_id);
                    $token = $user->createToken('peakify')->plainTextToken;
                    $data['token'] = $token;
                    return response(['data' => $data],200);
                }
            }

            return response(['message' => 'Une de vos informations est incorrecte.'],404);
        }
    }
}
