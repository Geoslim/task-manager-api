<?php

namespace App\Services;

use App\Exceptions\UnauthorizedException;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param array $data
     * @return array
     */
    public function signUp(array $data): array
    {
        $user = $this->createUser($data);

        $response['token'] = $this->authToken($user);
        $response['user'] = UserResource::make($user);
        return $response;
    }

    /**
     * Create a new user
     * @param array $data
     * @return mixed
     */
    protected function createUser(array $data): mixed
    {
        return User::create($data);
    }

    /**
     * @param array $data
     * @return array
     * @throws UnauthorizedException
     */
    public function login(array $data): array
    {
        $user = User::query()->whereEmail($data['user_name'])
            ->orWhere('phone', $data['user_name'])
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new UnauthorizedException('Invalid credentials provided');
        }

        $response['token'] = $this->authToken($user);
        $response['user'] = UserResource::make($user);;

        return $response;
    }

    /**
     * Generate authentication token
     * @param User $user
     * @return string
     */
    public function authToken(User $user): string
    {
        return $user->createToken($user->email)->plainTextToken;
    }

    /**
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
