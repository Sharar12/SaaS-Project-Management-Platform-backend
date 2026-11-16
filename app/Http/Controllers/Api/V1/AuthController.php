<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => $data['password'],
        ]);

        // Link any pre-invited TeamMember rows matching this email
        TeamMember::where('email', $user->email)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        $token = $user->createToken('orbitflow-spa')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => ['id' => (string) $user->id, 'name' => $user->name, 'email' => $user->email],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', strtolower($data['email']))->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 422);
        }

        $token = $user->createToken('orbitflow-spa')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => ['id' => (string) $user->id, 'name' => $user->name, 'email' => $user->email],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'id' => (string) $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(null, 204);
    }
}
