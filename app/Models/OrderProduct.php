<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'has_variants'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id')->withDefault([]);
    }

    public function variants()
    {
        return $this->hasMany(OrderProductVariant::class);
    }
}
