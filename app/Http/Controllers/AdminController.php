<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function getPressingsNotActive()
{
    $users = User::where('role', 'pressing')->where('is_active', false)->get();
    return response()->json($users);
}


public function activatePressingAccount($id)
{
    // Retrieve the user with the specified ID
    $user = User::findOrFail($id);

      // Check if the user is a pressing
      if ($user->role === 'pressing') {
        // Check if the account is already activated
        if ($user->is_active) {
            return response()->json(['error' => 'This account is already activated'], 400);
        }

        // Activate the user
        $user->is_active = true;
        $user->save();

        // Return a success response
        return response()->json(['message' => 'Pressing account activated successfully'], 200);
    } else {
        // Return an error response
        return response()->json(['error' => 'Unable to activate pressing account'], 400);
    }
}


public function createAdminUser(Request $request, $id)
{
    $admin = User::find($id);

    // Check if the authenticated user has the admin role
    if ($admin->role !== 'admin') {
        return response()->json(['error' => 'you are not admin'], 403);
    }

    $validatedData = $request->validate([
        'email' => 'required|unique|email',
        'password' => 'required|min:8',
        'first_name' => 'required',
        'last_name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'city' => 'required',
        'country' => 'required',
        'postal_code' => 'required',
    ]);

    // Create the user with the admin role
    $user = User::create([
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
        'first_name' => $validatedData['first_name'],
        'last_name' => $validatedData['last_name'],
        'phone' => $validatedData['phone'],
        'address' => $validatedData['address'],
        'city' => $validatedData['city'],
        'country' => $validatedData['country'],
        'postal_code' => $validatedData['postal_code'],
        'role' => 'admin',
        'is_active' => true,
        // add other fields as needed
    ]);

    return response()->json([
        'message' => 'Admin user created successfully',
        'user' => $user,
    ], 201);
}


}