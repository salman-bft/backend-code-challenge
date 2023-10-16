<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToDoController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {

});

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/verify-code', [VerificationController::class,'codeVerification']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('todos')->group(function () {
    Route::get('/', [ToDoController::class,'index']);
    Route::post('/', [ToDoController::class,'store']);
    Route::get('/{todo}', [ToDoController::class,'show']);
    Route::put('/{todo}', [ToDoController::class,'update']);
    Route::delete('/{todo}', [ToDoController::class,'destroy']);
});
});

Route::get('/test', function () {
    return "test()";
});
