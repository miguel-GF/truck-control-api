<?php

use App\Http\Controllers\OperadorController;
use Illuminate\Support\Facades\Route;

Route::prefix('operadores')->group(function () {
	Route::get('/', [OperadorController::class, 'listarMobile']);
});
