<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRateRequest;
use App\Models\Rate;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Rates",
 *     description="Endpoints for rating posts"
 * )
 */
class RateController extends Controller
{
    /**
     * Rate a post (STAR or LIKE).
     *
     * This endpoint allows users to rate a post with either "STAR" or "LIKE".
     * - If the same rating exists, it will be removed (unrate).
     * - If a different rating exists, it will be updated.
     * - If no rating exists, a new one is created.
     *
     * @OA\Post(
     *     path="/api/rate",
     *     summary="Rate a post",
     *     description="Allows a user to rate a post with either 'STAR' or 'LIKE'. If the same rating exists, it will be removed. If a different rating exists, it will be updated.",
     *     operationId="ratePost",
     *     tags={"Rates"},
     *     security={{ "bearerAuth":{} }},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"post_id", "like"},
     *             @OA\Property(property="post_id", type="integer", example=5, description="The ID of the post"),
     *             @OA\Property(property="like", type="string", enum={"STAR", "LIKE"}, example="STAR", description="The type of rating")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=201,
     *         description="Rating added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Star added successfully"),
     *             @OA\Property(property="rate", type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="post_id", type="integer", example=5),
     *                 @OA\Property(property="like", type="string", example="STAR")
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Rating updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rating updated successfully"),
     *             @OA\Property(property="rate", type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="post_id", type="integer", example=5),
     *                 @OA\Property(property="like", type="string", example="LIKE")
     *             )
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     * 
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function rate(StoreRateRequest $request)
    {
        $userId = $request->user()->id;
        $postId = $request->post_id;
        $newRating = $request->like;

        // Check if the user has already rated this post
        $existingRate = Rate::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();

        if ($existingRate) {
            if ($existingRate->like === $newRating) {
                $existingRate->delete();
                return response()->json([
                    'message' => ucfirst(strtolower($newRating)) . ' removed successfully'
                ]);
            }

            // If a different rating exists, update it
            $existingRate->update(['like' => $newRating]);

            return response()->json([
                'message' => 'Rating updated successfully',
                'rate' => $existingRate
            ]);
        }

        // Create a new rating
        $rate = Rate::create([
            'user_id' => $userId,
            'post_id' => $postId,
            'like' => $newRating
        ]);

        return response()->json([
            'message' => ucfirst(strtolower($newRating)) . ' added successfully',
            'rate' => $rate
        ], 201);
    }
}
