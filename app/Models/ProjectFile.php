<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFileFactory> */
    use HasFactory;
    protected $fillable = ['project_id', 'file'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
