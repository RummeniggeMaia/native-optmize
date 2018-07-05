<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Segmentation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'device',
        'country',
        'campaingn_id,
    ];

}
