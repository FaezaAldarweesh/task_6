<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class TaskService {
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    public function getAllTAsks($priority,$status){
        try {
            //إعادة جميع المهام و استخدام سكوب فلتر في حالة أراد الأدمن أو المدير الفلترة حسب الحالة للمهة أو درجة أهميتها
            return Task::filter($priority,$status)->get();
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
            $task->status = $data['status'];  
            $task->priority = $data['priority'];
            $task->due_date = $data['due_date'];
            $task->project_id = $data['project_id'];  
            $task->notes = $data['notes'];  
            $task->save();  

            return $task;
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
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response($th->getMessage(), 400);
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
    // //========================================================================================================================
    // public function AssignTask($data, $task_id)
    // {
    //     try {
    //         $task = Task::findOrFail($task_id);
            
    //         if (isset($data['user_id']) && !empty($data['user_id'])) {
    //             $user = User::findOrFail($data['user_id']);

    //             //التحقق من عدم إستاد المهمة لمستخدم ليس موظفاً
    //             if ($user->role != 'employee') {
    //                 throw new \Exception('You cannot assign a task to a manager, only to employees');
    //             }
    //         }
    
    //         $task->update($data);
    //         return $task;
    
    //     }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
    //     } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with assign task', 400);}
    // }
    // //========================================================================================================================
    // public function updatedStatus($data , $task_id)
    // {   
    //     try {
    //         $task = Task::findOrFail($task_id);
    //         if($task->user_id != Auth::id()){
    //             throw new \Exception('You cannot update the status of this task because its not belongs to you');
    //         }
    //             $task->update($data);
    //         return $task;
    //     }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
    //     } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with updating status', 400);}
    // }
    // //========================================================================================================================



}
