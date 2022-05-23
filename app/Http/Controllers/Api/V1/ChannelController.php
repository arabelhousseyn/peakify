<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Http\Resources\ChannelResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\{Channel};
class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channels = Channel::withoutTrashed()->latest('created_at')->paginate(15);
        return response($channels,200);
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
    public function store(StoreChannelRequest $request)
    {
        if($request->validated())
        {
            Channel::create($request->validated());

            return response(['message' => 'created !'],201);
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
    public function update(UpdateChannelRequest $request, Channel $channel)
    {
        if($request->validated())
        {
             $channel->update($request->validated());
             return response()->noContent();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel)
    {
        if(!$channel->trashed())
        {
            $channel->delete();
            return response()->noContent();
        }
    }

    public function restore($channel_id)
    {
        try {
            $channel = Channel::withTrashed()->findOrFail($channel_id);
            $channel->restore();
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('channel not found');
        }

    }

    public function channelDetails($channel_id)
    {
        try {
            return new ChannelResource(Channel::findOrFail($channel_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('channel not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $channels = Channel::withTrashed()->latest('created_at')->paginate(15);
                return response($channels,200);
                break;
            case 1 :
                $channels = Channel::onlyTrashed()->latest('created_at')->paginate(15);
                return response($channels,200);
                break;
        }
    }

    public function getAllCategories()
    {
        $channels = Channel::select('name')->get();
        return response(['data' => $channels],200);
    }
}
