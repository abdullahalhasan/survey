<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
class Common extends Model
{
    //
    public static function getAllQuestionInputType()
    {
        $inputs = \DB::table('question_input_type')
            ->select('id','input_type_name','input_type_value')
            ->get();
        return $inputs;
    }

    /**
     * Convert data time to sec, min, hour, day, month, year.
     *
     * @param date $datetime
     * @param bool $full
     * @return string
     */
    public static function TiemElapasedString($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

}
