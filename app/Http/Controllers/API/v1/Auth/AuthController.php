<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\JsonResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use JsonResponseTrait;

    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * @route api/v1/auth/register
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $response = $this->authService->signUp($request->validated());
            DB::commit();
            return $this->successResponse($response);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Unable to sign up:: ' . $e);
            return $this->error('Unable to sign up. Please try again later.');
        }
    }

    /**
     * @route api/v1/auth/login
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->login($request->validated());

            return $this->successResponse($response);
        } catch (UnauthorizedException $e) {
            Log::error('Unable to log in:: ' . $e);
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            Log::error('Unable to log in:: ' . $e);
            return $this->error('Unable to log in. Please try again later.');
        }
    }

    /**
     * @route api/v1/auth/logout
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());
            return $this->success('Successfully logged out');
        } catch (Exception $e) {
            Log::error($e);
            return $this->error('Error logging out');
        }
    }
}
