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
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration details",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="cin", type="integer", example="12345678"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="password", type="string", example="Abc12345"),
     *             @OA\Property(property="address", type="string", example="123 Main Street"),
     *             @OA\Property(property="city", type="string", example="New York"),
     *             @OA\Property(property="country", type="string", example="USA"),
     *             @OA\Property(property="postal_code", type="string", example="10001"),
     *             @OA\Property(property="role", type="string", example="client", enum={"client", "pressing", "admin"}),
     *             @OA\Property(property="first_name", type="string", example="John", nullable=true),
     *             @OA\Property(property="last_name", type="string", example="Doe", nullable=true),
     *             @OA\Property(property="pressing_name", type="string", example="Acme Pressing", nullable=true),
     *             @OA\Property(property="tva", type="string", example="1234567890", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors in the request body",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            // 'cin' => [
            //     'required',
            //     'numeric',
            //     'digits:8',
            //     'unique:users,cin'
            // ],
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
        $user->email = $request->email;
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

    /**
         * Log in a user
         *
         * @OA\Post(
         *     path="/api/login",
         *     summary="Log in a user",
         *     tags={"Authentication"},
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\MediaType(
         *             mediaType="application/json",
         *             @OA\Schema(
         *                 @OA\Property(
         *                     property="email",
         *                     type="string",
         *                     format="email",
         *                     example="john@example.com"
         *                 ),
         *                 @OA\Property(
         *                     property="password",
         *                     type="string",
         *                     format="password",
         *                     example="password"
         *                 ),
         *                 required={"email", "password"}
         *             )
         *         )
         *     ),
         *     @OA\Response(
         *         response="200",
         *         description="Successful operation",
         *         @OA\JsonContent(
         *             @OA\Property(
         *                 property="message",
         *                 type="string",
         *                 example="Login successful"
         *             ),
         *             @OA\Property(
         *                 property="access_token",
         *                 type="string",
         *                 example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
         *             )
         *         )
         *     ),
         *     @OA\Response(
         *         response="401",
         *         description="Invalid email or password",
         *         @OA\JsonContent(
         *             @OA\Property(
         *                 property="message",
         *                 type="string",
         *                 example="Invalid email or password"
         *             )
         *         )
         *     ),
         *     @OA\Response(
         *         response="403",
         *         description="Account is inactive",
         *         @OA\JsonContent(
         *             @OA\Property(
         *                 property="message",
         *                 type="string",
         *                 example="Account is inactive"
         *             )
         *         )
         *     )
         * )
     */

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
        $role = $user->role;
        return response()->json([
            'message' => "Login successfully",
            'access_token' => $token,
            'role' => $role
        ]);
    }

    /**
        * @OA\Post(
        * path="/api/logout",
        * tags={"Authentication"},
        * summary="Logout the user",
        * description="Logs out the currently authenticated user",
        * security={{"bearerAuth": {}}},
        * @OA\Response(
        * response=200,
        * description="Successfully logged out"
        * ),
        * @OA\Response(
        * response=401,
        * description="Unauthorized action"
        * )
        * )
    */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

}

