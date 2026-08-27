<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDeleteRequest extends Model
{
    protected $fillable = [
        'project_id',
        'requested_by',
        'reason',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class)->withoutGlobalScopes();
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
