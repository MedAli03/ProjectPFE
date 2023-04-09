<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarif;

class TarifController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/client/tarif/",
     *     summary="Get all tarifs",
     *     tags={"Client"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tarifs"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */

    /**
     * @OA\Get(
     *     path="api/pressings/tarif/",
     *     summary="Get all tarifs",
     *     description="Retrieve a list of all tarifs",
     *     operationId="getAllTarifs",
     *     tags={"Pressing"},
     *     @OA\Response(
     *         response="200",
     *         description="List of all tarifs"
     *     )
     * )
     */

    public function index(){
        $tarifs = Tarif::all();
        return response()->json($tarifs);
    }

    /**
     * @OA\Get(
     *     path="/api/client/tarif/{id}",
     *     summary="Get a single tarif",
     *     description="Returns a single tarif by ID",
     *     operationId="getTarifById",
     *     tags={"Client"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the tarif to retrieve",
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
     *         description="Tarif not found"
     *     )
     * )
     */

    /**
     * @OA\Get(
     *     path="api/pressings/tarif/{id}",
     *     summary="Get a Tarif by ID",
     *     description="Returns a Tarif object corresponding to the provided ID",
     *     operationId="getTarifById",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Tarif to retrieve",
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
     *         description="Tarif not found"
     *     )
     * )
     */


    public function show($id){
        $tarif = Tarif::findOrFail($id);
        return response()->json($tarif);
    }

    /**
     * @OA\Post(
     *     path="api/pressings/tarif/",
     *     summary="Create a new tarif",
     *     description="Create a new tarif with the given details",
     *     operationId="createTarif",
     *     tags={"Pressing"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Tarif object to be created"
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="New tarif created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="id_service", type="integer"),
     *             @OA\Property(property="id_article", type="integer"),
     *             @OA\Property(property="id_pressing", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request){
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'id_service' => 'exists:services,id',
            'id_article' => 'exists:articles,id',
            'id_pressing' => 'exists:users,id,role,pressing'
        ]);

        $tarif = Tarif::create($validatedData);

        return response()->json($tarif, 201);
    }


    /**
     * @OA\Put(
     *     path="api/pressings/tarif/{id}",
     *     summary="Update a Tarif by ID",
     *     description="Updates a Tarif object corresponding to the provided ID",
     *     operationId="updateTarifById",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Tarif to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="The new information for the Tarif"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarif not found"
     *     )
     * )
     */


    public function update(Request $request, $id){
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'id_service' => 'exists:services,id',
            'id_article' => 'exists:articles,id',
            'id_pressing' => 'exists:users,id,role,pressing'
        ]);

        $tarif = Tarif::findOrFail($id);
        $tarif->update($validatedData);

        return response()->json($tarif, 200);
    }

    /**
     * @OA\Delete(
     *     path="api/pressings/tarif/{id}",
     *     summary="Delete a Tarif by ID",
     *     description="Deletes a Tarif object corresponding to the provided ID",
     *     operationId="deleteTarifById",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Tarif to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarif not found"
     *     )
     * )
     */

    public function destroy($id){
        $tarif = Tarif::findOrFail($id);
        $tarif->delete();

        return response()->json(null, 204);
    }
}
