<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\PasswordReseted;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ResetPasswordRequest $request,String $token)
    {
        if($request->validated())
        {
            try {
                $data = DB::table('password_resets')->where('token',$token)->first();
                $user = User::where('email',$data['email'])->first();

                User::where('_id',$user->_id)->update([
                    'password' => Hash::make($request->new_password)
                ]);

                Mail::to($user->email)->send(new PasswordReseted());

                DB::table('password_resets')->where('token',$token)->delete();

                return response()->noContent();
            }catch (\Exception $exception)
            {
                return response(['message' => $exception->getMessage()],500);
            }
        }
    }
}
