<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Log",
 *     description="Operations related to logs"
 * )
 */
class LogController extends Controller
{
    /**
     * Display a listing of logs with filtering and pagination.
     *
     * @OA\Get(
     *     path="/api/logs",
     *     summary="Get list of logs",
     *     tags={"Log"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="method",
     *         in="query",
     *         description="Filter logs by method",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Filter logs by email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="action",
     *         in="query",
     *         description="Filter logs by action",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of results per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(response=200, description="List of logs")
     * )
     */
    public function index(Request $request)
    {
        $query = Log::query();

        // Filter by email keyword
        if ($request->has('keyword')) {
            $query->where('email', 'LIKE', '%' . $request->keyword . '%');
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $logs = $query->paginate($perPage);

        return response()->json($logs);
    }
}
