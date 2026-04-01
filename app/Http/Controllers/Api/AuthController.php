<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'viewer', // par défaut, un nouvel utilisateur est "viewer"
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->to(env('FRONTEND_URL').'/login?error=google_auth_failed');
        }

        // Chercher l'utilisateur existant ou le créer
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            // Créer un nouvel utilisateur
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(uniqid()), // mot de passe aléatoire
                'role' => 'viewer', // rôle par défaut
            ]);
        }

        // Créer un token Sanctum
        $token = $user->createToken('auth-token')->plainTextToken;

        // Rediriger vers le frontend avec le token dans l'URL (ou le stocker via un cookie)
        return redirect()->to(env('FRONTEND_URL').'/login/google/callback?token='.$token.'&user='.urlencode(json_encode($user)));
    }

    public function index()
    {
        $agents = User::where('role', 'agent')->get();

        return response()->json($agents);
    }
}
