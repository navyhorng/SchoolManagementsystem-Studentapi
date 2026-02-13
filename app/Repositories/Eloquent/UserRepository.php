<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function updatePassword(User $user, string $hashedPassword): void
    {
        $user->password = $hashedPassword;
        $user->save();
    }
}
