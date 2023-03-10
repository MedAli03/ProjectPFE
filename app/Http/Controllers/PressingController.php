<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PressingController extends Controller
{
    public function index()
    {
        // Retrieve all users with the role of "pressing"
        $pressings = User::where('role', 'pressing')->get();

        return response()->json($pressings, 200);
    }

    public function show(User $pressing,$id)
    {
        // Find the user by ID
        $pressing = User::find($id);

        // If the pressing is not found, return a 404 error
    if (!$pressing) {
        return response()->json(['error' => 'Pressing not found'], 404);
    }


        // Check if the user has the role of "pressing"
        if ($pressing->role !== 'pressing') {
            return response()->json(['error' => 'This user is not a pressing'], 403);
        }

         

        return response()->json($pressing, 200);
    }

    public function update(Request $pressing, $id)
    {
            // Find the user by ID
            $pressing = User::find($id);

        if (!$pressing) {
            return response()->json(['error' => 'Pressing not found'], 404);
            }
    
        // Check if the user has the role of "pressing"
        if ($pressing->role !== 'pressing') {
            return response()->json(['error' => 'This user is not a pressing'], 403);
        }
    
        $validator = Validator::make($pressing->all(), [
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|unique:users,phone,'.$id,
            'password' => 'required|min:8',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            'pressing_name' =>'required' ,
            'tva' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Update the user's information
        $pressing->update([
            'email' => $pressing->input('email', $pressing->email),
            'phone' => $pressing->input('phone', $pressing->phone),
            'password' => Hash::make($pressing->input('password', $pressing->password)),
            'address' => $pressing->input('address', $pressing->address),
            'city' => $pressing->input('city', $pressing->city),
            'country' => $pressing->input('country', $pressing->country),
            'postal_code' => $pressing->input('postal_code', $pressing->postal_code),
            'pressing_name' => $pressing->input('pressing_name', $pressing->pressing_name),
            'tva' => $pressing->input('tva', $pressing->tva),
        ]);
    
        return response()->json($pressing, 200);
    }

    public function destroy(User $pressing,$id)
{
        // Find the user by ID
        $pressing = User::find($id);
        
    if (!$pressing) {
        return response()->json(['error' => 'Pressing not found'], 404);
        }

    // Check if the user has the role of "pressing"
    if ($pressing->role !== 'pressing') {
        return response()->json(['error' => 'This user is not a pressing'], 403);
    }

    // Delete the user
    $pressing->delete();

    return response()->json(['message' => 'User deleted successfully'], 200);
}
}
