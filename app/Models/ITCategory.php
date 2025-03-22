<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ITCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ITCategoryFactory> */
    use HasFactory;
    protected $fillable = ['name'];
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'it_category_technology', 'it_category_id');
    }
}
