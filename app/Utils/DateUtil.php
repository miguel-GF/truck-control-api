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
  public static function now(bool $mostrarHora = true)
  {
    $carbon = new Carbon();
    if ($mostrarHora) 
      return $carbon->now();
    else
      return $carbon->now()->format('Y-m-d');
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

  public static function sumarDias($fecha, $dias){
    $newDate = Carbon::parse($fecha)->setTimezone(env('TIME_ZONE'));
    return $newDate->addDays($dias);
  }
}
