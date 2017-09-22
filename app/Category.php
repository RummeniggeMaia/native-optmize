<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //fillable fields
    protected $fillable = ['title', 'content'];
    
    //custom timestamps name
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
}
