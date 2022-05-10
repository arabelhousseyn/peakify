<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ProductVariantOptionValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'option_value_id'
    ];

    public function value()
    {
        return $this->belongsTo(OptionValue::class,'option_value_id')->withDefault([]);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_id')->withDefault([]);
    }
}
