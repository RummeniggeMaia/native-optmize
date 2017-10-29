<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreativeLog extends Model
{
    protected $fillable = [
        'article',
        'impressions',
        'clicks',
        'revenue',
    ];
}
