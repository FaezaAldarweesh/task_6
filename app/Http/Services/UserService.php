<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Trait\ApiResponseTrait;
use App\Http\Resources\TaskResources;


class UserService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all user
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_users(){
        try {
            return User::all();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with fetche all users', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to store a new User
     * @param  $data
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function create_user($data) {
        try {    
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];  
            $user->role = 'user';
            $user->save();

            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with creating user', 400);
        }
    }
          
    //========================================================================================================================
    /**
     * method to show user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_user($user_id) {
        try {    
            $user = User::find($user_id);
            if(!$user){
                throw new \Exception('user not found');
            }
            return $user;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with creating user', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to update user alraedy exist
     * @param  $data
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_User($data,User $user){
        try {
            $user->name = $data['name'] ?? $user->name;
            $user->email = $data['email'] ?? $user->email;
            $user->password = $data['password'] ?? $user->password;
            $user->role = 'user';
            $user->save();

            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with updating user', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to soft delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */    
    public function delete_User($user_id)
    {
        try {  
            $user = User::find($user_id);
            if(!$user){
                throw new \Exception('user not found');
            } 
            //منع الأدمن من إزالة حسابه
            if ($user->role == 'Admin') {
                throw new \Exception('You cannot soft delete admin account');
            }
            $user->delete();
            return true;
            //catch error expception
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting user', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_User($user_id)
    {
        try {
            $user = User::withTrashed()->find($user_id);
            if(!$user){
                throw new \Exception('user not found');
            }
            //البحث عن المستخدم المراد إعادته من ضمن المستخدمين المحذوفين مؤقتاً
            return $user->restore();
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore user', 400);}
    }
    //========================================================================================================================
        /**
     * method to force delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function force_delete_User($user_id)
    {
        try {
            $user = User::find($user_id);
            if(!$user){
                throw new \Exception('user not found');
            }
             //منع الأدمن من إزالة حسابه
            else if ($user->role == 'Admin') {
                throw new \Exception('You cannot delete admin account');
            }
            return $user->forceDelete();
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting user', 400);}
    }
    //========================================================================================================================

    /**
     * method to get all task that belongs to user
     * @return /Illuminate\Http\JsonResponse if have an error
     */  
    public function all_tasks()
    {
        try {
            //catch the login user
            $user = Auth::user();
            //then call relation tasks to return all tasks that belongs to the user
            $tasks = $user->tasks; 

            if ($tasks->isEmpty()) {
                throw new \Exception('You do not have any tasks');
            }
            return $tasks;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('something went wrong with fetching all tasks', 400);}
    }
    //========================================================================================================================
}
