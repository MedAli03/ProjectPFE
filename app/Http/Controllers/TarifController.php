<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarif;

class TarifController extends Controller
{
    public function index(){
        $tarifs = Tarif::all();
        return response()->json($tarifs);
    }

    public function show($id){
        $tarif = Tarif::findOrFail($id);
        return response()->json($tarif);
    }

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

    public function destroy($id){
        $tarif = Tarif::findOrFail($id);
        $tarif->delete();

        return response()->json(null, 204);
    }
}
