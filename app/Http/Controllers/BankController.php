<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Bank")
 */
class BankController extends Controller
{
    /**
     * Display a paginated list of banks.
     *
     * @OA\Get(
     *     path="/api/banks",
     *     summary="Get list of banks",
     *     tags={"Bank"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of banks")
     * )
     */
    public function index(Request $request)
    {
        $banks = Bank::all();
        return response()->json($banks);
    }
}
