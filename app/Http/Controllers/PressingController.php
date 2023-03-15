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

    public function updatePressingProfile(Request $request, $id)
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
    
        // Check if the pressing is modifying their own attributes
        if ($pressing->id !== $request->user()->id) {
            return response()->json(['error' => 'You can only modify your own attributes'], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
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
            'email' => $request->input('email', $pressing->email),
            'phone' => $request->input('phone', $pressing->phone),
            'password' => Hash::make($request->input('password', $pressing->password)),
            'address' => $request->input('address', $pressing->address),
            'city' => $request->input('city', $pressing->city),
            'country' => $request->input('country', $pressing->country),
            'postal_code' => $request->input('postal_code', $pressing->postal_code),
            'pressing_name' => $request->input('pressing_name', $pressing->pressing_name),
            'tva' => $request->input('tva', $pressing->tva),
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

public function activePressings()
{
    return User::where('role', 'pressing')
        ->where('is_active', true)
        ->get();
}
}
