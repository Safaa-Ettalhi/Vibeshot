<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function shortDiffForHumans(Carbon $date)
    {
        $now = Carbon::now();
        $diff = $date->diffInSeconds($now);
        
        if ($diff < 60) {
            return $diff . 's';
        }
        
        if ($diff < 3600) {
            return floor($diff / 60) . 'm';
        }
        
        if ($diff < 86400) {
            return floor($diff / 3600) . 'h';
        }
        
        if ($diff < 604800) {
            return floor($diff / 86400) . 'd';
        }
        
        if ($diff < 2592000) {
            return floor($diff / 604800) . 'w';
        }
        
        if ($diff < 31536000) {
            return floor($diff / 2592000) . 'mo';
        }
        
        return floor($diff / 31536000) . 'y';
    }
}