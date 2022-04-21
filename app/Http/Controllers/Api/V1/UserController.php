<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\{DefineHoursOfAccessRequest, StoreUserRequest, UpdateUserRequest, UserChangePasswordRequest};
use App\Mail\AccountCreated;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{App,Hash,Mail};

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withTrashed()->latest('created_at')->type('agent')->paginate(2);
        return response($users,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        if($request->validated())
        {
            $default = [
                'banned_at' => null,
                'start_at' => ($request->start_at) ? $request->start_at : null,
                'end_at' => ($request->end_at) ? $request->end_at : null,
                'type' => 'agent'
            ];
            $hashed_password = [
                'password' => Hash::make($request->password)
            ];
            $user = User::create(array_merge($request->except('password_confirmation','password','start_at','end_at'),$hashed_password,$default));

            Mail::to($request->email)->send(new AccountCreated($request->username,$request->password,Carbon::now()->locale(App::getLocale())->toDateTimeString()));

            return response(['message' => 'created!'],201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {

            if($request->validated())
            {
                $user = User::findOrFail($id);
                $user->update($request->validated());
                return response()->noContent();
            }

        }catch (\Exception $exception)
        {
            return response(['message' => $exception->getMessage()],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            if(!$user->trashed())
            {
                $user->delete();
            }
            return response()->noContent();
        }catch (\Exception $exception)
        {
            return response(['message' => $exception->getMessage()],404);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            if($user->trashed())
            {
                $user->restore();
            }
            return response()->noContent();
        }catch (\Exception $exception)
        {
            return response(['message' => $exception->getMessage()],404);
        }
    }

    public function banUser($user_id, $status)
    {
        User::where('_id',$user_id)->update([
            'banned_at' => ($status) ? Carbon::now()->toDateTimeString() : null
        ]);

        return response()->noContent();
    }

    public function defineHours(DefineHoursOfAccessRequest $request,$user_id)
    {
        if($request->validated())
        {
            User::where('_id',$user_id)->update([
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
            ]);

            return response()->noContent();
        }
    }

    public function changePassword(UserChangePasswordRequest $request,$user_id)
    {
        try {
            if($request->validated())
            {
                $user = User::findOrFail($user_id);
                $user->update(['password' => Hash::make($request->new_password)]);
                return response()->noContent();
            }
        }catch (\Exception $exception)
        {
            return response(['message' => $exception->getMessage()],404);
        }
    }
}
