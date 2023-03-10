<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = User::where('role', 'client')->get();
        return response()->json(['data' => $clients], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function show(User $client,$id)
    {
          // Find the user by ID
          $client = User::find($id);

          // If the pressing is not found, return a 404 error
          if (!$client) {
          return response()->json(['error' => 'Pressing not found'], 404);
          }
        
        if ($client->role !== 'client') {
            return response()->json(['error' => 'This is not a client'], 404);
        }
        return response()->json(['data' => $client], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $client,$id)
    {

        if (!$client) {
            return response()->json(['error' => 'Pressing not found'], 404);
            }
        
        if ($client->role !== 'client') {
            return response()->json(['error' => 'This is not a client'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|unique:users,phone,'.$id,
            'password' => 'required|min:8',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'first_name' => 'required',
            'last_name' =>'required' ,
            'tva' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update the user's information
        $client->update([
            'email' => $request->input('email', $client->email),
            'phone' => $request->input('phone', $client->phone),
            'password' => Hash::make($request->input('password', $client->password)),
            'address' => $request->input('address', $client->address),
            'city' => $request->input('city', $client->city),
            'country' => $request->input('country', $client->country),
            'postal_code' => $request->input('postal_code', $client->postal_code),
            'first_name' => $request->input('first_name', $client->first_name),
            'last_name' => $request->input('last_name', $client->last_name),
        ]);
        return response()->json(['data' => $client], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $client,$id)
    {
            // Find the user by ID
            $client = User::find($id);

        // If the pressing is not found, return a 404 error
        if (!$client) {
        return response()->json(['error' => 'Pressing not found'], 404);
        }

        if ($client->role !== 'client') {
            return response()->json(['error' => 'This is not a client'], 404);
        }
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully'], 200);
    }
}
