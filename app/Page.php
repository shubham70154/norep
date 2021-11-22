<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'query_title',
        'sub_title',
        'description'
    ];
}
