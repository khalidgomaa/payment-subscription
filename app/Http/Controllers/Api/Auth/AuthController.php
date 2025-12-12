<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\HttpStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Auth\LoginRequest;    
use App\Http\Requests\Auth\RegisterRequest; 
use App\Repositories\EloquentUserRepository;

class AuthController extends Controller
{
    public function __construct(protected EloquentUserRepository $userRepository)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->userRepository->register($request->safe()->toArray());
    }

    public function login(LoginRequest $request): JsonResponse
    {

        return $this->userRepository->login($request->safe()->toArray());

    }
    
    public function logout(Request $request)
    {
        $this->userRepository->revokeUserToken($request->user());

        return Response::apiResponse(HttpStatus::OK, null, 'Logged out successfully.');
    }
}