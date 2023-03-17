<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  \App\Models\Facture  $facture
     * @return \Illuminate\Http\Response
     */
    public function show(Facture $facture)
    {
        return response()->json($facture, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Facture  $facture
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Facture  $facture
     * @return \Illuminate\Http\Response
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