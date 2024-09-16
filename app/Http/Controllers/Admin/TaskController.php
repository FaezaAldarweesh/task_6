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
use App\Http\Requests\Admin\Update_Status_Tsak_Request;
use App\Http\Requests\Admin\Update_Notes_Tsak_Request;



class TaskController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $taskservices;
    /**
     * construct to inject task Services 
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
     * من أجل قولبة شكل الاستجابة المعادة TaskResources استخدام 
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
        return $this->success_Response(new TaskResources($response), "Task created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show task alraedy exist
     * @param  $task_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($task_id)
    {
        $task = $this->taskservices->view_task($task_id);

        // In case error messages are returned from the services section 
        if ($task instanceof \Illuminate\Http\JsonResponse) {
            return $task;
        }
            return $this->success_Response(new TaskResources($task), "task viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update task alraedy exist
     * @param  Update_Tsak_Request $request
     * @param  Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Tsak_Request $request, Task $task)
    {
        $updated = $this->taskservices->updateTask($request->validated(), $task);
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

        // In case error messages are returned from the services section 
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

        // In case error messages are returned from the services section 
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

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "task force deleted successfully", 200);
    }
        
    // //========================================================================================================================












    //========================================================================================================================
    /**
     * method to create task by manager
     * @param  Store_Task_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function create_task(Store_Task_Request $request)
    {
        $updated = $this->taskservices->insert_task($request->validated());

        // In case error messages are returned from the services section 
        if ($updated instanceof \Illuminate\Http\JsonResponse) {
            return $updated;
        }
            return $this->success_Response(new TaskResources($updated), "task created successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to update task by manager
     * @param  Update_Tsak_Request $request
     * @param  Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function Update_task(Update_Tsak_Request $request , Task $task)
    {
        $updated = $this->taskservices->edit_task($request->validated()  , $task);

        // In case error messages are returned from the services section 
        if ($updated instanceof \Illuminate\Http\JsonResponse) {
            return $updated;
        }
            return $this->success_Response(new TaskResources($updated), "task updated successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to update status to task by developer
     * @param  Update_Status_Tsak_Request $request
     * @param  $project_id
     * @param  $task_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function updated_Status(Update_Status_Tsak_Request $request , $project_id , $task_id)
    {
        $updated = $this->taskservices->updatedStatus($request->validated() , $project_id , $task_id);

        // In case error messages are returned from the services section 
        if ($updated instanceof \Illuminate\Http\JsonResponse) {
            return $updated;
        }
            return $this->success_Response(new TaskResources($updated), "task Status updated successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to update notes the task by tester
     * @param  Update_Notes_Tsak_Request $request
     * @param  $project_id
     * @param  $task_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function updated_Notes(Update_Notes_Tsak_Request $request , $project_id , $task_id)
    {
        $updated = $this->taskservices->updated_Notes($request->validated() , $project_id , $task_id);

        // In case error messages are returned from the services section 
        if ($updated instanceof \Illuminate\Http\JsonResponse) {
            return $updated;
        }
            return $this->success_Response(new TaskResources($updated), "task notes updated successfully", 200);
    }
    //========================================================================================================================
}
