<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class OrderProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_product_id',
        'product_variant_option_value_id'
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariantOptionValue::class,'product_variant_option_value_id')
            ->withDefault([]);
    }
}
