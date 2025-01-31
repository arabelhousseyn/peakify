<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GenerateOrderNumberService;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::withoutTrashed()->with('client','channel','createdBy')->paginate(15);
        return response($orders,200);
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
    public function store(StoreOrderRequest $request)
    {
        if($request->validated())
        {
            $data = [
                'created_by' => Auth::id(),
                'order_number' => (new GenerateOrderNumberService())->setType($request->type)->generate()
            ];
            // first case
            $order = Order::create(array_merge($request->all(),$data));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->update($request->all());
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if(!$order->trashed())
        {
            $order->delete();
            return response()->noContent();
        }
    }

    public function restore($order_id)
    {
        try {
            $order = Order::findOrFail($order_id);
            $order->restore();
            return response()->noContent();
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('order not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $orders = Order::withTrashed()->latest('created_at')->paginate(15);
                return response($orders,200);
                break;
            case 1 :
                $orders = Order::onlyTrashed()->latest('created_at')->paginate(15);
                return response($orders,200);
                break;
        }
    }
}
