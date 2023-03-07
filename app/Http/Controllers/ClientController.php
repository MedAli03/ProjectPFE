<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $clients = Client::all();
    return response()->json(['data' => $clients]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email|unique:clients',
        'password' => 'required|min:6',
        'phone' => 'required',
        'address' => 'required',
        'city' => 'required',
        'country' => 'required',
        'postal_code' => 'required',
    ]);

    $client = Client::create($validatedData);

    return response()->json([
        'status' => 'success',
        'message' => 'Client created successfully',
        'data' => $client
    ], 201);
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::find($id);
        if ($client) {
            return response()->json($client);
        } else {
            return response()->json(['message' => 'Client not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:clients,email,'.$id,
            'password' => 'required|min:6',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postal_code' => 'required'
        ]);

        $client = Client::find($id);
        if ($client) {
            $client->update($validatedData);
            return response()->json($client);
        } else {
            return response()->json(['message' => 'Client not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        if ($client) {
            $client->delete();
            return response()->json(['message' => 'Client deleted successfully']);
        } else {
            return response()->json(['message' => 'Client not found'], 404);
        }
    }
}
