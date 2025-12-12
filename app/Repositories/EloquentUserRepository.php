<?php

namespace App\Repositories;

use App\Models\User;
use App\Enums\HttpStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository 
{
    public function all(): Collection
    {
        return User::all();
    
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function register(array $data): JsonResponse
    {
        $user = User::create($data);

        return $this->loginResponse($user);
    }

    public function login(array $data): JsonResponse
    {
        if (!Auth::attempt($data)) {
            return $this->invalidCredentials();
        }

        $user = Auth::user();

        return $this->loginResponse($user);
    }
    private function loginResponse(User $user): JsonResponse
    {
        $token = $user->createToken('auth_token')->plainTextToken;

        return Response::apiResponse(

            HttpStatus::OK,
        
            [
                'user' => new UserResource($user),
                'token' => $token
            ],
             message: 'Logged in successfully.',

        );
    }
       private function invalidCredentials(): JsonResponse
    {
        return Response::apiResponse(
            HttpStatus::BAD_REQUEST,
            message: 'invalid_credentials',
        );
    }

    public function revokeUserToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

}
