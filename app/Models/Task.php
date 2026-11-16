<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'workspace_id',
        'project_id',
        'code',
        'title',
        'description',
        'status',
        'priority',
        'assignee_id',
        'start_date',
        'due_date',
        'tags',
        'estimated_hours',
    ];

    protected $casts = [
        'tags' => 'array',
        'start_date' => 'date:Y-m-d',
        'due_date' => 'date:Y-m-d',
        'estimated_hours' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('sort_order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->orderBy('created_at');
    }
}
