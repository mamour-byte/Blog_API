<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Models\User;

class AuthController extends Controller
{

    // register user 
    public function register(Resquest $request){

        // validation de champs 
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        // creation d'utilisateur 
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    
        return response()->json([
            'user' => $user ,
            'token'=>createToken('secret')->plainTextToken,
        ]);
    }

        // login user
        public function login(Resquest $request){

            // validation de champs 
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|',
            ]);
            // attemp login 
            if (!auth()->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 403);
            }

        
            return response()->json([
                'user' => $user ,
                'token'=>createToken('secret')->plainTextToken,
            ],202);
        }

        // logout user
        public function logout(Request $request){
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'Logged out successfully'
            ],202);
        }

        // get user details 
        public function user(Request $request){
            return response()->json([
                'user' => auth()->user()
            ],200);
        }

    

        
}
