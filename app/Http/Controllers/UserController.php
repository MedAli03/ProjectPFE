<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function index()
{
    $users = User::all();
    return response()->json($users);
}

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|unique:users,phone',
        'password' => 'required|min:8',
        'address' => 'required',
        'city' => 'required',
        'country' => 'required',
        'postal_code' => 'required',
        'role' => 'required|in:client,pressing,admin',
        'first_name' => $request->role === 'client' ? 'required' : '',
        'last_name' => $request->role === 'client' ? 'required' : '',
        'pressing_name' => $request->role === 'pressing' ? 'required' : '',
        'tva' => $request->role === 'pressing' ? 'required' : '',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $user = new User;
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

    if ($request->role === 'client') {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
    } else {
        $user->pressing_name = $request->pressing_name;
        $user->tva = $request->tva;
    }

    $user->save();

    return response()->json([
        'message' => 'User registered successfully',
        
    ], 201);
}

public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid email or password'], 401);
    }

    if (!$user->is_active) {
        return response()->json(['message' => 'Account is inactive'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => "Login successfully",
        'access_token' => $token
    ]);
}

public function activatePressingAccount($id)
{
    // Retrieve the user with the specified ID
    $user = User::findOrFail($id);

    // Check if the user is a pressing and is not already activated
    if ($user->role === 'pressing' && !$user->is_active) {
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

}


//$token = $user->createToken('auth_token')->plainTextToken;


