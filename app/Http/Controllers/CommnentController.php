<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommnentRequest;
use App\Http\Requests\UpdateCommnentRequest;
use App\Models\Commnent;
use Illuminate\Http\Request;

class CommnentController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/comments",
     *     summary="Get a list of comments",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         description="Filter comments by post_id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful response")
     * )
     */
    public function index(Request $request)
    {
        $query = Commnent::with(['post', 'user']);

        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        $totalComments = $query->count();
        $comments = $query->paginate(10);

        return response()->json([
            'total_comments' => $totalComments,
            'data' => $comments
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/comments",
     *     summary="Create a new comment",
     *     security={{"bearerAuth":{}}},
     *     tags={"Comments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content", "post_id"},
     *             @OA\Property(property="content", type="string", example="This is a comment."),
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Comment created successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(StoreCommnentRequest $request)
    {
        $user = $request->user();
        $comment = Commnent::create([
            'content' => $request->input('content'),
            'post_id' => $request->input('post_id'),
            'user_id' => $user->id,
        ]);

        return response()->json($comment, 201);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommnentRequest $request, Commnent $commnent)
    {
        $commnent->update($request->validated());
        return response()->json($commnent);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commnent $commnent)
    {
        $commnent->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
