<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        $user = User::where("email", request("email"))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        if (!$user->hasRole('student', 'api')) {
            return response()->json([
                'message' => 'Access denied. You are not a student.',
                'debug_roles' => $user->roles->pluck('name')->toArray()
                ], 403);
        }
        $token = $user->createToken('student_access_token')->plainTextToken;
        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(), // Useful for Vue frontend
            ],
            'token' => $token,
            'message' => 'Student logged in successfully'
        ], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $request->user()->tokens()->where('id', $token->id)->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
