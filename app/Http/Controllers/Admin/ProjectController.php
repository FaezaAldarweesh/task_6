<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Trait\ApiResponseTrait;
use App\Http\Services\ProjectService;
use App\Http\Resources\ProjectResources;
use App\Http\Resources\ProjectOldestTaskResources;
use App\Http\Resources\ProjectLatestTaskResources;
use App\Http\Requests\Admin\Store_Project_Request;
use App\Http\Requests\Admin\Update_Project_Request;



class ProjectController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $projectservice;
    /**
     * construct to inject project Services 
     * @param ProjectService $projectservice
     */
    public function __construct(ProjectService $projectservice)
    {
        $this->projectservice = $projectservice;
    }
    //===========================================================================================================================
    /**
     * method to view all project
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة ProjectResources استخدام 
     */
    public function index()
    {  
        $projects = $this->projectservice->get_all_projects();
        return $this->success_Response(ProjectResources::collection($projects), "All projects fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new project
     * @param  Store_Project_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Project_Request $request)
    {
        $response = $this->projectservice->create_project($request->validated());
        return $this->success_Response(new ProjectResources($response), "project created successfully.", 201);
    }
    //===========================================================================================================================
    /**
     * method to show project alraedy exist
     * @param  $project_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($project_id)
    {
        $project = $this->projectservice->view_project($project_id);

        // In case error messages are returned from the services section 
        if ($project instanceof \Illuminate\Http\JsonResponse) {
            return $project;
        }
            return $this->success_Response(new ProjectResources($project), "project viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update project alraedy exist
     * @param  Update_Project_Request $request
     * @param  Project $project
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Project_Request $request, Project $project)
    {
        $updatedProject = $this->projectservice->update_Project($request->validated(),$project);
        return $this->success_Response(new ProjectResources($updatedProject), "project updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete project alraedy exist
     * @param  $project_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($project_id)
    {
        $project = $this->projectservice->delete_Project($project_id);

        // In case error messages are returned from the services section 
        if ($project instanceof \Illuminate\Http\JsonResponse) {
            return $project;
        }
            return $this->success_Response(null, "project soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft delete project alraedy exist
     * @param  $project_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($project_id)
    {
        $project = $this->projectservice->restore_Project($project_id);
        
        // In case error messages are returned from the services section 
        if ($project instanceof \Illuminate\Http\JsonResponse) {
            return $project;
        }
            return $this->success_Response(null, "project restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete project alraedy exist
     * @param  $project_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($project_id)
    {
        $project = $this->projectservice->force_delete_Project($project_id);

        // In case error messages are returned from the services section 
        if ($project instanceof \Illuminate\Http\JsonResponse) {
            return $project;
        }
            return $this->success_Response(null, "project force deleted successfully", 200);
    }
        
    //========================================================================================================================





    
    //========================================================================================================================
    /**
     * method to return oldest task from the project
     * @param  $project_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function oldest_task($project_id)
    {
        $old_task = $this->projectservice->oldest_task($project_id);

        // In case error messages are returned from the services section 
        if ($old_task instanceof \Illuminate\Http\JsonResponse) {
            return $old_task;
        }
            return $this->success_Response( new ProjectOldestTaskResources($old_task), "oldest task fitched successfully", 200);
    }
    //========================================================================================================================
        /**
     * method to return latest task from the project
     * @param  $project_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function latest_task($project_id)
    {
        $last_task = $this->projectservice->latest_task($project_id);

        // In case error messages are returned from the services section 
        if ($last_task instanceof \Illuminate\Http\JsonResponse) {
            return $last_task;
        }
            return $this->success_Response( new ProjectLatestTaskResources($last_task), "latest task fitched successfully", 200);
    }
    //========================================================================================================================
        /**
     * method to return Very important task
     * @return /Illuminate\Http\JsonResponse
     */
    public function Very_important_task()
    {
        $task = $this->projectservice->Very_important_task();

        // In case error messages are returned from the services section 
        if ($task instanceof \Illuminate\Http\JsonResponse) {
            return $task;
        }
            return $this->success_Response($task, "very important task fitched successfully", 200);
    }
    //========================================================================================================================
}
