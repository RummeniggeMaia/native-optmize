<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaingn extends Model
{
    //fillable fields
    protected $fillable = ['name', 'brand'];
    
    //custom timestamps name
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}
