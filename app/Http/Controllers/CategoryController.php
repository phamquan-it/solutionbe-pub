<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Categories")
 * 
 */
class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/categories",
     *      tags={"Categories"},
     *      summary="Get list of categories",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="Success"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Apply keyword filter if 'keyword' exists in the request
        if ($request->has('keyword')) {
            $query->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        return response()->json($query->get(), 200);
    }


    /**
     * @OA\Post(
     *      path="/api/categories",
     *      tags={"Categories"},
     *      summary="Create a new category",
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Technology")
     *          )
     *      ),
     *      @OA\Response(response=201, description="Category created"),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create($request->all());

        return response()->json($category, 201);
    }

    /**
     * @OA\Patch(
     *      path="/api/categories/{id}",
     *      tags={"Categories"},
     *      summary="Update a category",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Updated Category")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Updated successfully"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($request->all());

        return response()->json($category);
    }

    /**
     * @OA\Delete(
     *      path="/api/categories/{id}",
     *      tags={"Categories"},
     *      summary="Delete a category",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(response=204, description="Deleted successfully"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
