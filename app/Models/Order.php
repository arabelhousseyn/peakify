<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'client_id',
        'channel_id',
        'type',
        'order_number',
        'total_price',
        'shipping_code',
        'internal_note',
        'external_note',
        'confirmed_by',
        'order_status',
        'delivery_status',
        'payment_status',
        'bar_code',
        'status',
        'created_by'
    ];

    public function order()
    {
        $this->belongsTo(Order::class,'order_id')->withDefault([]);
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id')->withDefault([]);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_id')->withDefault([]);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by')->withDefault([]);
    }
}
