<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechnologyRequest;
use App\Http\Requests\UpdateTechnologyRequest;
use App\Models\Technology;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Technology",
 *     description="Technology management"
 * )
 */
class TechnologyController extends Controller
{
    protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * Display a listing of technologies with filtering and pagination.
     *
     * @OA\Get(
     *     path="/api/technologies",
     *     summary="Get list of technologies",
     *     security={{"bearerAuth":{}}},
     *     tags={"Technology"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search technologies by name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of results per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of technologies")
     * )
     */
    public function index(Request $request)
    {
        $query = Technology::query();

        // Filtering
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Pagination
        $technologies = $query
            ->with('languages')
            ->with('itcategories')
            ->paginate($request->input('per_page', 10));

        // foreach ($technologies->items() as $technology) {
        //     if ($technology->icon && str_starts_with($technology->icon, 'uploads/')) {
        //         try {
        //             $technology->icon = $this->firebaseService->getFileUrl($technology->icon);
        //         } catch (\Exception $e) {
        //             $technology->icon = null; // Handle missing file gracefully
        //         }
        //     }
        // }
        return response()->json($technologies);
    }

    /**
     * Display a listing of technologies with filtering and pagination.
     *
     * @OA\Get(
     *     path="/api/technologies/all",
     *     summary="Get list of technologies",
     *     tags={"Technology"},
     *     @OA\Response(response=200, description="List of technologies")
     * )
     */
    public function all(Request $request)
    {
        $technologies = Technology::all();

        // foreach ($technologies as $technology) {
        //     if ($technology->icon && str_starts_with($technology->icon, 'uploads/')) {
        //         try {
        //             $technology->icon = $this->firebaseService->getFileUrl($technology->icon);
        //         } catch (\Exception $e) {
        //             $technology->icon = null; // Handle missing file gracefully
        //         }
        //     }
        // }

        return response()->json($technologies);
    }


    /**
     * Store a newly created technology.
     *
     * @OA\Post(
     *     path="/api/technologies",
     *     summary="Create a new technology",
     *     security={{"bearerAuth":{}}},
     *     tags={"Technology"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "icon"},
     *             @OA\Property(property="name", type="string", example="Laravel"),
     *             @OA\Property(property="icon", type="string", example="laravel.svg")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Technology created successfully")
     * )
     */
    public function store(StoreTechnologyRequest $request)
    {
        $technology = Technology::create($request->validated());
        return response()->json(['message' => 'Technology created successfully', 'data' => $technology], 201);
    }

    /**
     * Update the specified technology.
     *
     * @OA\Patch(
     *     path="/api/technologies/{id}",
     *     summary="Update a technology",
     *     security={{"bearerAuth":{}}},
     *     tags={"Technology"},
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
     *             @OA\Property(property="name", type="string", example="Updated Tech"),
     *             @OA\Property(property="icon", type="string", example="updated-icon.svg")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Technology updated successfully")
     * )
     */
    public function update(UpdateTechnologyRequest $request, Technology $technology)
    {
        $technology->update($request->validated());
        return response()->json(['message' => 'Technology updated successfully', 'data' => $technology]);
    }


    /**
     * @OA\Patch(
     *      path="/api/technologies/update-language/{id}",
     *      summary="Sync languages for a technology",
     *      tags={"Technology"},
     *      security={{ "bearerAuth":{} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="technology ID",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"language_ids"},
     *              @OA\Property(
     *                  property="language_ids",
     *                  type="array",
     *                  @OA\Items(type="integer"),
     *                  example={1,2,3}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Technologies updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Technologies updated successfully"),
     *              @OA\Property(property="service_id", type="integer", example=1),
     *              @OA\Property(property="technologies", type="array", @OA\Items(type="integer"), example={1,2,3})
     *          )
     *      )
     * )
     */
    public function updateLanguageTechnology(Request $request, $id)
    {
        $technology = Technology::findOrFail($id);
        $technology->languages()->sync($request->input('language_ids'));

        return response()->json([
            'message' => 'Technologies updated successfully',
            'technology_id' => $technology->id,
            'languages' => $technology->languages()->pluck('id')
        ]);
    }


    /**
     * @OA\Patch(
     *      path="/api/technologies/update-ittechnology/{id}",
     *      summary="Sync it-technology for a technology",
     *      tags={"Technology"},
     *      security={{ "bearerAuth":{} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="technology ID",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"ittechnology_ids"},
     *              @OA\Property(
     *                  property="ittechnology_ids",
     *                  type="array",
     *                  @OA\Items(type="integer"),
     *                  example={1,2,3}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Technologies updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Technologies updated successfully"),
     *              @OA\Property(property="service_id", type="integer", example=1),
     *              @OA\Property(property="technologies", type="array", @OA\Items(type="integer"), example={1,2,3})
     *          )
     *      )
     * )
     */
    public function updateTechnologyITTechnology(Request $request, $id)
    {
        $technology = Technology::findOrFail($id);
        $technology->itcategories()->sync($request->input('ittechnology_ids'));

        return response()->json([
            'message' => 'Technologies updated successfully',
            'technology_id' => $technology->id,
            'itcategories' => $technology->itcategories()->pluck('id')
        ]);
    }


    /**
     * Remove the specified technology.
     *
     * @OA\Delete(
     *     path="/api/technologies/{id}",
     *     summary="Delete a technology",
     *     security={{"bearerAuth":{}}},
     *     tags={"Technology"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Technology deleted successfully")
     * )
     */
    public function destroy(Technology $technology)
    {
        $technology->delete();
        return response()->json(['message' => 'Technology deleted successfully']);
    }
}
