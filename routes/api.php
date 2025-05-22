<?php
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PersonajesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutes protegides pel middleware IsUserAuth
Route::middleware(['isUserAuth'])->group(function () {
    Route::get('/me', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/games', [GameController::class, 'index']);
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{game}', [GameController::class, 'update']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);
    Route::get('/ranking', [GameController::class, 'ranking']);
    Route::get('/games/user/{id}', [GameController::class, 'getGamesByUserId']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::post('/cards', [CardController::class, 'store']);
    Route::get('/cards/category/{categoryId}', [CategoryController::class, 'getByCategory']);
    Route::post('/cards', [CardController::class, 'store']);
    Route::get('/my-cards', [CardController::class, 'myCards']);
    Route::get('/public-cards', [CardController::class, 'publicCards']);
    Route::delete('/cards/{card}', [CardController::class, 'destroy']);
});

Route::middleware(['IsAdmin'])->group(function () {
    Route::get('/cards', [CardController::class, 'all']);
    Route::delete('/cards/{card}', [CardController::class, 'adminDestroy']);
    Route::put('/cards/{card}', [CardController::class, 'adminUpdate']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/personajes', [PersonajesController::class, 'addPersonaje']);
    Route::put('/personajes/{id}', [PersonajesController::class, 'updatePersonaje']);
    Route::delete('/personajes/{id}', [PersonajesController::class, 'deletePersonaje']);
});
