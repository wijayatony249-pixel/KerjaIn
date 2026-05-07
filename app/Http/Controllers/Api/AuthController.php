<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role'     => 'required|in:client,freelancer',
        ]);

        $user  = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);
        
        // Log the user into the session for Inertia
        auth()->login($user);
        
        $token = $user->createToken('kerjain')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password salah'], 401);
        }

        // Log the user into the session for Inertia
        auth()->login($user);

        $token = $user->createToken('kerjain')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'client') {
            $user->total_bookings = $user->bookings()->count();
            $user->active_bookings = $user->bookings()->whereIn('status', ['pending', 'accepted'])->count();
            $user->completed_bookings = $user->bookings()->where('status', 'done')->count();
        } else {
            $user->total_services = $user->services()->count();
            $user->total_bookings = $user->freelancerBookings()->count();
            $user->pending_bookings = $user->freelancerBookings()->where('status', 'pending')->count();
            $user->avg_rating = $user->reviews()->avg('rating') ?: 0;
            $user->services = $user->services()->latest()->take(3)->get();
        }

        return response()->json($user);
    }
}