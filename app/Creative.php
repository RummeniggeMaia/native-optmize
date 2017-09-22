<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creative extends Model
{
    //fillable fields
    protected $fillable = ['name', 'url', 'image'];
    
    //custom timestamps name
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}
