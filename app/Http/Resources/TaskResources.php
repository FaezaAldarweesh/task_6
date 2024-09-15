<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'task id' => $this->id,
            'task title' => $this->title,
            'task description' => $this->description,
            'task status' => $this->status,
            'task priority' => $this->priority,
            'task due_date' => $this->due_date,
            'the project'=> $this->project_id,
            'task notes'=> $this->notes,
        ];
    }
    
}
