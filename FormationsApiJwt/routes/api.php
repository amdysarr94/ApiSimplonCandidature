<?php

use App\Models\Formation;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\FormationController;
use App\Http\Controllers\Api\CandidatureController;

// use App\Http\Controllers\Api\AuthController;


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

Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
// Route::post('refresh', [AuthController::class,'refresh']);
Route::post('/logout', [AuthController::class,'logout']);
// Le middleware de l'Admin //
Route::middleware(['auth:api','role:admin'])->group(function (){
    Route::post('/formation/register', [FormationController::class, 'store']);
    Route::put('/formation/update/{formation}', [FormationController::class, 'update']);
    Route::delete('/formation/delete/{formation}', [FormationController::class, 'destroy']);
    //Les routes d'affichage
    //Liste de toutes les formation
    Route::get('/formation/list', [FormationController::class, 'index']);
    //Les routes de gestion des candidatures

});
// Le middleware du candidat //
Route::middleware(['auth:api','role:candidat'])->group(function (){
    Route::post('/candidate', [CandidatureController::class, 'store']);
});