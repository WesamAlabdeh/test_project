<?php

namespace App\Service;

use App\Exceptions\Errors;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function Login($data)
    {
        $user = User::where('username', $data['username'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            Errors::InvalidCredentials('Incorrect Password', 'incorrect Password');
        }

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return $response;
    }

    
}
