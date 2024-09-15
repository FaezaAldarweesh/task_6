<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Trait\ApiResponseTrait;
use App\Models\Project;
use Illuminate\Support\Facades\Request;

class TaskService {
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    public function getAllTAsks(){
        try {
            return Task::with('users')->get();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with fetche tasks', 400);
        }
    }
    //========================================================================================================================
    public function createTask($data) {
        try {
            $task = new Task;
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->status = $data['status'] ?? 'New';  
            $task->priority = $data['priority'] ?? 'Medium';
            $task->due_date = $data['due_date'];
            $task->project_id = $data['project_id'];  
            $task->notes = $data['notes'] ?? null;  
           
            $task->save(); 
    
            return $task; // تأكد من إعادة كائن المهمة نفسه
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response($th->getMessage(), 400);
        }
    }    
    //========================================================================================================================
    public function updateTask($data,Task $task){
        try {  
            $task->title = $data['title'] ?? $task->title;
            $task->description = $data['description'] ?? $task->description;
            $task->status = $data['status'] ?? $task->status;  
            $task->priority = $data['priority'] ?? $task->priority;
            $task->due_date = $data['due_date'] ?? $task->due_date;
            $task->project_id = $data['project_id'] ?? $task->project_id;  
            $task->notes = $data['notes'] ?? $task->notes;  
            $task->save();  

            return $task;
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response($th->getMessage(), 400);}
    }
    //========================================================================================================================
    public function view_task($task_id) {
        try {    
            $task = Task::find($task_id);
            if(!$task){
                throw new \Exception('task not found');
            }
            return $task;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with view task', 400);
        }
    }
    //========================================================================================================================
    public function deleteTask($task_id)
    {
        try {  
            $task = Task::find($task_id);
            if(!$task){
                throw new \Exception('task not found');
            }
            $task->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting task', 400);}
    }
    //========================================================================================================================

    public function restoreTask($task_id)
    {
        try {
            $task = Task::withTrashed()->find($task_id);
            if(!$task){
                throw new \Exception('task not found');
            }
            return $task->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore task', 400);
        }
    }
    //========================================================================================================================

    public function forceDeleteTask($task_id)
    {   
        try {
            $task = Task::find($task_id);
            if(!$task){
                throw new \Exception('task not found');
            }
            return $task->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting task', 400);}
    }
    //========================================================================================================================
    public function insert_task($data) {
        try {

            $project = Project::where('id', $data['project_id'])
                              ->with(['users' => function($query) {
                              $query->where('user_id', Auth::id());
            }])->first();
            $user_role =  $project->users->first()->pivot->role;

            if(!($user_role == 'admin' || $user_role == 'manager')){
                throw new \Exception('you do not have permisstion to create task,only admin or manager can do that');
            }else{   
            $task = new Task;
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->status = $data['status'];  
            $task->priority = $data['priority'];
            $task->due_date = $data['due_date'];
            $task->project_id = $data['project_id'];  
            $task->notes = $data['notes'];  
           
            $task->save();  
            }
            return $task;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage());  return $this->failed_Response($th->getMessage(), 400);}
    }
    //========================================================================================================================
    public function edit_task($data,Task $task){
        try {
            $project = Project::where('id', $data['project_id'])
                              ->with(['users' => function($query) {
                              $query->where('user_id', Auth::id());
            }])->first();
            $user_role =  $project->users->first()->pivot->role;

            if(!($user_role == 'admin' || $user_role == 'manager')){
                throw new \Exception('you do not have permisstion to update task,only admin or manager can do that');
            }else{   
            $task->title = $data['title'] ?? $task->title;
            $task->description = $data['description'] ?? $task->description;
            $task->status = $data['status'] ?? $task->status;  
            $task->priority = $data['priority'] ?? $task->priority;
            $task->due_date = $data['due_date'] ?? $task->due_date;
            $task->project_id = $data['project_id'] ?? $task->project_id;  
            $task->notes = $data['notes'] ?? $task->notes;  
            $task->save();  

            return $task;
        }
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response($th->getMessage(), 400);}
    }
    //========================================================================================================================
    public function updatedStatus($data , $project_id , $task_id)
    {   
        try {
            $task = Task::findOrFail($task_id);

            $project = Project::where('id', $project_id)
                              ->with(['users' => function($query) {
                              $query->where('user_id', Auth::id());
            }])->first();
            $user_role =  $project->users->first()->pivot->role;

            if($user_role == 'admin' || $user_role == 'manager' || $user_role == 'tester'){
                throw new \Exception('you do not have permisstion to uptade status of task,only developer can do that');
            }else{  
                $task->update($data);
            return $task;
            }
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with updating status', 400);}
    }
    //========================================================================================================================
    public function updated_Notes($data , $project_id , $task_id)
    {   
        try {
            $task = Task::findOrFail($task_id);

            $project = Project::where('id', $project_id)
                              ->with(['users' => function($query) {
                              $query->where('user_id', Auth::id());
            }])->first();
            $user_role =  $project->users->first()->pivot->role;

            if($user_role == 'admin' || $user_role == 'manager' || $user_role == 'developer'){
                throw new \Exception('you do not have permisstion to uptade the notes of task,only tester can do that');
            }else{  
                $task->update($data);
            return $task;
            }
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with updating status', 400);}
    }
    //========================================================================================================================



}
