<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Http\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResources;
use App\Http\Trait\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Store_Task_Request;
use App\Http\Requests\Admin\Update_Tsak_Request;
use App\Http\Requests\Admin\UpdateTsakRequest;
use App\Http\Requests\Admin\UpdateTsakUserRequest;



class TaskController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $taskservices;
    /**
     * construct to inject  Services 
     * @param taskService $taskservices
     */
    public function __construct(TaskService $taskservices)
    {
        $this->taskservices = $taskservices;
    }
    //===========================================================================================================================
    /**
     * method to view all tasks
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة UserResources استخدام 
     */
    public function index(Request $request)
    {  
        $users = $this->taskservices->getAllTAsks($request->input('priority'),$request->input('status'));
        return $this->success_Response(TaskResources::collection($users), "All tasks fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new task
     * @param   Store_Task_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Task_Request $request)
    {
        $response = $this->taskservices->createTask($request->validated());
        //التحقق في ما إذا كان السيرفس يعيد رسائل خطأ من أجل طباعتها
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response;
        }
            return $this->success_Response(new TaskResources($response), "Task created successfully.", 201);
        
    }
    
    //===========================================================================================================================
    /**
     * method to show task alraedy exist
     * @param  Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        return $this->success_Response(new TaskResources($task), "task viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update task alraedy exist
     * @param   Update_Tsak_Request $request
     * @param  Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Tsak_Request $request, Task $task)
    {
        $updated = $this->taskservices->updateTask($request->validated(), $task);
        //التحقق في ما إذا كان السيرفس يعيد رسائل خطأ من أجل طباعتها
        if ($updated instanceof \Illuminate\Http\JsonResponse) {
            return $updated;
        }
            return $this->success_Response(new TaskResources($updated), "task updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete task alraedy exist
     * @param  Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($task_id)
    {
        $delete = $this->taskservices->deleteTask($task_id);
        //التحقق في ما إذا كان السيرفس يعيد رسائل خطأ من أجل طباعتها
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "task soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft delete task alraedy exist
     * @param   $task_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($task_id)
    {
        $delete = $this->taskservices->restoreTask($task_id);
        //التحقق في ما إذا كان السيرفس يعيد رسائل خطأ من أجل طباعتها
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "task restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete task alraedy exist
     * @param   $task_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($task_id)
    {
        $delete = $this->taskservices->forceDeleteTask($task_id);
        //التحقق في ما إذا كان السيرفس يعيد رسائل خطأ من أجل طباعتها
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "task force deleted successfully", 200);
    }
        
    // //========================================================================================================================
    // /**
    //  * method to assign task alraedy exist to employee
    //  * @param   UpdateTsakUserRequest $request
    //  * @param   $task_id
    //  * @return /Illuminate\Http\JsonResponse
    //  */
    // public function AssignTask(UpdateTsakUserRequest $request , $task_id)
    // {
    //     $updated = $this->taskservices->AssignTask($request->validated() , $task_id);
    //     if ($updated instanceof \Illuminate\Http\JsonResponse) {
    //         return $updated;
    //     }
    //         return $this->SuccessResponse(new TaskResources($updated), "task Assign Task successfully", 200);
    // }
        
    // //========================================================================================================================
    //         /**
    //  * method to update status to task by employee
    //  * @param   UpdateStatusTsakRequest $request
    //  * @param  $task_id
    //  * @return /Illuminate\Http\JsonResponse
    //  */
    // public function updatedStatus(UpdateStatusTsakRequest $request , $task_id)
    // {
    //     $updated = $this->taskservices->updatedStatus($request->validated() , $task_id);
    //     if ($updated instanceof \Illuminate\Http\JsonResponse) {
    //         return $updated;
    //     }
    //         return $this->success_Response(new TaskResources($updated), "task updated Status successfully", 200);
    // }
        
    // //========================================================================================================================

}
