<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrosController;

Route::apiResource('registers', RegisterController::class);

/*
Route::post('/registro', [RegistrosController::class, 'criarRegistro']);
Route::get('/registro', [RegistrosController::class, 'listarTodosRegistros']);
//Route::get('/registro/{id}', [RegistrosController::class, 'listarRegistro']);
Route::post('/editando', [RegistrosController::class, 'alterarRegistro']);
Route::delete('/registro/{id}', [RegistrosController::class, 'deletandoRegistro']);
 */
