<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');  
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    // protected routes go here
    Route::post('logout',[AuthController::class ,'logout']); 
    
    //only for admin
    Route::apiResource('user',UserController::class); 
    Route::get('restore_user/{user_id}', [UserController::class, 'restore']);
    Route::delete('forceDelete_user/{user_id}', [UserController::class, 'forceDelete']);
    
    Route::apiResource('project',ProjectController::class); 
    Route::get('restore_project/{project_id}', [ProjectController::class, 'restore']);
    Route::delete('forceDelete_project/{project_id}', [ProjectController::class, 'forceDelete']);
    
    Route::apiResource('task', TaskController::class)->middleware('manager_adminMiddleware'); 
    Route::get('restore_task/{task_id}', [TaskController::class, 'restore']);
    Route::delete('forceDelete_task/{task_id}', [TaskController::class, 'forceDelete']);
    
});
    



//Route::group(['middleware' => ['auth:api']], function () {
//});
