<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class OptionValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'option_id',
        'value'
    ];

    public function option()
    {
        return $this->belongsTo(Option::class,'option_id')->withDefault([]);
    }
}
