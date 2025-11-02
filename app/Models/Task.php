<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'assigned_to',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'attachment'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function canAccess($user): bool
    {
        return $this->user_id === $user->id || $this->assigned_to === $user->id;
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'concluida' && $this->due_date->isPast();
    }
}
