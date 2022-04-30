<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'product_code',
        'product_name',
        'description',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => __('messages.category_deleted')]);
    }
}
