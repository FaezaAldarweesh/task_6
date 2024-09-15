<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'project id' => $this->id,
            'project name' => $this->name,  
            'project description' => $this->description,
            'project users' => $this->users->map(function($user) {
                return [
                    'user id' => $user->id,
                    'user name' => $user->name,
                    'user email' => $user->email,
                    'user role' => $user->pivot->role,
                    'user contribution hours' => $user->pivot->contribution_hours,
                    'user last activity' => $user->pivot->last_activity,
                ];
            }),
        ];
    }
    
}
