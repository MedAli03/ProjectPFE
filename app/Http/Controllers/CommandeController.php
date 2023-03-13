<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::all();
        return response()->json($commandes);
    }

    public function getCommandsByClient(Request $request)
{
    $client = $request->user();

    $commandes = Commande::where('client_id', $client->id)->get();

    return response()->json($commandes);
}

public function getCommandsByPressing(Request $request)
{
    $pressing = $request->user();

    $commandes = Commande::where('pressing_id', $pressing->id)->get();

    return response()->json($commandes);
}

    public function store(Request $request)
    {
        $commande = Commande::create([
            'client_id' => $request->client_id,
            'pressing_id' => $request->pressing_id,
            'article_id' => $request->client_id,
            'service_id' => $request->pressing_id,
            'quantity' => $request->quantity,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
            // add more attributes here as needed
        ]);

        return response()->json($commande, 201);
    }

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
        'message' => 'Commande added to Facture successfully',
        'facture' => $facture
    ]);
}
    

    
}
