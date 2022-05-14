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
        'created_by',
        'quantity',
        'discount',
        'is_static'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by')
            ->withDefault(['full_name' => __('messages.user_deleted')]);
    }

    protected $hidden = [
        'product_id'
    ];
}
