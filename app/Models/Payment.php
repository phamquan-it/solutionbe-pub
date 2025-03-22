<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'user_id',
        'transaction_id',
        'rate',
        'method'
    ];

    /**
     * Count payments grouped by their status
     *
     * @return array
     */
    public static function countByStatus()
    {
        return self::groupBy('status')
            ->selectRaw('status, COUNT(*) as count')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Relationship with User model (each payment belongs to one user)
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get payment statistics by status
     *
     * @return JsonResponse
     */
    public function getPaymentStats(): JsonResponse
    {
        $stats = self::countByStatus(); // Call the countByStatus method to get the statistics
        return response()->json($stats);
    }
}
