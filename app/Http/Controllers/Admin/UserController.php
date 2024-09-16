<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Trait\ApiResponseTrait;
use App\Http\Resources\UserResources;
use App\Http\Requests\Admin\Store_User_Request;
use App\Http\Requests\Admin\Update_User_Request;
use App\Http\Resources\TaskResources;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $userservices;
    /**
     * construct to inject User Services 
     * @param UserService $userservices
     */
    public function __construct(UserService $userservices)
    {
        $this->userservices = $userservices;
    }
    //===========================================================================================================================
    /**
     * method to view all user
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة UserResources استخدام 
     */
    public function index()
    {  
        $users = $this->userservices->get_all_users();
        return $this->success_Response(UserResources::collection($users), "All Users fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new User
     * @param  Store_User_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_User_Request $request)
    {
        $response = $this->userservices->create_user($request->validated());
        return $this->success_Response(new UserResources($response), "user created successfully.", 201);
    }
    //===========================================================================================================================
    /**
     * method to show user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($user_id)
    {
        $user = $this->userservices->view_user($user_id);

        // In case error messages are returned from the services section 
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
            return $this->success_Response(new UserResources($user), "user viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update user alraedy exist
     * @param  Update_User_Request $request
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_User_Request $request, User $user)
    {
        $updatedUser = $this->userservices->update_User($request->validated(), $user);
        return $this->success_Response(new UserResources($updatedUser), "user updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($user_id)
    {
        $user = $this->userservices->delete_User($user_id);

        // In case error messages are returned from the services section 
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
            return $this->success_Response(null, "user soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($user_id)
    {
        $user = $this->userservices->restore_User($user_id);

        // In case error messages are returned from the services section 
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
            return $this->success_Response(null, "user restored successfully", 200);
    }
    //========================================================================================================================
        /**
     * method to force delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($user_id)
    {
        $user = $this->userservices->force_delete_User($user_id);

        // In case error messages are returned from the services section 
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
            return $this->success_Response(null, "user force deleted successfully", 200);
    }
        
    //========================================================================================================================
    /**
     * method to get all task that belongs to user
     * @return /Illuminate\Http\JsonResponse
     */
    public function all_tasks()
    {
        $tasks = $this->userservices->all_tasks();

        // In case error messages are returned from the services section 
        if ($tasks instanceof \Illuminate\Http\JsonResponse) {
            return $tasks;
        }
            return $this->success_Response(TaskResources::collection($tasks), "All tasks fetched successfully", 200);
    }    
    //========================================================================================================================

}
