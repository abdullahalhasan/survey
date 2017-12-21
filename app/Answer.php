<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    public static function compareDeepValue($val1, $val2) {
        return strcmp($val1['value'], $val2['value']);
    }
}
