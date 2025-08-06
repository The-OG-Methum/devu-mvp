<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'long_description',
        'tech_stack',
        'repository_url',
        'website_url',
        'stars',
        'open_issues_count',
        'contributors_count',
        'last_updated',
        'difficulty',
        'codebase_overview',
        'contribution_guide'
    ];

    protected $casts = [
        'tech_stack'=>'array',
        'codebase_overview'=>'array',
        'contribution_guide'=>'array',
        'last_updated'=>'datetime'
    ];

    public function issues(){
        return $this->hasMany(Issue::class);
    }
}
