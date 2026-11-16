<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'workspace_id',
        'key',
        'name',
        'description',
        'status',
        'color',
        'start_date',
        'due_date',
        'lead_id',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'start_date' => 'date:Y-m-d',
        'due_date' => 'date:Y-m-d',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'project_member', 'project_id', 'team_member_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
