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

    public function getTarifsForPressing(Request $request){
        $pressing = $request->user();
        $tarifs = Tarif::with('service','article')->where('id_pressing', $pressing->id)->orderBy('created_at', 'desc')->get();
        return response()->json($tarifs);
    
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'id_service' => 'exists:services,id',
            'id_article' => 'exists:articles,id',
        ]);
    
        $pressing = $request->user();
        $tarif = new Tarif($validatedData);
        $tarif->id_pressing = $pressing->id;
        $tarif->save();
    
        $tarif->load(['service', 'article']);
        $tarifData = [
            'id' => $tarif->id,
            'service' => $tarif->service->name,
            'article' => $tarif->article->name,
            'price' => $tarif->price,
        ];
    
        return response()->json($tarifData, 201);
    }
    
    public function show($id){
        $tarif = Tarif::findOrFail($id);
        return response()->json($tarif);
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
