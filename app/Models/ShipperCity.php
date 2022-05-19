<?php

namespace App\Models;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ShipperCity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipper_id',
        'city_id',
        'price'
    ];

    protected $appends = ['priceValue'];

    public function shipper()
    {
        return $this->belongsTo(Shipper::class,'shipper_id')->withDefault([]);
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id')->withDefault([]);
    }

    public function getPriceAttribute()
    {
        $format = new Money($this->attributes['price'],new Currency(config('app.currency')));
        return $format->getAmount() . ' ' . $format->getCurrency();
    }

    public function getPriceValueAttribute()
    {
        return $this->attributes['price'];
    }
}
