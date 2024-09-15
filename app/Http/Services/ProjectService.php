<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
use App\Http\Trait\ApiResponseTrait;

class ProjectService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    public function get_all_projects(){
        try {
            return Project::query()
                        ->with('users')
                        ->orderBy('name')
                        ->paginate();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with fetche all projects', 400);
        }
    }
    //========================================================================================================================
    public function create_project($data) {
        try {    
            $project = new Project();
            $project->name = $data['name'];
            $project->description = $data['description'];
            $project->save();

            foreach($data['users'] as $user){
                $user_id = $user['id'];
                $user_role = $user['role'];
                $project->users()->attach($user_id,['role' => $user_role]);
            }

            $project->save();

            return $project;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with creating project', 400);
        }
    }      
    //========================================================================================================================
    public function view_project($project_id) {
        try {    
            $project = Project::find($project_id);
            if(!$project){
                throw new \Exception('project not found');
            }
            return $project;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with creating project', 400);
        }
    }
    //========================================================================================================================
    public function update_Project($data , Project $project){
        try {
            $project->name = $data['name'] ?? $project->name;
            $project->description = $data['description'] ?? $project->description;
            $project->save();

            $user_array = [];
            foreach($data['users'] as $user){
                $user_id = $user['id'];
                $user_role = $user['role'];
                $user_array[$user_id] = ['role' => $user_role];
            }
            
            $project->save();
            $project->users()->sync($user_array);

            return $project;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with updating project', 400);
        }
    }
    //========================================================================================================================
    public function delete_Project($project_id)
    {
        try {  
            $project = Project::find($project_id);
            if(!$project){
                throw new \Exception('project not found');
            } 
            $project->delete();
            return true;
            //catch error expception
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting project', 400);}
    }
    //========================================================================================================================

    public function restore_Project($project_id)
    {
        try {
            $project = Project::withTrashed()->find($project_id);
            if(!$project){
                throw new \Exception('project not found');
            }

            return $project->restore();
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore project', 400);}
    }
    //========================================================================================================================

    public function force_delete_Project($project_id)
    {
        try {
            $project = Project::find($project_id);
            if(!$project){
                throw new \Exception('project not found');
            }
            return $project->forceDelete();
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting project', 400);}
    }
}