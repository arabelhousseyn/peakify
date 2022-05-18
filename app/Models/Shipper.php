<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Shipper extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'type',
        'city_id'
    ];

    public function cities()
    {
        return $this->hasMany(ShipperCity::class);
    }

    public function defaultCity()
    {
        return $this->belongsTo(City::class,'city_id')->withDefault([]);
    }
}
