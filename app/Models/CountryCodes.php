<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryCodes extends Model
{
    public $table = 'countrycodes';
    protected $fillable = [
        'code',
        'name',
        'dial_code'
    ];
}
