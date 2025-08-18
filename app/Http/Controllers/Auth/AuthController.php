<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Service\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function Login(LoginRequest $LoginRequest)
    {
        $data = $this->authService->Login($LoginRequest->validated());
        return $this->success($data);
    }

    public function Logout()
    {
        $token = Auth::user()->currentAccessToken();
        $token->delete();
    }
}
