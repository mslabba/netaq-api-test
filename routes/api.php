<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnrolmentController;

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
Route::post('login', 'AuthController@login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['jwt.auth', 'manage-enrolments'])->group(function () {
    Route::post('enrolments', [EnrolmentController::class, 'store']);
    Route::get('enrolments/{enrolmentId}', [EnrolmentController::class, 'get']);
    Route::put('enrolments/{enrolmentId}', [EnrolmentController::class, 'update']);
    Route::get('courses/{courseId}/enrolments', [EnrolmentController::class, 'listByCourse']);
    Route::get('users/{userId}/enrolments', [EnrolmentController::class, 'listByUser']);
    Route::delete('/enrolments/{id}', [EnrolmentController::class, 'destroy']);
});