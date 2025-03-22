<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Refund",
 *     type="object",
 *     required={"refund_amount", "status", "transaction_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="refund_amount", type="number", format="decimal", example=100.50),
 *     @OA\Property(property="status", type="string", example="Pending"),
 *     @OA\Property(property="transaction_id", type="integer", example=123),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Refund extends Model
{
    /** @use HasFactory<\Database\Factories\RefundFactory> */
    use HasFactory;
    protected $fillable = [
        'refund_amount',
        'transaction_id',
        'status',
        'reason'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
