<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}