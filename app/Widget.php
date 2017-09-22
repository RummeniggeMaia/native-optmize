<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    //fillable fields
    protected $fillable = ['name', 'url'];
    
    //custom timestamps name
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}
