<?php

namespace App\Services\Rating;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class Rating
{
    public function timeInterval($time)
    {
        $time = Carbon::createFromTimestamp($time);

        if ($days = $time->diffInDays()) {
            return CarbonInterval::days($days)->cascade()->forHumans();
        } elseif ($hours = $time->diffInHours()) {
            return CarbonInterval::hours($hours)->cascade()->forHumans();
        } elseif ($minutes = $time->diffInMinutes()) {
            return CarbonInterval::minutes($minutes)->cascade()->forHumans();
        }
    }

    public function timeIntervalColor($time)
    {
        $time = Carbon::createFromTimestamp($time);

        if ($time->diffInDays()) {
            return 'secondary';
        } elseif ($time->diffInHours()) {
            return 'info';
        } elseif ($time->diffInMinutes()) {
            return 'success';
        }
    }

    public function blocksColor($value)
    {
        if ($value < -1) {
            return 'danger';
        } elseif ($value >= -1 && $value < 1) {
            return 'secondary';
        } else {
            return 'success';
        }
    }

    public function hashRateUnit($value)
    {
        $unit_eh = $value * 0.000000000000000001;
        $unit_ph = $value * 0.000000000000001;
        $unit_th = $value * 0.000000000001;
        $unit_gh = $value * 0.000000001;
        $unit_mh = $value * 0.000001;
        $unit_kh = $value * 0.001;

        if ($unit_eh >= 1) {
            return number_format($unit_eh, 2, '.', '') . ' Eh/s';
        } elseif ($unit_ph >= 1) {
            return number_format($unit_ph, 2, '.', '') . ' Ph/s';
        } elseif ($unit_th >= 1) {
            return number_format($unit_th, 2, '.', '') . ' Th/s';
        } elseif ($unit_gh >= 1) {
            return number_format($unit_gh, 2, '.', '') . ' Gh/s';
        } elseif ($unit_mh >= 1) {
            return number_format($unit_mh, 2, '.', '') . ' Mh/s';
        } elseif ($unit_kh >= 1) {
            return number_format($unit_kh, 2, '.', '') . ' kh/s';
        }

        return number_format($value, 2, '.', '') . ' h/s';
    }
}
