<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

public function updateAdminUser(Request $request, $id)
{
    $admin = User::find($id);

    // Check if the authenticated user has the admin role
    if ($admin->role !== 'admin') {
        return response()->json(['error' => 'you are not admin'], 403);
    }

    // Validate the request data
    $validatedData = $request->validate([
        'email' => 'required|email|unique:users,email,' . $admin->id,
        'password' => 'nullable|min:8',
        'first_name' => 'required',
        'last_name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'city' => 'required',
        'country' => 'required',
        'postal_code' => 'required',
    ]);

    // Update the user's information
    $admin->update([
        'email' => $validatedData['email'],
        'first_name' => $validatedData['first_name'],
        'last_name' => $validatedData['last_name'],
        'phone' => $validatedData['phone'],
        'address' => $validatedData['address'],
        'city' => $validatedData['city'],
        'country' => $validatedData['country'],
        'postal_code' => $validatedData['postal_code'],
    ]);

    // Update the user's password if provided
    if (isset($validatedData['password'])) {
        $admin->update([
            'password' => Hash::make($validatedData['password']),
        ]);
    }

    return response()->json([
        'message' => 'Admin user updated successfully',
        'user' => $admin,
    ], 200);
}

public function updatePressing(Request $pressing, $id)
{
        // Find the user by ID
        $pressing = User::find($id);

    if (!$pressing) {
        return response()->json(['error' => 'Pressing not found'], 404);
        }

    // Check if the user has the role of "pressing"
    if ($pressing->role !== 'admin') {
        return response()->json(['error' => 'This user is not a admin'], 403);
    }

    $validator = Validator::make($pressing->all(), [
        'email' => 'required|email|unique:users,email,'.$id,
        'phone' => 'required|unique:users,phone,'.$id,
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



}