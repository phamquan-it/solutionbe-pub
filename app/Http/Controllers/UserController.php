<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Technology management"
 * )
 */
class UserController extends Controller
{
	/**
	 * List users with pagination and filtering.
	 *
	 * @OA\Get(
	 *     path="/api/users",
	 *     summary="Get a list of users",
	 *     tags={"Users"},
	 *     security={{ "bearerAuth":{} }},
	 *     @OA\Parameter(
	 *         name="keyword",
	 *         in="query",
	 *         description="Filter by keyword",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Parameter(
	 *         name="page",
	 *         in="query",
	 *         description="Page number",
	 *         @OA\Schema(type="integer", example=1)
	 *     ),
	 *     @OA\Parameter(
	 *         name="per_page",
	 *         in="query",
	 *         description="Items per page",
	 *         @OA\Schema(type="integer", example=10)
	 *     ),
	 *     @OA\Response(response=200, description="List of users")
	 * )
	 */
	public function index(Request $request)
	{
		$query = User::query();

		if ($request->has('keyword')) {
			$query->where('email', 'LIKE', '%' . $request->keyword . '%')
				->orWhere('name', 'LIKE', '%' . $request->keyword . '%');
		}

		$users = $query->paginate($request->get('per_page', 10));
		return response()->json($users);
	}

	/**
	 * List users with pagination and filtering.
	 *
	 * @OA\Get(
	 *     path="/api/users/all",
	 *     summary="Get a list of users",
	 *     tags={"Users"},
	 *     security={{ "bearerAuth":{} }},
	 *     @OA\Response(response=200, description="List of users")
	 * )
	 */
	public function all(Request $request)
	{
		$users = User::all();
		return response()->json($users);
	}


	/**
	 * @OA\Post(
	 *     path="/api/users/{user}/update-password",
	 *     summary="Update user password (Admin Only)",
	 *     tags={"Users"},
	 *     security={{"bearerAuth": {}}},
	 *     @OA\Parameter(
	 *         name="user",
	 *         in="path",
	 *         required=true,
	 *         description="User ID",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\RequestBody(
	 *         required=true,
	 *         @OA\JsonContent(
	 *             required={"new_password", "new_password_confirmation"},
	 *             @OA\Property(property="new_password", type="string", example="newpassword123"),
	 *             @OA\Property(property="new_password_confirmation", type="string", example="newpassword123")
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Password updated successfully",
	 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Password updated successfully"))
	 *     ),
	 *     @OA\Response(
	 *         response=400,
	 *         description="Validation error",
	 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="The new password must be at least 6 characters."))
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthorized",
	 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Unauthenticated."))
	 *     )
	 * )
	 */
	public function updateUserPassword(Request $request, User $user)
	{
		// Validate the request
		$request->validate([
			'new_password' => 'required|string',
		]);

		// Update the user's password
		$user->password = Hash::make($request->new_password);
		$user->save();

		return response()->json(['message' => 'Password updated successfully']);
	}
	/**
	 * Get the total sum of funds and remains for all users.
	 *
	 * @OA\Get(
	 *     path="/api/users/total-funds-remains",
	 *     summary="Retrieve total funds and remains",
	 *     tags={"Users"},
	 *     security={{"bearerAuth":{}}},
	 *     @OA\Response(
	 *         response=200,
	 *         description="Totals retrieved successfully",
	 *         @OA\JsonContent(
	 *             type="object",
	 *             @OA\Property(property="total_fund", type="number", format="float", example=12345.67),
	 *             @OA\Property(property="total_remains", type="number", format="float", example=8901.23)
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=401,
	 *         description="Unauthenticated"
	 *     )
	 * )
	 *
	 * @return JsonResponse
	 */
	public function getTotalFundsAndRemains(): JsonResponse
	{
		$totals = DB::table('users')
			->selectRaw('SUM(fund) as total_fund, SUM(remains) as total_remains')
			->first();

		return response()->json([
			'total_fund' => $totals->total_fund ?? 0,
			'total_remains' => $totals->total_remains ?? 0,
		]);
	}

	public function update(Request $request)
	{
		// Xác thực dữ liệu đầu vào
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'phone' => 'required|string|max:15',
		]);

		// Lấy người dùng đã xác thực
		$user = $request->user();

		// Cập nhật thông tin người dùng
		$user->update([
			'name' => $validated['name'],
			'phone' => $validated['phone'],
		]);

		return response()->json(['message' => 'User updated successfully', 'user' => $user]);
	}
}
