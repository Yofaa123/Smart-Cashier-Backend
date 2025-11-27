<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function requestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $request->email;
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        PasswordResetOtp::where('email', $email)->delete();

        PasswordResetOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP terkirim ke email (simulasi).',
            'debug_otp' => $otp,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $record = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP tidak valid atau sudah kadaluarsa',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP valid',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $record = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP tidak valid atau sudah kadaluarsa',
            ], 400);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        $record->used_at = Carbon::now();
        $record->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil direset',
        ]);
    }
}
