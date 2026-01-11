<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'email' => 'nullable|email',
            'location' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'password' => 'required|string|min:6'
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send OTP via SMS (integrate your SMS service here)
        // For demo purposes, returning OTP in response

        return response()->json([
            'message' => 'Registration successful. Please verify your phone number.',
            'user_id' => $user->id,
            'otp' => $otp // Remove in production
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string'
        ]);

        $user = User::find($request->user_id);

        if ($user->otp !== $request->otp || now()->gt($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $user->update([
            'phone_verified' => true,
            'otp' => null,
            'otp_expires_at' => null
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Phone verified successfully',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->phone_verified) {
            return response()->json(['message' => 'Phone not verified'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|unique:users,phone,' . $user->id,
            'email' => 'sometimes|nullable|email',
            'location' => 'sometimes|string'
        ]);

        $phoneChanged = $request->has('phone') && $request->phone !== $user->phone;

        if ($phoneChanged) {
            $otp = rand(100000, 999999);
            $user->update([
                'phone' => $request->phone,
                'phone_verified' => false,
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10)
            ]);

            return response()->json([
                'message' => 'Phone number updated. Please verify.',
                'requires_verification' => true,
                'otp' => $otp // Remove in production
            ]);
        }

        $user->update($request->only(['name', 'email', 'location']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
