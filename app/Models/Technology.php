<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technology extends Model
{
    /** @use HasFactory<\Database\Factories\TechnologyFactory> */
    use HasFactory;
    protected $table = 'technologies';

    protected $fillable = [
        'name',
        'icon',
    ];
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_technology', 'technology_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_technology', 'technology_id');
    }

    public function itcategories(): BelongsToMany
    {
        return $this->belongsToMany(ITCategory::class, 'it_category_technology', 'technology_id', 'it_category_id');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_technology', 'technology_id');
    }
}
