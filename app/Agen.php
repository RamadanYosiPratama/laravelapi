<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agen extends Model
{
    protected $table = 'agen';
    protected $primaryKey = 'kd_agen';
    protected $fillable = [
        'store_name',
        'store_owner',
        'address',
        'latitude',
        'longitude',
        'photo_store'
    ];
}
