<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Correction ici

class AuthController extends Controller
{
    // Register user
    public function register(Request $request) 
    {
        // Validation des champs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken, 
        ], 201);
    }

    
    public function login(Request $request) 
    {
        // Validation des champs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Tentative de connexion
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 403);
        }

        // Récupérer l'utilisateur connecté
        $user = Auth::user(); // Correction ici

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken, // Correction ici
        ], 200);
    }

    // Logout user
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    // Get user details
    public function user(Request $request)
    {
        return response()->json([
            'user' => auth()->user()
        ], 200);
    }

    // Update user 
    public function update(Request $request)
    {
        $attr= $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $image = $this->saveImage($request->image, 'profile');

        auth()->user()->update([
            'name' => $attr['name'],
            'image' => $image,
        ]);
        return response([
            'user' => auth()->user(),
            'message' => 'User updated successfully'
        ], 200);
    }
}
