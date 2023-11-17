<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//method register
Route::post('/register', [AuthController::class, 'register']);

//method login
Route::post('/login', [AuthController::class, 'login']);

//method get detail resource
Route::get('/employees/{id}',[EmployeeController::class, 'show']);

//method get resource
Route::get('/employees',[EmployeeController::class, 'index'])->middleware('auth:sanctum');

//method post resource
Route::post('/employees',[EmployeeController::class, 'store']);

//method put resource
Route::put('/employees/{id}', [EmployeeController::class, 'update']);

//method delete resource
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

//method search resource
Route::get('/employees/search/{name}', [EmployeeController::class, 'search']);

//method status resource
Route::get('/employees/status/{status}', [EmployeeController::class, 'status']);