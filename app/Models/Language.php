<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Language extends Model
{
    /** @use HasFactory<\Database\Factories\LanguageFactory> */
    use HasFactory;
    protected $table = "languages";
    protected $fillable = [
        'name',
        'icon'
    ];
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'language_technology', 'language_id');
    }
}
