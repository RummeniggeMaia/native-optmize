<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creative extends Model
{
   protected $fillable=[
        'name',
        'url',
        'image',
        'related_category',
    ];
}
