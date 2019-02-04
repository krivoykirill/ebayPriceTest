<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $fillable = [
        'id','username','type','type_value'
    ];
    protected $table = 'user_queries';
    public $timestamps=false;

}
