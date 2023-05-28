<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Facture;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function index()
    {
        $factures = Facture::all();
        return response()->json($factures);
    }

    public function getPressingFactures(Request $request)
    {
        $pressingId = $request->user()->id;
        $factures = Facture::with('client','commande','pressing')->where('pressing_id', $pressingId)->get();
        return response()->json($factures);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'client_id' => 'required|exists:users,id',
            'pressing_id' => 'required|exists:users,id',
            'numero' => 'required',
        ]);

        $facture = Facture::create($validatedData);
        return response()->json($facture, 201);
    }

    public function show(Facture $facture)
    {
        return response()->json($facture);
    }

    public function update(Request $request, Facture $facture)
    {
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'client_id' => 'required|exists:users,id',
            'pressing_id' => 'required|exists:users,id',
            'numero' => 'required',
        ]);

        $facture->update($validatedData);
        return response()->json($facture, 200);
    }

    public function markAsPaid(Request $request, $id)
    {
        $facture = Facture::findOrFail($id);
        $facture->status = 'payé'; // Update the status value to 'payé'
        $facture->save();
    
        return response()->json([
            'message' => 'Facture payée', // Update the response message
            'data' => $facture
        ]);
    }

    public function printInvoice(Facture $facture)
    {
        // Logic to generate and print the invoice

        // For example, you can use a PDF generation library like Dompdf or TCPDF
        // to generate the invoice PDF and send it to the printer.

        // Once the invoice is printed, you can return a response indicating success
        return response()->json(['message' => 'Invoice printed successfully']);
    }

    public function facturer(Request $request,$id)
    {
        $commande = Commande::find($id);

    
        $pressing = $commande->pressing;
        $client = $commande->client;
    
        // Create a new invoice
        $facture = Facture::create([
            'commande_id' => $commande->id,
            'client_id' => $client->id,
            'pressing_id' => $pressing->id,
            'numero' => 'INV' . Carbon::now()->format('YmdHis'), // Generate a unique invoice number
            'status' => 'non payé', // Set the default status to 'non payé'
        ]);
    
        // Update the command with the invoice ID
        $commande->save();
    
        return response()->json([
            'message' => 'Invoice created successfully',
            'facture' => $facture
        ]);
    }
    


    public function destroy(Facture $facture)
    {
        $facture->delete();
        return response()->json(null, 204);
    }
}
