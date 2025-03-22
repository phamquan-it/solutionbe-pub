<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Languages",
 *     description="API Endpoints for managing languages"
 * )
 */
class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/languages",
     *     summary="Get list of languages",
     *     tags={"Languages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of languages")
     * )
     */
    public function index(Request $request)
    {
        $query = Language::query();

        // Filtering by keyword (search by name or code)
        if ($request->has('keyword')) {
            $search = $request->input('keyword');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Fetch results
        $languages = $query->get();

        return response()->json($languages);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/languages",
     *     summary="Create a new language",
     *     tags={"Languages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "icon"},
     *             @OA\Property(property="name", type="string", example="English"),
     *             @OA\Property(property="icon", type="string", example="🇬🇧")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Language created successfully")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:languages,name',
            'icon' => 'required|string',
        ]);

        $language = Language::create($request->all());
        return response()->json(['message' => 'Language created successfully', 'data' => $language], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @OA\Patch(
     *     path="/api/languages/{id}",
     *     summary="Update an existing language",
     *     tags={"Languages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "icon"},
     *             @OA\Property(property="name", type="string", example="French"),
     *             @OA\Property(property="icon", type="string", example="🇫🇷")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Language updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $language = Language::find($id);
        if (!$language) {
            return response()->json(['message' => 'Language not found'], 404);
        }
        $language->update($request->all());
        return response()->json(['message' => 'Language updated successfully', 'data' => $language]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/languages/{id}",
     *     summary="Delete a language",
     *     tags={"Languages"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Language deleted successfully")
     * )
     */
    public function destroy($id)
    {
        $language = Language::find($id);

        if (!$language) {
            return response()->json(['message' => 'Language not found'], 404);
        }

        $language->delete();

        return response()->json([
            'message' => 'Language deleted successfully',
            'data' => $language
        ]);
    }
}
