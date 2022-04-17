<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestResetPasswordRequest;

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
            return $request;
        }
    }
}
