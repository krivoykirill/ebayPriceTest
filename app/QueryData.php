<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueryData extends Model
{
    protected $table = 'query_data';
    protected $casts = [
        'data'=>'array'
    ];
}
