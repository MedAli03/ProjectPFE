<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
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

    return response()->json(['message' => 'User registered successfully'], 201);
}

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json(['message' => 'User login successfully'], 201);
}

}
