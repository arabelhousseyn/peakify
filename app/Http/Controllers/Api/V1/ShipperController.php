<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipperRequest;
use App\Http\Requests\UpdateShipperCityRequest;
use App\Http\Requests\UpdateShipperRequest;
use App\Http\Resources\ShipperResource;
use App\Models\Shipper;
use App\Models\ShipperCity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $shippers = Shipper::latest('created_at')->paginate(15);
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
    public function update(UpdateShipperRequest $request, Shipper $shipper)
    {
            $shipper->update($request->all());
            return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipper  $shipper
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipper $shipper)
    {
        if(!$shipper->trashed())
        {
            $shipper->delete();
            return response()->noContent();
        }
    }

    public function restore($shipper_id)
    {
        try {
            $shipper = Shipper::withTrashed()->findOrFail($shipper_id);
            if($shipper->trashed())
            {
                $shipper->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('shipper not found');
        }
    }

    public function shipperDetails($shipper_id)
    {
        try {
            return new ShipperResource(Shipper::findOrFail($shipper_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('shipper not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $shippers = Shipper::withTrashed()->latest('created_at')->paginate(15);
                return response($shippers,200);
                break;
            case 1 :
                $shippers = Shipper::onlyTrashed()->latest('created_at')->paginate(15);
                return response($shippers,200);
                break;
        }
    }

    public function getAllShippers()
    {
        $shippers = Shipper::select(['_id','full_name','type','phone'])->get();
        return response(['data' => $shippers],200);
    }

    public function getCitiesByShipper($shipper_id)
    {
        try {
            $shipper = Shipper::with('cities.city')->findOrFail($shipper_id);
            return response(['data' => $shipper->cities],200);
        }catch (ModelNotFoundException $exception)
        {
            return new ModelNotFoundException('shipper not found');
        }
    }

    public function UpdateShipperCity(UpdateShipperCityRequest $request, $shipper_city_id)
    {
        if($request->validated())
        {
            try {
                $shipper_city = ShipperCity::findOrFail($shipper_city_id);
                $shipper_city->update($request->all());
                return response()->noContent();
            }catch (ModelNotFoundException $exception)
            {
                return new ModelNotFoundException('shipper city not found');
            }
        }
    }

    public function destroyShipperCity($shipper_city_id)
    {
        try {
            $shipper_city = ShipperCity::findOrFail($shipper_city_id);
            $shipper_city->forceDelete();
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            return new ModelNotFoundException('shipper city not found');
        }
    }
}
