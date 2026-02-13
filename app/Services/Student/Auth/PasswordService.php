<?php

namespace App\Services\Student\Auth;

use App\Mail\PasswordOtpMail;
use App\Models\User;
use App\Repositories\Contracts\PasswordOtpRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PasswordService
{
    public function __construct(
        private readonly PasswordOtpRepositoryInterface $otpRepo,
        private readonly UserRepositoryInterface $userRepo
    ) {}

    public function sendOtp(string $email): void
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) {
            throw ValidationException::withMessages(['email' => 'Email not found.']);
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        DB::transaction(function () use ($email, $otp, $expiresAt) {
            $this->otpRepo->invalidateActiveOtps($email);
            $this->otpRepo->createOtp($email, Hash::make($otp), $expiresAt);
        });

        Mail::to($email)->send(new PasswordOtpMail($otp));
    }

    public function resetPassword(string $email, string $otp, string $newPassword): void
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) {
            throw ValidationException::withMessages(['email' => 'Email not found.']);
        }

        $row = $this->otpRepo->getLatestActiveOtp($email);
        if (!$row) {
            throw ValidationException::withMessages(['otp' => 'OTP not found or already used.']);
        }

        if (now()->greaterThan($row->expires_at)) {
            throw ValidationException::withMessages(['otp' => 'OTP expired.']);
        }

        if (!Hash::check($otp, $row->otp)) {
            throw ValidationException::withMessages(['otp' => 'Invalid OTP.']);
        }

        DB::transaction(function () use ($user, $newPassword, $row) {
            $this->userRepo->updatePassword($user, Hash::make($newPassword));
            $this->otpRepo->markUsed($row);
        });
    }

    public function changePasswordForUser(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $this->userRepo->updatePassword($user, Hash::make($newPassword));
    }
}
