<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Auth;
use App\Models\{Product};

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::withoutTrashed()->with(['category:_id,name','createdBy:_id,full_name,type'])->latest('created_at')->paginate(15);
        return response($products,200);
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
    public function store(StoreProductRequest $request)
    {
        collect($request->offers)->map(function ($value){
        });
        if($request->validated())
        {
            $creator = [
                'created_by' => Auth::id()
            ];
            $product = Product::create(array_merge($request->validated(),$creator));

            if($request->has('offers'))
            {
                collect($request->offers)->map(function ($offer) use ($product){
                    $product->offers()->create($offer);
                });
            }

            if($request->has('variants'))
            {
                  collect($request->variants)->map(function ($variant) use ($product){
                   $data = $product->variants()->create($variant);

                   collect($variant['options'])->map(function ($option) use ($data){
                       $data->options()->create($option);
                   });
                });
            }

            return response(['message' => 'created!'],201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if($request->validated())
        {
            $product->update($request->validated());
            return response()->noContent();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(!$product->trashed())
        {
            $product->delete();
            return response()->noContent();
        }
    }

    public function restore($product_id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($product_id);
            if($product->trashed())
            {
                $product->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('product not found');
        }
    }

    public function productDetails($product_id)
    {
        try {
            return new ProductResource(Product::findOrFail($product_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('product not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $products = Product::withTrashed()->with(['category:_id,name','createdBy:_id,full_name,type'])->latest('created_at')->paginate(15);
                return response($products,200);
                break;
            case 1 :
                $products = Product::onlyTrashed()->with(['category:_id,name','createdBy:_id,full_name,type'])->latest('created_at')->paginate(15);
                return response($products,200);
                break;
        }
    }
}
