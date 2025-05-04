<?php




use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\AIHelperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('goals', \App\Http\Controllers\GoalController::class);
    Route::post('/goals/{goal}/progress', [\App\Http\Controllers\GoalController::class, 'updateProgress']);
    Route::get('/map-data', [\App\Http\Controllers\GoalController::class, 'getMapData']);
    Route::post('/ai-suggestions', [\App\Http\Controllers\API\AIHelperController::class, 'generateSuggestions']);
});
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/goals', [\App\Http\Controllers\GoalController::class, 'apiIndex']);
Route::middleware('auth:sanctum')->post('/goals', [\App\Http\Controllers\GoalController::class, 'store']);
