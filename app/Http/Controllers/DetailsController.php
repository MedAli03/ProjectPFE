<?php

namespace App\Http\Controllers;

use App\Models\Details;
use Illuminate\Http\Request;

class DetailsController extends Controller
{
    public function index()
    {
        $details = Details::all();
        return response()->json($details);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'service_id' => 'required|exists:services,id',
            'article_id' => 'required|exists:articles,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = Details::create($validatedData);

        return response()->json($detail, 201);
    }

    public function show(Details $detail)
    {
        return response()->json($detail);
    }

    public function update(Request $request, Details $detail)
    {
        $validatedData = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'service_id' => 'required|exists:services,id',
            'article_id' => 'required|exists:articles,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $detail->update($validatedData);

        return response()->json($detail, 200);
    }

    public function destroy(Details $detail)
    {
        $detail->delete();

        return response()->json(null, 204);
    }
}
