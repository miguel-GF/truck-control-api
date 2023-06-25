<?php

use App\Http\Controllers\OperadorController;
use Illuminate\Support\Facades\Route;

Route::prefix('operadores')->group(function () {
	// GET
	Route::get('/', [OperadorController::class, 'listar']);

	// POST
	Route::post('/', [OperadorController::class, 'agregar']);
});
