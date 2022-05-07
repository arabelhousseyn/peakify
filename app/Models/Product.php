<?php

namespace App\Models;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Prophecy\Doubler\Generator\TypeHintReference;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'product_code',
        'product_name',
        'description',
        'price',
        'has_variants',
        'has_offers',
        'created_by'
    ];

    protected $hidden = ['category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => __('messages.category_deleted')]);
    }

    public function offers()
    {
        return $this->hasMany(ProductOffer::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by')
            ->withDefault(['full_name' => __('messages.user_deleted')]);
    }

    public function getPriceAttribute()
    {
        $format = new Money($this->attributes['price'],new Currency(config('app.currency')));
        return $format->getAmount() . ' ' . $format->getCurrency();
    }
}
