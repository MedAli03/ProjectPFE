<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Get a list of pressings that are not active.
     *
     * @OA\Get(
     *     path="/admin/pressingnoactive",
     *     tags={"Admin"},
     *     summary="Get a list of pressings that are not active",
     *     description="Returns a JSON list of pressings that are not active.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"     
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function getPressingsNotActive(){
        $users = User::where('role', 'pressing')->where('is_active', false)->get();
        return response()->json($users);
    }

    public function getClients() {
        $clients = User::where('role', 'client')->get();
        return response()->json([
            'clients' => $clients
        ], 200);
    }

    /**
         * @OA\Post(
         *     path="/admin/activate/{id}",
         *     summary="Activate pressing account",
         *     description="Activates the pressing account with the specified ID",
         *     tags={"Admin"},
         *     @OA\Parameter(
         *         name="id",
         *         description="ID of the pressing account to activate",
         *         required=true,
         *         in="path",
         *         @OA\Schema(
         *             type="integer",
         *             format="int64"
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Success",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string", example="Pressing account activated successfully")
         *         )
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Error",
         *         @OA\JsonContent(
         *             @OA\Property(property="error", type="string", example="Unable to activate pressing account")
         *         )
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Not found",
         *         @OA\JsonContent(
         *             @OA\Property(property="error", type="string", example="Pressing account not found")
         *         )
         *     )
         * )
     */


    public function activatePressingAccount($id){
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
    /**
         * @OA\Post(
         *     path="/admin/addadmin",
         *     summary="Create admin user",
         *     description="Creates a new admin user",
         *     tags={"Admin"},
         *     @OA\RequestBody(
         *         required=true,
         *         description="Admin user data",
         *         @OA\JsonContent(
         *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
         *             @OA\Property(property="password", type="string", format="password", example="Abcd1234"),
         *             @OA\Property(property="first_name", type="string", example="John"),
         *             @OA\Property(property="last_name", type="string", example="Doe"),
         *             @OA\Property(property="phone", type="string", example="+1234567890"),
         *             @OA\Property(property="address", type="string", example="123 Main St"),
         *             @OA\Property(property="city", type="string", example="Anytown"),
         *             @OA\Property(property="country", type="string", example="US"),
         *             @OA\Property(property="postal_code", type="string", example="12345")
         *         )
         *     ),
         *     @OA\Response(
         *         response=201,
         *         description="Success",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string", example="Admin user created successfully"),
         *             @OA\Property(property="user", type="object")
         *         )
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Bad request",
         *         @OA\JsonContent(
         *             @OA\Property(property="error", type="string", example="Validation error")
         *         )
         *     ),
         *     @OA\Response(
         *         response=401,
         *         description="Unauthorized",
         *         @OA\JsonContent(
         *             @OA\Property(property="error", type="string", example="Unauthorized")
         *         )
         *     ),
         *     @OA\Response(
         *         response=500,
         *         description="Internal Server Error",
         *         @OA\JsonContent(
         *             @OA\Property(property="error", type="string", example="Could not create admin user")
         *         )
         *     )
         * )
     */


    public function createAdminUser(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|unique:users|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => [
                'required',
                'regex:/^[+]?[0-9]{8,15}$/'
            ],
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
        ]);

        $user = new User();
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->phone = $validatedData['phone'];
        $user->address = $validatedData['address'];
        $user->city = $validatedData['city'];
        $user->country = $validatedData['country'];
        $user->postal_code = $validatedData['postal_code'];
        $user->role = 'admin';
        $user->is_active = true;
        $user->is_validated = true;
        $user->save();

        return response()->json([
            'message' => 'Admin user created successfully',
            'user' => $user,
        ], 201);
    }

    public function updateAdminUser(Request $request,$id){
        $user = User::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'email' => 'required|unique:users|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => [
                'required',
                'regex:/^[+]?[0-9]{8,15}$/'
            ],
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
        ]);

        // Update the user's information
        $user->update([
            'email' => $validatedData['email'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'postal_code' => $validatedData['postal_code'],
            // only update password if provided
            'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
            // add other fields as needed
        ]);

        // Update the user's password if provided
        if (isset($validatedData['password'])) {
            $user->update([
                'password' => Hash::make($validatedData['password']),
            ]);
        }

        return response()->json([
            'message' => 'Admin user updated successfully',
            'user' => $user,
        ], 200);
    }

    public function updatePressing(Request $pressing, $id){
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