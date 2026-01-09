<?php

// app/Services/PasswordOtpService.php
namespace App\Services;

use App\Models\Password_Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordOtpMail;

class PasswordOtpService
{
    public function generateOtp(string $email, string $ip ): bool
    {
        $user = User::where('email',$email)->first();
        if (!$user) return false;

        $otpCode = rand(100000, 999999); // 6-digit OTP

        $passwordOtp = Password_Otp::create([
            'email' => $email,
            'otp' => Hash::make($otpCode),
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $ip,
        ]);

        // Send email
        Mail::to($email)->send(new PasswordOtpMail($otpCode));

        return true;
    }

    public function verifyOtp(string $email, string $otp): ?Password_Otp
    {
        $record = Password_Otp::where('email',$email)
            ->whereNull('used_at')
            ->where('expires_at','>', now())
            ->latest()
            ->first();

        if (!$record) return null;
        if (!$record->verify($otp)) return null;

        return $record;
    }

    public function markUsed(Password_Otp $record): void
    {
        $record->update(['used_at'=>now()]);
    }
}

