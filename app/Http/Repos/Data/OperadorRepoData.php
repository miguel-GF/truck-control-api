<?php

namespace App\Http\Repos\Data;

use App\Http\Repos\Filter\OperadorFilter;
use Illuminate\Support\Facades\DB;

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
      // $operadores = Operador::all();
      // OperadorFilter::filtrosListar($operadores, (array) $filtros);
      // return $operadores->toArray();
      $query = DB::table('operadores')
        ->select('*');
      OperadorFilter::filtrosListar($query, (array) $filtros);
      return $query ? $query->get()->toArray() : [];
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
