<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/client/facture/",
     *     summary="Get all factures for the authenticated user",
     *     tags={"Client"},
     *     description="Returns a list of all factures for the authenticated user, either a client or a pressing",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of factures"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid user role",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid user role")
     *         )
     *     )
     * )
     */

    /**
     * @OA\Get(
     *     path="api/pressings/facture",
     *     summary="Get a list of Factures",
     *     description="Returns a list of Facture objects based on the authenticated user's role",
     *     operationId="getFactures",
     *     tags={"Pressing"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Invalid user role"
     *     )
     * )
     */

    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is a client or a pressing
        if ($user->role === 'client') {
            // Get all factures for the client
            $factures = Facture::where('client_id', $user->id)->get();
        } else if ($user->role === 'pressing') {
            // Get all factures for the pressing
            $factures = Facture::where('pressing_id', $user->id)->get();
        } else {
            // Invalid user role
            return response()->json(['error' => 'Invalid user role'], 403);
        }

        return response()->json($factures, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'client_id' => 'required|exists:users,id,role,client',
            'pressing_id' => 'required|exists:users,id,role,pressing',
            'numero' => 'required',
            'date' => 'required|date',
            'total' => 'required|numeric',
        ]);

        // Create a new facture
        $facture = Facture::create($validatedData);

        return response()->json($facture, 201);
    }

    /**
     * Retrieve the specified Facture.
     *
     * @OA\Get(
     *     path="/api/client/facture/{id}",
     *     tags={"Client"},
     *     summary="Retrieve a Facture",
     *     description="Retrieves the specified Facture by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Facture to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Facture not found"
     *     )
     * )
     */

    /**
     * @OA\Get(
     *     path="api/pressings/facture/{id}",
     *     summary="Get a Facture by ID",
     *     description="Returns a Facture object based on the given ID",
     *     operationId="getFactureById",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Facture to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facture not found"
     *     )
     * )
     */

    public function show(Facture $facture)
    {
        return response()->json($facture, 200);
    }

    /**
     * @OA\Put(
     *     path="api/pressings/facture/{id}",
     *     summary="Update a Facture by ID",
     *     description="Updates a Facture object based on the given ID",
     *     operationId="updateFactureById",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Facture to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON object containing data to update Facture"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facture not found"
     *     )
     * )
     */

    public function update(Request $request, Facture $facture)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'commande_id' => 'sometimes|required|exists:commandes,id',
            'client_id' => 'sometimes|required|exists:users,id,role,client',
            'pressing_id' => 'sometimes|required|exists:users,id,role,pressing',
            'numero' => 'sometimes|required',
            'date' => 'sometimes|required|date',
            'total' => 'sometimes|required|numeric',
        ]);

        // Update the facture
        $facture->update($validatedData);

        return response()->json($facture, 200);
    }

    /**
     * @OA\Delete(
     *     path="api/pressings/facture/{id}",
     *     tags={"Pressing"},
     *     summary="Delete a facture by ID",
     *     description="Delete a facture by ID. Requires admin role, or client_id or pressing_id must match authenticated user's ID.",
     *     operationId="deleteFacture",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the facture to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Facture deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Facture deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You are not authorized to delete this facture",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="You are not authorized to delete this facture")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Facture not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Facture not found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        // Find the facture by ID
        $facture = Facture::find($id);
    
        if (!$facture) {
            return response()->json(['error' => 'Facture not found'], 404);
        }
    
        // Check if the authenticated user is authorized to delete the facture
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $facture->client_id && $user->id !== $facture->pressing_id) {
            return response()->json(['error' => 'You are not authorized to delete this facture'], 403);
        }
    
        // Delete the facture
        $facture->delete();
    
        return response()->json(['message' => 'Facture deleted successfully'], 200);
    }
};    