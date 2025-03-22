<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;


    protected $table = 'posts';

    protected $fillable = [
        'title',
        'title_vi',
        'description',
        'description_vi',
        'image'
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'post_id');
    }
}
