<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TodoTagsController;
use App\Http\Controllers\Api\TagTodosController;
use App\Http\Controllers\Api\PermissionController;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);

        Route::apiResource('users', UserController::class);

        Route::apiResource('todos', TodoController::class);

        // Todo Tags
        Route::get('/todos/{todo}/tags', [
            TodoTagsController::class,
            'index',
        ])->name('todos.tags.index');
        Route::post('/todos/{todo}/tags/{tag}', [
            TodoTagsController::class,
            'store',
        ])->name('todos.tags.store');
        Route::delete('/todos/{todo}/tags/{tag}', [
            TodoTagsController::class,
            'destroy',
        ])->name('todos.tags.destroy');

        Route::apiResource('tags', TagController::class);

        // Tag Todos
        Route::get('/tags/{tag}/todos', [
            TagTodosController::class,
            'index',
        ])->name('tags.todos.index');
        Route::post('/tags/{tag}/todos/{todo}', [
            TagTodosController::class,
            'store',
        ])->name('tags.todos.store');
        Route::delete('/tags/{tag}/todos/{todo}', [
            TagTodosController::class,
            'destroy',
        ])->name('tags.todos.destroy');
    });
