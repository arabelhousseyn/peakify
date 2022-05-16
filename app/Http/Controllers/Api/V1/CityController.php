<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Http\Resources\CityResource;
use App\Models\{City};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::withoutTrashed()->latest('created_at')->paginate(15);
        return response($cities,200);
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
    public function store(StoreCityRequest $request)
    {
        if($request->validated())
        {
            City::create($request->validated());

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
    public function update(UpdateCityRequest $request, City $city)
    {
        if($request->validated())
        {
            $city->update($request->only('name'));
            return response()->noContent();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        if(!$city->trashed())
        {
            $city->delete();
            return response()->noContent();
        }
    }

    public function restore($city_id)
    {
        try {
            $city = City::withTrashed()->findOrFail($city_id);
            if($city->trashed())
            {
                $city->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('city not found');
        }
    }

    public function CityDetails($city_id)
    {
        try {
            return new CityResource(City::findOrFail($city_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('city not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $cities = City::withTrashed()->latest('created_at')->paginate(15);
                return response($cities,200);
                break;
            case 1 :
                $cities = City::onlyTrashed()->latest('created_at')->paginate(15);
                return response($cities,200);
                break;
        }
    }
}
