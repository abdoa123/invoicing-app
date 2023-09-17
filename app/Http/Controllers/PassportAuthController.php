<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
         // Define the validation rules
         $rules = [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'isAdmin' => 'required|boolean', // Assuming 'isAdmin' should be a boolean
        ];
        
         // Create a validator instance
         $validator = Validator::make($request->all(), $rules);

         // Check if validation fails
         if ($validator->fails()) {
             // Return a response with validation errors
             return response()->json([
                 'message' => 'Validation failed',
                 'errors' => $validator->errors(),
             ], 422); // 422 Unprocessable Entity status code for validation errors
         }

        // check if already user exist
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json(['error' => 'User Already Exist'], 404);
        }
        // create user 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'isAdmin' =>$request->isAdmin,
            'password' => bcrypt($request->password)

        ]);
        // generate  default toke to use
        $token = $user->createToken('LaravelAuthApp')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
 
    /**
     * Login
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        if (password_verify($request->password, $user->password)) {
            $token = $user->createToken('LaravelAuthApp')->accessToken;
    
            // Check if the user is an admin
            $isAdmin = $user->isAdmin ? 'Admin' : 'User';
    
            return response()->json(['token' => $token, 'UserRole' => $isAdmin], 200);
        } else {
            return response()->json(['error' => 'Incorrect password'], 401);
        }
    }
}