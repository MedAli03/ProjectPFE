<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Facture;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::all();
        return response()->json($commandes);
    }

    /**
     * Get all orders placed by the authenticated client.
     *
     * @OA\Get(
     *     path="/api/client/commande/all",
     *     tags={"Client"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Returns a list of orders placed by the authenticated client"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. Authentication failed or client not authenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden. Client does not have permission to perform this action",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No orders found for the authenticated client",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No orders found for the authenticated client")
     *         )
     *     )
     * )
     */
    public function getCommandsByClient(Request $request)
    {
        $client = $request->user();

        $commandes = Commande::where('client_id', $client->id)->get();

        return response()->json($commandes);
    }

    /**
     * @OA\Get(
     *     path="api/pressings/commande/",
     *     summary="Get commands by pressing",
     *     description="Retrieve a list of commands for the authenticated pressing",
     *     operationId="getCommandsByPressing",
     *     tags={"Pressing"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response="200",
     *         description="List of commands for the pressing"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function getCommandsByPressing(Request $request)
{
    $pressing = $request->user();

    $commandes = Commande::with('client')->where('pressing_id', $pressing->id)->orderBy('created_at', 'desc')->get();

    return response()->json($commandes);
}


    /**
        * @OA\Post(
        *     path="/api/client/commande/add",
        *     summary="Create a new order",
        *     description="Create a new order",
        *     tags={"Client"},
        *     security={{"bearer_token":{}}},
        *     @OA\RequestBody(
        *         required=true,
        *         description="Pass order details",
        *         @OA\JsonContent(
        *             required={"client_id", "pressing_id", "article_id", "service_id", "quantity", "status", "total_amount"},
        *             @OA\Property(property="client_id", type="integer", description="ID of the client who made the order"),
        *             @OA\Property(property="pressing_id", type="integer", description="ID of the pressing where the order is placed"),
        *             @OA\Property(property="article_id", type="integer", description="ID of the article that needs to be serviced"),
        *             @OA\Property(property="service_id", type="integer", description="ID of the service required"),
        *             @OA\Property(property="quantity", type="integer", description="Quantity of the article that needs to be serviced"),
        *             @OA\Property(property="status", type="string", description="Status of the order (en attente, en cours, terminer)"),
        *             @OA\Property(property="total_amount", type="number", description="Total amount of the order"),
        *         ),
        *     ),
        *     @OA\Response(
        *         response=201,
        *         description="New order created successfully",
        *     ),
        *     @OA\Response(
        *         response=400,
        *         description="Validation error(s)"
        *     ),
        *     @OA\Response(
        *         response=401,
        *         description="Unauthorized"
        *     )
        * )
    */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:users,id',
            'pressing_id' => 'required|exists:users,id',
            'article_id' => 'required|exists:articles,id',
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1',
            'status' => [
                'required',
                'in:en attente, en cours, terminer',
                Rule::default('en attente')
            ],
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $commande = Commande::create([
            'client_id' => $request->client_id,
            'pressing_id' => $request->pressing_id,
            'article_id' => $request->article_id,
            'service_id' => $request->service_id,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
            // add more attributes here as needed
        ]);

        return response()->json($commande, 201);
    }


    /**
     * @OA\Get(
     *     path="api/pressings/commande/{id}",
     *     summary="Get a single command",
     *     description="Retrieve details for a single command by ID",
     *     operationId="getCommand",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the command to retrieve",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Details of the requested command"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Commande not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json([
                'message' => 'Commande not found'
            ], 404);
        }

        return response()->json($commande);
    }


    public function update(Request $request, $id)
    {
        $commande = Commande::find($id);
    
        if (!$commande) {
            return response()->json([
                'message' => 'Commande not found'
            ], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:en attente, annuler, confirmer, en cours, terminer',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            // add other fields as needed
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $commande->update($request->all());
    
        return response()->json($commande);
    }
    

    public function destroy($id)
    {


        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json([
                'message' => 'Commande not found'
            ], 404);
        }

        $commande->delete();

        return response()->json([
            'message' => 'Commande deleted successfully'
        ]);
    }


    /**
     * @OA\Put(
     *     path="api/pressings/commande/status/{id}",
     *     summary="Modify the status of a command",
     *     description="Modify the status of a single command by ID",
     *     operationId="modifyCommandStatus",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the command to modify",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="New status for the command",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Details of the modified command",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Commande not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function modifyStatus(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        $commande->status = $request->input('status');
        $commande->save();
    
        return response()->json([
            'message' => 'Commande status updated successfully',
            'data' => $commande
        ]);
    }

    /**
     * @OA\Delete(
     *     path="api/client/commande/delete/{id}",
     *     summary="Delete a pending commande",
     *     description="Deletes a commande if it exists and its status is 'en attente'.",
     *     tags={"Client"},
     *     @OA\Parameter(
     *         name="id",
     *         description="Commande ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Commande deleted successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande not found or cannot be deleted because status is not 'en attente'",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Commande not found or cannot be deleted because status is not 'en attente'"
     *             )
     *         )
     *     )
     * )
     */
    public function deletePendingCommande($id)
    {
        $commande = Commande::where('id', $id)->where('status', 'en attente')->first();

        if (!$commande) {
            return response()->json([
                'message' => 'Commande not found or cannot be deleted because status is not "en attente"'
            ], 404);
        }

        $commande->delete();

        return response()->json([
            'message' => 'Commande deleted successfully'
        ]);
    }


    /**
     * @OA\Post(
     *     path="api/pressings/commande/invoice/{id}",
     *     summary="Add an invoice to a command",
     *     description="Create a new invoice for a single command by ID",
     *     operationId="addCommandInvoice",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the command to add the invoice to",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Invoice information",
     *         @OA\JsonContent(
     *             @OA\Property(property="numero", type="string"),
     *             @OA\Property(property="date", type="string", format="date"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Details of the new invoice",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Commande not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function addingInvoice(Request $request, $id)
    {
        $commande = Commande::find($id);

        if (!$commande) {
            return response()->json([
                'message' => 'Commande not found'
            ], 404);
        }

        $pressing = $commande->pressing;
        $client = $commande->client;

        $facture = Facture::create([
            'commande_id' => $commande->id,
            'client_id' => $client->id,
            'pressing_id' => $pressing->id,
            'numero' => $request->numero,
            'date' => $request->date,
            'total' => $commande->total_price,
        ]);

        return response()->json([
            'message' => 'invoice added successfully',
            'facture' => $facture
        ]);
    }
    

    public function markAsInProgress($id){
        $commande = Commande::findOrFail($id);

        $commande->update(['status' => 'en cours']);

        return response()->json($commande, 200);
    }

    public function finish($id){
        $commande = Commande::findOrFail($id);

        $commande->update(['status' => 'terminée']);

        return response()->json($commande, 200);
    }


    
}
