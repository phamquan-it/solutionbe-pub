<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Service",
 *     title="Service",
 *     description="Service entity",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Web Hosting"),
 *     @OA\Property(property="amount", type="integer", example=100),
 *     @OA\Property(property="price", type="number", format="decimal", example=99.99),
 *     @OA\Property(property="description", type="string", nullable=true, example="Premium hosting service"),
 *     @OA\Property(property="rate", type="number", format="decimal", nullable=true, example=4.5),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ServiceController extends Controller
{
    protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * @OA\Get(
     *      path="/api/services",
     *      operationId="getServices",
     *      tags={"Services"},
     *     security={{ "bearerAuth":{} }},
     *      summary="Get a list of services with optional filters",
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Filter by service name",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="technology_id",
     *          in="query",
     *          description="Filter by service technology_id",
     *          required=false,
     *          @OA\Schema(type="number")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List of services",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Service"))
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = Service::query()->with('technologies');

        if ($request->has('technology_id')) {
            $query->whereHas('technologies', function ($q) use ($request) {
                $q->where('id', $request->technology_id);
            });
        }
        if ($request->has('language_id')) {
            $query->whereHas('technologies.languages', function ($q) use ($request) {
                $q->where('id', $request->language_id);
            });
        }

        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        // Filter by created_at only when both start_date and end_date exist
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $query->orderBy('created_at', 'desc');

        $services = $query->paginate($request->input('per_page', 10));
        foreach ($services->items() as $service) {
            if ($service->image && str_starts_with($service->image, 'uploads/')) {
                try {
                    $service->image = $this->firebaseService->getFileUrl($service->image);
                } catch (\Exception $e) {
                    $service->image = null; // Handle missing file gracefully
                }
            }
        }
        return response()->json($services);
    }

    /**
     * @OA\Get(
     *      path="/api/services/all",
     *      operationId="getAllServices",
     *      tags={"Services"},
     *      summary="Get all services",
     *      @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          description="Filter by service keyword",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List of services",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Service"))
     *      )
     * )
     */
    public function all()
    {
        $services = Service::with(['technologies', 'technologies.itcategories'])->get();
        foreach ($services as $service) {
            if ($service->image && str_starts_with($service->image, 'uploads/')) {
                try {
                    $service->image = $this->firebaseService->getFileUrl($service->image);
                } catch (\Exception $e) {
                    $service->image = null; // Handle missing file gracefully
                }
            }
        }
        return response()->json($services);
    }

    /**
     * @OA\Post(
     *      path="/api/services",
     *      operationId="createService",
     *     security={{ "bearerAuth":{} }},
     *      tags={"Services"},
     *      summary="Create a new service",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Service")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Service created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Service")
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'rate' => 'nullable|numeric',
            'image' => 'nullable|string|max:255'
        ]);

        $service = Service::create($validated);
        return response()->json($service, 201);
    }

    /**
     * @OA\Put(
     *      path="/api/services/{id}",
     *      operationId="updateService",
     *      security={{ "bearerAuth":{} }},
     *      tags={"Services"},
     *      summary="Update an existing service",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the service to update",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Service")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Service updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Service")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Service not found"
     *      )
     * )
     */
    public function updateServiceInfo(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'rate' => 'nullable|numeric',
            'image' => 'nullable|string|max:255'
        ]);

        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Check if a new image is provided and delete the old one
        if ($request->has('image') && $request->image !== $service->image) {
            if ($this->firebaseService->fileExists($service->image)) {
                $this->firebaseService->deleteFile($service->image);
            }
        }
        // Update the service with new data
        $service->update($validated);

        return response()->json($service, 200);
    }

    /**
     * @OA\Patch(
     *      path="/api/services/{id}",
     *      summary="Sync technologies for a service",
     *      tags={"Services"},
     *      security={{ "bearerAuth":{} }},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Service ID",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"technologies"},
     *              @OA\Property(
     *                  property="technologies",
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
    public function update(UpdateServiceRequest $request, $id)
    {
        $service = Service::findOrFail($id);

        // Sync only technologies
        $service->technologies()->sync($request->input('technologies'));

        return response()->json([
            'message' => 'Technologies updated successfully',
            'service_id' => $service->id,
            'technologies' => $service->technologies()->pluck('id')
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/api/services/{id}",
     *      operationId="deleteService",
     *      tags={"Services"},
     *      security={{ "bearerAuth":{} }},
     *      summary="Delete a service",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Service deleted successfully"
     *      )
     * )
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        // Check if the service has an image
        if (!empty($service->image)) {
            // Check if the image exists in Firebase Storage
            if ($this->firebaseService->fileExists($service->image)) {
                $this->firebaseService->deleteFile($service->image);
            }
        }
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 204);
    }
}
