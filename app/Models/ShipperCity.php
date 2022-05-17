<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ShipperCity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipper_id',
        'city_id',
        'price',
        'type'
    ];

    public function shipper()
    {
        return $this->belongsTo(Shipper::class,'shipper_id')->withDefault([]);
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id')->withDefault([]);
    }
}
