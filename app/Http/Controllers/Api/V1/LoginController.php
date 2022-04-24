<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
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
                    // check if account is banned
                    if($user->banned_at !== null)
                    {
                        $errors = [
                            'errors' => [
                                'deleted' => ['Compte Bloquer']
                            ]
                        ];
                        return response($errors,422);
                    }

                    // check the access hours
                    $time = Carbon::now()->format('H:i');

                    if($time < $user->start_at || $time > $user->end_at && $user->start_at !== null && $user->end_at !== null)
                    {
                        $errors = [
                            'errors' => [
                                'hours' => ['Accès expiré vous avez accès à votre compte du: ' . $user->start_at . ' au: ' . $user->end_at]
                            ]
                        ];
                        return response($errors,422);
                    }

                    $data = User::with('permissions')->find($user->_id);
                    $token = $user->createToken('peakify')->plainTextToken;
                    $data['token'] = $token;
                    return response(['data' => $data],200);
                }
            }
            $errors = [
                'errors' => [
                    'account' => ['Une de vos informations est incorrecte ou bien le Compte est supprimer']
                ]
            ];
            return response($errors,422);
        }
    }
}
