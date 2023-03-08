<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $commande = Commande::create([
            'client_id' => $request->client_id,
            'pressing_id' => $request->pressing_id,
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

    

    
}
