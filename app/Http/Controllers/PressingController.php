<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressing;

class PressingController extends Controller
{
    public function index()
    {
        $pressings = Pressing::all();
        return response()->json($pressings);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:pressings,email',
           
        ]);

        $pressing = Pressing::create($validatedData);
        return response()->json($pressing, 201);
    }

    public function show($id)
    {
        $pressing = Pressing::find($id);
        if (!$pressing) {
            return response()->json(['error' => 'Pressing not found'], 404);
        }
        return response()->json($pressing);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:pressings,email,'.$id,
            
        ]);

        $pressing = Pressing::find($id);
        if (!$pressing) {
            return response()->json(['error' => 'Pressing not found'], 404);
        }
        $pressing->update($validatedData);
        return response()->json($pressing);
    }

    public function destroy($id)
    {
        $pressing = Pressing::find($id);
        if (!$pressing) {
            return response()->json(['error' => 'Pressing not found'], 404);
        }
        $pressing->delete();
        return response()->json(['message' => 'Pressing deleted successfully']);
    }
}
