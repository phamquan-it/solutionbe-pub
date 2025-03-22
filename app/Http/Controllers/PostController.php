<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Posts", description="API for managing posts")
 */
class PostController extends Controller
{
    /**
     * Get the list of posts.
     * 
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Retrieve the list of posts",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of posts"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::query()
            ->leftJoin('rates', 'posts.id', '=', 'rates.post_id')
            ->select('posts.id', 'posts.title', 'posts.title_vi', 'posts.description', 'posts.description_vi')
            ->selectRaw('COUNT(CASE WHEN rates.like = "STAR" THEN 1 END) as star_count')
            ->selectRaw('COUNT(CASE WHEN rates.like = "LIKE" THEN 1 END) as like_count')
            ->groupBy('posts.id', 'posts.title', 'posts.title_vi', 'posts.description', 'posts.description_vi');

        // Paginate the results (10 items per page)
        $posts = $query->paginate(10);

        return response()->json($posts);
    }

    public function all(Request $request): JsonResponse
    {
        $query = Post::with('rates') // Eager load the rates relation
            ->leftJoin('rates', 'posts.id', '=', 'rates.post_id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.title_vi',
                'posts.description',
                'posts.description_vi',
                'posts.image'
            )
            ->selectRaw('COUNT(CASE WHEN rates.like = "STAR" THEN 1 END) as star_count')
            ->selectRaw('COUNT(CASE WHEN rates.like = "LIKE" THEN 1 END) as like_count')
            ->groupBy('posts.id', 'posts.title', 'posts.title_vi', 'posts.description', 'posts.description_vi', 'posts.image');

        // Paginate the results (10 items per page)
        $posts = $query->get();

        return response()->json($posts);
    }

    /**
     * Store a new post.
     * 
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "title_vi", "description", "description_vi"},
     *             @OA\Property(property="title", type="string", example="My Post"),
     *             @OA\Property(property="title_vi", type="string", example="Bài viết của tôi"),
     *             @OA\Property(property="description", type="string", example="This is a post"),
     *             @OA\Property(property="description_vi", type="string", example="Đây là bài viết")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Post created successfully"),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());
        return response()->json($post, 201);
    }

    /**
     * Update a post.
     * 
     * @OA\Patch(
     *     path="/api/posts/{id}",
     *     summary="Update a post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "title_vi", "description", "description_vi"},
     *             @OA\Property(property="title", type="string", example="Updated Post"),
     *             @OA\Property(property="title_vi", type="string", example="Bài viết cập nhật"),
     *             @OA\Property(property="description", type="string", example="Updated content"),
     *             @OA\Property(property="description_vi", type="string", example="Nội dung cập nhật")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Post updated successfully"),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());
        return response()->json($post);
    }

    /**
     * Delete a post.
     * 
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Post deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    //change image link

    /**
     * Update the image link of a post.
     * 
     * @OA\Put(
     *     path="/api/posts/{id}/image",
     *     summary="Update post image link",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID to update image",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"image"},
     *             @OA\Property(property="image", type="string", example="https://example.com/new-image.jpg")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Image updated successfully"),
     *     @OA\Response(response=400, description="Invalid image URL"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function updateImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|string', // Ensure a valid URL is provided
        ]);

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Update the image link
        $post->update(['image' => $request->image]);

        return response()->json(['message' => 'Image updated successfully', 'post' => $post], 200);
    }
}
