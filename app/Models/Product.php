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
        'price',
        'created_by'
    ];

    protected $hidden = ['category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => __('messages.category_deleted')]);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by')
            ->withDefault(['full_name' => __('messages.user_deleted')]);
    }
}
