<?php

use App\Http\Controllers\GastoDirectoController;
use App\Http\Controllers\DeduccionController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\NominaController;
use Illuminate\Support\Facades\Route;

Route::prefix('operadores')->group(function () {
	// GET
	Route::get('/', [OperadorController::class, 'listar']);

	// POST
	Route::post('/', [OperadorController::class, 'agregar']);

	// PATCH
	Route::patch('/{id}', [OperadorController::class, 'editar']);
	
	// DELETE
	Route::delete('/{id}', [OperadorController::class, 'eliminar']);
});

Route::prefix('gastos')->group(function () {

	Route::prefix('directos')->group(function () {
		// GET
		Route::get('/', [GastoDirectoController::class, 'listar']);
	
		// POST
		Route::post('/', [GastoDirectoController::class, 'agregar']);
	
		// PATCH
		Route::patch('/{id}', [GastoDirectoController::class, 'editar']);
		
		// DELETE
		Route::delete('/{id}', [GastoDirectoController::class, 'eliminar']);
	});
});

Route::prefix('deducciones')->group(function () {
	// GET
	Route::get('/', [DeduccionController::class, 'listar']);
	
	// POST
	Route::post('/', [DeduccionController::class, 'agregar']);
	
	// PATCH
	Route::patch('/{id}', [DeduccionController::class, 'editar']);
		
	// DELETE
	Route::delete('/{id}', [DeduccionController::class, 'eliminar']);
});

Route::prefix('nominas')->group(function () {
	// GET
	//Route::get('/', [OperadorController::class, 'listar']);

	// POST
	Route::post('/', [NominaController::class, 'agregar']);

	// PATCH
	//Route::patch('/{id}', [OperadorController::class, 'editar']);
	
	// DELETE
	//Route::delete('/{id}', [OperadorController::class, 'eliminar']);
});