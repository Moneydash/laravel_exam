<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('user')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/getUser/{email}', [UserController::class, 'getUser']);
    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::post('/updateUser/{id}', [UserController::class, 'updateUser']);
    Route::delete('/deletUser/{id}', [UserController::class, 'deleteUser']);
});

Route::prefix('role')->group(function() {
    Route::get('/getRoles', [RoleController::class, 'view']);
    Route::post('/addRole', [RoleController::class, 'create']);
    Route::post('/editRole/{id}', [RoleController::class, 'update']);
    Route::delete('/deleteRole/{id}', [RoleController::class, 'deleteRole']);
});