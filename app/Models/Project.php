<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users (){
        
        return $this->belongsToMany(User::class)->withPivot('role','contribution_hours','last_activity');
    }

    public function tasks (){
        
        return $this->hasMany(Task::class);
    }

    public function oldestTask()
    {
       return $this->hasOne(Task::class)->oldestOfMany();
    }

    public function latestTask()
    {
       return $this->hasOne(Task::class)->latestOfMany();
    }

    public function VITask()
    {
        return $this->hasOne(Task::class)->ofMany(['priority' => 'max'], function ($query) {
            $query->where('title', 'like', '%very important%');
        });
    }
}
