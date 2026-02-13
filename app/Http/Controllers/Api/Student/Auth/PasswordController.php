<?php

namespace App\Http\Controllers\Api\Student\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Auth\ForgotPasswordRequest;
use App\Http\Requests\Student\Auth\ResetPasswordRequest;
use App\Http\Requests\Student\Auth\ChangePasswordRequest;
use App\Services\Student\Auth\PasswordService;

class PasswordController extends Controller
{
    public function __construct(private readonly PasswordService $passwordService) {}

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->passwordService->sendOtp($request->email);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent to your email.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $this->passwordService->resetPassword(
            $request->email,
            $request->otp,
            $request->password
        );

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully.',
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        $this->passwordService->changePasswordForUser(
            $user,
            $request->current_password,
            $request->password
        );

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}
