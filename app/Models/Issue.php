<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        
        'project_id',
        'title',
        'description',
        'labels',
        'difficulty',
        'status',
        'assignee',
        'code_explanation',
        'contribution_steps',
        'resources'
    ];


    protected $casts = [

        'labels' => 'array',
        'code_explanation' => 'array',
        'contribution_steps' => 'array',
        'resources' => 'array'
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
