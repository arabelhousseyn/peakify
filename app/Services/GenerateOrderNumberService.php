<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Traits\FormatDateTrait;
class GenerateOrderNumberService{
    use FormatDateTrait;
    public string $type;

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function generate()
    {
        $last_order = Order::withTrashed()->latest('created_at')->limit(1)->first();
        $now = Carbon::now();
        $last_order_date = Carbon::parse($last_order->created_at);
        $format = $this->formatDateForOrder($now->format('Y-m-d'));

        if($now->month == $last_order_date->month)
        {
            $order_number = Order::withTrashed()->count() + 1;
        }elseif($now->month > $last_order_date->month){
            $order_number = 1;
        }

        if($this->type == 'N')
        {
            $prefix = 'FA';
        }

        $order = $prefix . $format . strval($order_number);
        return $order;
    }

}
