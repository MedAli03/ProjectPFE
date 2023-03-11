<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Tarif;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prices = Price::all();
        return response()->json($prices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'tarif_id' => 'required|exists:tarifs,id',
            'total_price' => 'required',
        ]);

        $commande = Commande::findOrFail($validatedData['commande_id']);
        $tarif = Tarif::findOrFail($validatedData['tarif_id']);

        $price = new Price;
        $price->commande()->associate($commande);
        $price->tarif()->associate($tarif);
        $price->price = $validatedData['total_price'];
        $price->save();

        return response()->json([
            'message' => 'Price created successfully',
            'price' => $price,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $price = Price::findOrFail($id);
        return response()->json($price);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'tarif_id' => 'required|exists:tarifs,id',
            'total_price' => 'required',
        ]);

        $commande = Commande::findOrFail($validatedData['commande_id']);
        $tarif = Tarif::findOrFail($validatedData['tarif_id']);

        $price = Price::findOrFail($id);
        $price->commande()->associate($commande);
        $price->tarif()->associate($tarif);
        $price->price = $validatedData['total_price'];
        $price->save();

        return response()->json([
            'message' => 'Price updated successfully',
            'price' => $price,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $price = Price::findOrFail($id);
        $price->delete();
        return response()->json([
            'message' => 'Price deleted successfully',
            'price' => $price,
        ]);
    }
}
