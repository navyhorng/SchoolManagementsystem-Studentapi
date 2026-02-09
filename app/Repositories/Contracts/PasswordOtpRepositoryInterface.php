<?php

namespace App\Repositories\Contracts;

use App\Models\Password_Otp;
use App\Models\PasswordOtp;

interface PasswordOtpRepositoryInterface
{
    public function invalidateActiveOtps(string $email): void;

    public function createOtp(string $email, string $hashedOtp, \DateTimeInterface $expiresAt): Password_Otp;

    public function getLatestActiveOtp(string $email): ?Password_Otp;

    public function markUsed(Password_Otp $otpRow): void;
}
