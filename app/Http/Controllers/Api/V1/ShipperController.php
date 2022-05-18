<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipperRequest;
use App\Models\Shipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippers = Shipper::with('cities.city:_id,name')->latest('created_at')->paginate(15);
        return response($shippers,200);
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
    public function store(StoreShipperRequest $request)
    {
        if($request->validated())
        {
                $shipper = Shipper::create(array_merge($request->except('cities'),['city_id' => null]));
                collect($request->cities)->map(function ($city) use ($shipper){
                    if($city['type'] == 'S')
                    {
                        $shipper->update([
                            'city_id' => $city['city_id']
                        ]);
                    }
                    $shipper->cities()->create([
                        'city_id' => $city['city_id'],
                        'price' => $city['price']
                    ]);
                });
            return response(['message' => 'created!'],201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shipper  $shipper
     * @return \Illuminate\Http\Response
     */
    public function show(Shipper $shipper)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipper  $shipper
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipper $shipper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipper  $shipper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipper $shipper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipper  $shipper
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipper $shipper)
    {
        //
    }
}
