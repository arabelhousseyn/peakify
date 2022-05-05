<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\StoreOptionValuesRequest;
use App\Http\Resources\OptionResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\{Option,OptionValue};
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $options = Option::withoutTrashed()->latest('created_at')->paginate(15);
        return response($options,200);
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
    public function store(StoreOptionRequest $request)
    {
        if($request->validated())
        {
            $option = Option::create($request->validated());

            if($request->has('value'))
            {
                collect($request->values)->map(function ($value) use ($option){
                    $option->values()->create($value);
                });
            }

            return response(['message' => 'created !'],201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function show(Option $option)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function edit(Option $option)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Option $option)
    {
        $option->update($request->all());
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Option  $option
     * @return \Illuminate\Http\Response
     */
    public function destroy(Option $option)
    {
        if(!$option->trashed())
        {
            $option->delete();
            return response()->noContent();
        }
    }

    public function restore($option_id)
    {
        try {
            $option = Option::withTrashed()->findOrFail($option_id);
            if($option->trashed())
            {
                $option->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option not found');
        }
    }

    public function optionDetails($option_id)
    {
        try {
            return new OptionResource(Option::findOrFail($option_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $options = Option::withTrashed()->latest('created_at')->paginate(15);
                return response($options,200);
                break;
            case 1 :
                $options = Option::onlyTrashed()->latest('created_at')->paginate(15);
                return response($options,200);
                break;
        }
    }

    public function valuesByOption($option_id)
    {
        try {
            $option = Option::with('values')->findOrFail($option_id);
            return response(['data' => $option->values],200);
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option not found');
        }
    }

    public function storeValues(StoreOptionValuesRequest $request)
    {
        if($request->validated())
        {
            $option = Option::find($request->option_id);

            collect($request->values)->map(function ($value) use ($option){
                $option->values()->create($value);
            });

            return response(['message' => 'created !'],201);
        }
    }

    public function updateValue(Request $request, $option_value_id)
    {
        try {
            $option_value = OptionValue::findOrFail($option_value_id);
            $option_value->update($request->all());
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option value not found');
        }
    }

    public function destroyValue($option_value_id)
    {
        try {
            $option_value = OptionValue::findOrFail($option_value_id);
            $option_value->delete();
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option value not found');
        }
    }

    public function restoreValue($option_value_id)
    {
        try {
            $option_value = OptionValue::withTrashed()->findOrFail($option_value_id);
            $option_value->restore();
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option value not found');
        }
    }
}
