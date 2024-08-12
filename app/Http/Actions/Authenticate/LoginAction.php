<?php

namespace App\Http\Actions\Authenticate;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginAction
{
    public function execute(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($email)->plainTextToken;
        $user->token = $token;

        return $user;
    }
}
