<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\TaskResources;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectLatestTaskResources extends JsonResource
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
            'the task' => new TaskResources($this->latestTask),
        ];
    }
    
}
