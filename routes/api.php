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


Route::group(['middleware' => ['auth:api']], function () {

    // protected routes go here
    Route::post('logout',[AuthController::class ,'logout']); 
    
    //only for admin
    Route::apiResource('user',UserController::class); 
    Route::get('restore_user/{user_id}', [UserController::class, 'restore']);
    Route::delete('forceDelete_user/{user_id}', [UserController::class, 'forceDelete']);
    
    Route::apiResource('project',ProjectController::class); 
    Route::get('restore_project/{project_id}', [ProjectController::class, 'restore']);
    Route::delete('forceDelete_project/{project_id}', [ProjectController::class, 'forceDelete']);
    
    Route::apiResource('task', TaskController::class); 
    Route::get('restore_task/{task_id}', [TaskController::class, 'restore']);
    Route::delete('forceDelete_task/{task_id}', [TaskController::class, 'forceDelete']);


    //only for manager
    Route::post('create_task/{project_id}/{task_id}', [TaskController::class, 'create_task']);
    Route::put('Update_task/{project_id}/{task_id}', [TaskController::class, 'Update_task']);
    
    //only for developer
    Route::put('updated_Status/{project_id}/{task_id}', [TaskController::class, 'updated_Status']);

    //only for tester
    Route::put('updated_Notes/{project_id}/{task_id}', [TaskController::class, 'updated_Notes']);

    Route::get('all_tasks', [TaskController::class, 'updated_Notes']);


});
    



//});
