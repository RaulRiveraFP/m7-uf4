<?php
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutes protegides pel middleware IsUserAuth
Route::middleware(['isUserAuth'])->group(function () {
    Route::get('/me', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/pets', [PetController::class, 'index']);
    Route::post('/pets', [PetController::class, 'store']);
    Route::put('/pets/{id}', [PetController::class, 'update']);
    Route::patch('/pets/{id}', [PetController::class, 'partialUpdate']);
    Route::delete('/pets/{id}', [PetController::class, 'destroy']);
});

Route::middleware(['IsAdmin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/{id}/pets', [UserController::class, 'getUserPets']);
});
