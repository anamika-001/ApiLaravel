<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'genders';
    protected $fillable = [
       'gender'
      ];
}
