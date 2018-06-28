<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends BaseModel 
{
    protected $table = 'industries';

    protected $fillable = [
        'name',
        'code',
        'url',
    ];

}
