<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:15|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'verification_code' => rand(100000, 999999),
    ]);

    // Log the verification code (you could later integrate with an SMS service)
    Log::info('Verification code for user: ' . $user->verification_code);

    return response()->json([
        'user' => $user,
        'token' => $user->createToken('auth_token')->plainTextToken,
    ]);
}


public function login(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->is_verified) {
        return response()->json(['message' => 'Account not verified'], 403);
    }

    return response()->json([
        'user' => $user,
        'token' => $user->createToken('auth_token')->plainTextToken,
    ]);
}


public function verifyCode(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'verification_code' => 'required|integer',
    ]);

    $user = User::where('phone', $request->phone)->where('verification_code', $request->verification_code)->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid verification code'], 400);
    }

    $user->is_verified = true;
    $user->save();

    return response()->json(['message' => 'Account verified successfully']);
}


}
