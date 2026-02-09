<?php

namespace App\Repositories\Eloquent;

use App\Models\Password_Otp;
use App\Repositories\Contracts\PasswordOtpRepositoryInterface;

class PasswordOtpRepository implements PasswordOtpRepositoryInterface
{
    public function invalidateActiveOtps(string $email): void
    {
        Password_Otp::where('email', $email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);
    }

    public function createOtp(string $email, string $hashedOtp, \DateTimeInterface $expiresAt): Password_Otp
    {
        return Password_Otp::create([
            'email' => $email,
            'otp' => $hashedOtp,
            'expires_at' => $expiresAt,
        ]);
    }

    public function getLatestActiveOtp(string $email): ?Password_Otp
    {
        return Password_Otp::where('email', $email)
            ->whereNull('used_at')
            ->orderByDesc('id')
            ->first();
    }

    public function markUsed(Password_Otp $otpRow): void
    {
        $otpRow->used_at = now();
        $otpRow->save();
    }
}
