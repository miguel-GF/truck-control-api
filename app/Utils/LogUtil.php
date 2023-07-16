<?php

namespace App\Utils;
use Illuminate\Support\Facades\Log;
use ErrorException;

class LogUtil
{
  /**
   * Método para agregar linea de log
   * @return void
   */
  public static function log(string $tipo, ErrorException $e)
  {
    $message = $e->getMessage();
    $fila = $e->getFile();
    $line = $e->getLine();
    $logLine = "$message en el archivo $fila en la línea $line";
    switch ($tipo) {
      case 'info':
        Log::channel('stack')->info($logLine);
        break;
      case 'debug':
        Log::channel('stack')->debug($logLine);
        break;
      
      default:
        Log::channel('stack')->error($logLine);
        break;
    }
  }
}
