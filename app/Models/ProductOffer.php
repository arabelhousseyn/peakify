<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ProductOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'quantity',
        'discount',
        'is_static'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
