<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
     //
     public $timestamps = false;

     protected $fillable = [
         'email', 'token'
     ];
}
