<?php

namespace App\Utils;

use Carbon\Carbon;

class DateUtil
{
  /**
   * now
   *
   * @return mixed
   */
  public static function now()
  {
    $carbon = new Carbon();
    return $carbon->now();
  }

  /**
   * parsear
   *
   * @param  mixed $fecha
   * @return mixed
   */
  public static function parsear($fecha)
  {
    $newDate = Carbon::parse($fecha)->setTimezone(env('TIME_ZONE'));
    return $newDate->format('Y-m-d H:i:s');
  }
}
