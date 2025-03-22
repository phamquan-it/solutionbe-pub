<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
        'duration',
        'user_id',
        'category_id'
    ];


    public static function getProjectStatistics($userId = null)
    {
        $query = Project::query();

        // If userId is provided, filter by user_id
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Total revenue by month (Fix: Use subquery for ordering)
        $revenueByMonth = $query
            ->selectRaw('SUM(price) as revenue, YEAR(duration) as year, MONTH(duration) as month')
            ->groupByRaw('YEAR(duration), MONTH(duration)')
            ->orderByRaw('YEAR(duration) DESC, MONTH(duration) DESC')
            ->get()
            ->mapWithKeys(fn($item) => ["{$item->year}-{$item->month}" => $item->revenue])
            ->toArray();

        return [
            'revenueByMonth'   => $revenueByMonth,
            'totalProjects'    => $query->count(),
            'completedProjects' => (clone $query)->where('status', 'completed')->count(),
            'pendingProjects'   => (clone $query)->where('status', 'pending')->count(),
            'inProgressProjects' => (clone $query)->where('status', 'in_progress')->count(),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function files()
    {
        return $this->hasMany(ProjectFile::class, 'project_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'project_id');
    }
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'project_technology', 'project_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
