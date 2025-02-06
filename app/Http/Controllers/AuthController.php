<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            "name" => $request->name,
            'email' => $request->email,
            'password' => Hash::make('default_password'), 
        ]);

        return response()->json(['message' => 'User registered successfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate otp
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5); 
        $user->save();

        // Send otp via email
        Mail::raw("Your OTP code is $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP Code');
        });

        return response()->json(['message' => 'OTP sent to your email']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp !== $request->otp || Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        Auth::login($user);

        // clear otp 
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Login successful']);
    }
}