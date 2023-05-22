<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
 
        public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
    

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
           
            'cin' => [
                'required',
                'numeric',
                'digits:8',
                'unique:users,cin'
            ],
            'phone' => [
                'required',
                'regex:/^[+]?[0-9]{8,15}$/'
            ],
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
            'role' => 'required|in:client,pressing,admin',
            'first_name' => ($request->role === 'client' || $request->role === 'admin') ? 'required' : '',
            'last_name' => ($request->role === 'client' || $request->role === 'admin') ? 'required' : '',
            'pressing_name' => ($request->role === 'pressing') ? 'required' : '',
            'tva' => ($request->role === 'pressing') ? 'required' : '',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $user = new User;
        $user->cin = $request->cin;
        // $user->cin = $request->cin;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);   
        $user->address = $request->address;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->postal_code = $request->postal_code;
        $user->role = $request->role;
        $user->is_active = ($request->role === 'pressing') ? false : true;
        $user->is_validated = ($request->role === 'pressing') ? false : true;
    
        if ($request->role === 'client' || $request->role === 'admin') {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
        } else if ($request->role === 'pressing') {
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
            'cin' => 'required',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $user = User::where('cin', $request->cin)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid cin or password'], 401);
        }
    
        if (!$user->is_active) {
            return response()->json(['message' => 'Account is inactive'], 401);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
        $role = $user->role;
        return response()->json([
            'message' => "Login successfully",
            'access_token' => $token,
            'role' => $role
        ]);
    }
    
    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

}

