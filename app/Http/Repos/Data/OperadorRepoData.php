<?php

namespace App\Http\Repos\Data;

use App\Http\Repos\RH\OperadorRH;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
class OperadorRepoData
{
  /**
   * listar
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listar(array $filtros): array
  {
    try {
      $query = DB::table('operadores')
        ->select('*');
      OperadorRH::filtrosListar($query, (array) $filtros);
      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en operadores -> $e");
      throw new Exception("Error al listar operadores");
    }
  }
}
