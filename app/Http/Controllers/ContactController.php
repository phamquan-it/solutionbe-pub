<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Contacts",
 *     description="API Endpoints for managing contacts"
 * )
 */
class ContactController extends Controller
{
    /**
     * Get all contacts.
     *
     * @OA\Get(
     *     path="/api/contacts",
     *     summary="Get all contacts",
     *     tags={"Contacts"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(response=200, description="Contacts retrieved successfully")
     * )
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('keyword')) {
            $query->where('fullname', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('description', 'LIKE', '%' . $request->keyword . '%');
        }
        $contacts = $query->paginate($request->input('per_page', 10));
        return response()->json($contacts);
    }

    /**
     * Create a new contact.
     *
     * @OA\Post(
     *     path="/api/contacts",
     *     summary="Create a new contact",
     *     tags={"Contacts"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fullname", "email"},
     *             @OA\Property(property="fullname", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="description", type="string", example="Hello")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Contact created successfully")
     * )
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();
        $contact = Contact::create($validated);
        return response()->json($contact, 201);
    }

    /**
     * Update a contact.
     *
     * @OA\Patch(
     *     path="/api/contacts/{id}",
     *     summary="Update a contact",
     *     tags={"Contacts"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fullname", "email"},
     *             @OA\Property(property="fullname", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="jane@example.com"),
     *             @OA\Property(property="description", type="string", example="Updated description")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Contact updated successfully")
     * )
     */
    public function update(UpdateContactRequest $request, $id)
    {
        // Retrieve validated data
        $validated = $request->validated();

        // Find the contact by ID
        $contact = Contact::findOrFail($id);

        // Update the contact
        $contact->update($validated);

        return response()->json($contact);
    }


    /**
     * Delete a contact.
     *
     * @OA\Delete(
     *     path="/api/contacts/{id}",
     *     summary="Delete a contact",
     *     tags={"Contacts"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Contact deleted successfully")
     * )
     */
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return response()->json(['message' => 'Contact deleted successfully']);
    }
}
