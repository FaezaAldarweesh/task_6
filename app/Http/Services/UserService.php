<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Trait\ApiResponseTrait;


class UserService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    public function get_all_users(){
        try {
            return User::all();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->failed_Response('Something went wrong with fetche all users', 400);
        }
    }
    //========================================================================================================================
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
}
