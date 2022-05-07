<?php

namespace App\Models;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'price'
    ];

    public function getPriceAttribute()
    {
        $format = new Money($this->attributes['price'],new Currency(config('app.currency')));
        return $format->getAmount() . ' ' . $format->getCurrency();
    }

    public function options()
    {
        return $this->hasMany(ProductVariantOptionValue::class);
    }

}
