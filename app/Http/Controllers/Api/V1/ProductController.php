<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductOffersRequest;
use App\Http\Resources\ProductOfferResource;
use App\Http\Resources\ProductVariantResource;
use App\Http\Requests\{StoreProductRequest,
    StoreProductVariantOptionsRequest,
    StoreProductVariantsRequest,
    UpdateProductRequest,
    UpdateProductOfferRequest,
    UpdateProductVariantRequest};
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\{Product, ProductOffer, ProductVariant, ProductVariantOptionValue};

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
        if($request->validated())
        {
            $creator = [
                'created_by' => Auth::id()
            ];
            $product = Product::create(array_merge($request->validated(),$creator));

            if($request->has('offers'))
            {
                collect($request->offers)->map(function ($offer) use ($product,$creator){
                    $product->offers()->create(array_merge($offer,$creator));
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

    // offers

    public function offers($product_id)
    {
        try {
            $product = Product::with('offers.createdBy')->findOrFail($product_id);
            return response(['data' => array_reverse($product->offers->toArray())],200);
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('product not found');
        }
    }

    public function filterOffers($filter,$product_id)
    {
        switch ($filter)
        {
            case 0 :
                $values = ProductOffer::with('createdBy')->where('product_id',$product_id)->withTrashed()->latest('created_at')->get();
                return response(['data' => $values],200);
                break;
            case 1 :
                $values = ProductOffer::with('createdBy')->where('product_id',$product_id)->onlyTrashed()->latest('created_at')->get();
                return response(['data' => $values],200);
                break;
        }
    }

    public function storeOffers(StoreProductOffersRequest $request)
    {
        if($request->validated())
        {
            $creator = [
                'created_by' => Auth::id()
            ];
            $product = Product::find($request->product_id);
            collect($request->offers)->map(function ($offer) use ($product,$creator){
                $product->offers()->create(array_merge($offer,$creator));
            });
            return response(['message' => 'created !'],201);
        }
    }

    public function updateOffers(UpdateProductOfferRequest $request,$product_offer_id)
    {
        try {
            $product_offer = ProductOffer::findOrFail($product_offer_id);
            $product_offer->update($request->validated());
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('offer not found');
        }
    }

    public function destroyOffer($product_offer_id)
    {
        try {
            $product_offer = ProductOffer::findOrFail($product_offer_id);
            if(!$product_offer->trashed())
            {
                $product_offer->delete();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option not found');
        }
    }

    public function restoreOffer($product_offer_id)
    {
        try {
            $product_offer = ProductOffer::withTrashed()->findOrFail($product_offer_id);
            if($product_offer->trashed())
            {
                $product_offer->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option not found');
        }
    }

    public function OfferDetails($product_offer_id)
    {
        try {
            return new ProductOfferResource(ProductOffer::findOrFail($product_offer_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('offer not found');
        }
    }

    // variants


    public function variants($product_id)
    {
        try {
            $product = Product::with('variants')->findOrFail($product_id);
            return response(['data' => array_reverse($product->variants->toArray())],200);
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('product not found');
        }
    }

    public function filterVariants($filter,$product_id)
    {
        switch ($filter)
        {
            case 0 :
                $values = ProductVariant::where('product_id',$product_id)->withTrashed()->latest('created_at')->get();
                return response(['data' => $values],200);
                break;
            case 1 :
                $values = ProductVariant::where('product_id',$product_id)->onlyTrashed()->latest('created_at')->get();
                return response(['data' => $values],200);
                break;
        }
    }

    public function storeVariants(StoreProductVariantsRequest $request)
    {
        if($request->validated())
        {
            $product = Product::find($request->product_id);
            collect($request->variants)->map(function ($variant) use ($product){
                $data = $product->variants()->create($variant);

                collect($variant['options'])->map(function ($option) use ($data){
                    $data->options()->create($option);
                });
            });
            return response(['message' => 'created !'],201);
        }
    }

    public function updateVariant(UpdateProductVariantRequest $request,$product_variant_id)
    {
        try {
            $product_variant = ProductVariant::findOrFail($product_variant_id);
            $product_variant->update($request->all());
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('variant not found');
        }
    }

    public function destroyVariant($product_variant_id)
    {
        try {
            $product_variant = ProductVariant::findOrFail($product_variant_id);
            if(!$product_variant->trashed())
            {
                $product_variant->delete();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('variant not found');
        }
    }

    public function restoreVariant($product_variant_id)
    {
        try {
            $product_variant = ProductVariant::withTrashed()->findOrFail($product_variant_id);
            if($product_variant->trashed())
            {
                $product_variant->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('variant not found');
        }
    }

    public function variantDetails($product_variant_id)
    {
        try {
            return new ProductVariantResource(ProductVariant::findOrFail($product_variant_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('variant not found');
        }
    }
       // variant options

    public function options($product_variant_id)
    {
        try {
            $variant = ProductVariant::with('options.value.option')->findOrFail($product_variant_id);
            return response(['data' => array_reverse($variant->options->toArray())],200);
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('variant not found');
        }
    }

    public function storeOptions(StoreProductVariantOptionsRequest $request)
    {
        if($request->validated())
        {
            $product_variant = ProductVariant::find($request->product_variant_id);
                collect($request->options)->map(function ($option) use ($product_variant){
                    $product_variant->options()->create($option);
                });
            return response(['message' => 'created !'],201);
        }
    }


    public function destroyOption($product_variant_option_value_id)
    {
        try {
            $product_variant_option_value = ProductVariantOptionValue::findOrFail($product_variant_option_value_id);
            if(!$product_variant_option_value->trashed())
            {
                $product_variant_option_value->forceDelete();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('option not found');
        }
    }


}
