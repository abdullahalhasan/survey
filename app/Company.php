<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    public $table = "company";
    protected $fillable = [
        'name', 'description', 'address',
    ];
}
