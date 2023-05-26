<?php

namespace App\Http\Controllers;

use App\Models\Facture;
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
        $factures = Facture::where('pressing_id', $pressingId)->get();
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

    public function markAsPaid(Request $request)
    {
        $facture = Facture::findOrFail($request->facture_id);
        $facture->status = 'paid';
        $facture->save();

        return response()->json($facture, 200);
    }

    public function printInvoice(Facture $facture)
    {
        // Logic to generate and print the invoice

        // For example, you can use a PDF generation library like Dompdf or TCPDF
        // to generate the invoice PDF and send it to the printer.

        // Once the invoice is printed, you can return a response indicating success
        return response()->json(['message' => 'Invoice printed successfully']);
    }

    public function destroy(Facture $facture)
    {
        $facture->delete();
        return response()->json(null, 204);
    }
}
