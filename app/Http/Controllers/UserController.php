<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
{
    $users = User::all();
    return response()->json($users);
}

public function show($id)
{
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    return response()->json($user, 200);
}

public function update(Request $request, $id)
{
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email,' . $id,
        'phone' => 'required|unique:users,phone,' . $id,
        'password' => 'required|min:8',
        'address' => 'required',
        'city' => 'required',
        'country' => 'required',
        'postal_code' => 'required',
        'role' => 'required|in:client,pressing,admin',
        'first_name' => $request->role === 'client'||'admin' ? 'required' : '',
        'last_name' => $request->role === 'client'||'admin' ? 'required' : '',
        'pressing_name' => $request->role === 'pressing' ? 'required' : '',
        'tva' => $request->role === 'pressing' ? 'required' : '',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->password = bcrypt($request->password);
    $user->address = $request->address;
    $user->city = $request->city;
    $user->country = $request->country;
    $user->postal_code = $request->postal_code;
    $user->role = $request->role;
    $user->is_active = $request->role === 'pressing' ? false : true;
    $user->is_validated = $request->role === 'pressing' ? false : true;

    if ($request->role === 'client'||'admin') {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->pressing_name = null;
        $user->tva = null;
    } elseif ($request->role === 'pressing') {
        $user->pressing_name = $request->pressing_name;
        $user->tva = $request->tva;
        $user->first_name = null;
        $user->last_name = null;
    } 

    $user->save();

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
    ], 200);
}

public function destroy($id)
{
    // Find the user by ID
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Delete the user
    $user->delete();

    return response()->json(['message' => 'User deleted successfully'], 200);
}



}

