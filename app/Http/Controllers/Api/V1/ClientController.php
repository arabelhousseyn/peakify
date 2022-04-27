<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\{Client};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $clients = Client::withoutTrashed()->latest('created_at')->paginate(15);
         return response($clients,200);
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
    public function store(StoreClientRequest $request)
    {
        if($request->validated())
        {
            $client = Client::create($request->validated());
            return response(['message' => 'created !'],201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        if($request->validated())
        {
            $client->update($request->all());
            return response()->noContent();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if(!$client->trashed())
        {
            $client->delete();
            return response()->noContent();
        }
    }

    public function restore($client_id)
    {
        try {
            $client = Client::withTrashed()->findOrFail($client_id);
            if($client->trashed())
            {
                $client->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('client not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $clients = Client::withTrashed()->latest('created_at')->paginate(15);
                return response($clients,200);
                break;
            case 1 :
                $clients = Client::onlyTrashed()->latest('created_at')->paginate(15);
                return response($clients,200);
                break;
        }
    }
}
