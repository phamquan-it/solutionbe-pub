<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use Illuminate\Http\Request;

/**
 * 
 * @OA\Tag(
 *     name="Refunds",
 *     description="API Endpoints for Refunds"
 * )
 */
class RefundController extends Controller
{
    /**
     * Display a paginated list of refuns.
     *
     * @OA\Get(
     *     path="/api/refunds",
     *     summary="Get list of refunds",
     *     tags={"Refunds"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of refunds per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of refunds")
     * )
     */
    public function index(Request $request)
    {
        $query = Refund::query();

        // Filtering by keyword (e.g., searching by refund reason or user email)
        if ($request->has('keyword')) {
            $search = $request->input('keyword');
            $query->where('reason', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        }

        // Filtering by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Pagination
        $refunds = $query->paginate($request->input('per_page', 10));

        return response()->json($refunds);
    }


    /**
     * Store a newly created refund in storage.
     *
     * @OA\Post(
     *     path="/api/refunds",
     *     summary="Create a new refund",
     *     tags={"Refunds"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"refund_amount", "status", "transaction_id"},
     *             @OA\Property(property="refund_amount", type="number", format="decimal", example=150.75),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="transaction_id", type="integer", example=1023)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Refund created successfully")
     * )
     */
    public function store(Request $request)
    {
        $refund = Refund::create($request->all());
        return response()->json($refund, 201);
    }

    /**
     * Update an existing refund.
     *
     * @OA\Patch(
     *     path="/api/refunds/{id}",
     *     summary="Update an existing refund",
     *     tags={"Refunds"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Refund ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"refund_amount", "status"},
     *             @OA\Property(property="refund_amount", type="number", format="decimal", example=200.00),
     *             @OA\Property(property="status", type="string", example="Approved")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Refund updated successfully"),
     *     @OA\Response(response=404, description="Refund not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);
        $refund->update($request->all());
        return response()->json($refund);
    }
}
