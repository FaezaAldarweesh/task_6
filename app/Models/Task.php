<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'project_id',
        'notes',
    ];

    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i'); 
    }
    
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::parse($value)->format('Y-m-d H:i:s'); 
    }

    public function scopeFilter(Builder $query, $priority, $status)
    {
        if (!empty($priority)) {
            $query->where('priority', '=', $priority);
        }
        if (!empty($status)) {
            $query->where('status', '=', $status);
        }
        return $query;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
}
