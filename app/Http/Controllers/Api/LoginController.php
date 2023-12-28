<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->all())) {
            $user = Auth::user();
            $user->tokens()->delete();

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token]);
        }

        return response()->json(['message' => __('Не правильні користувацькі дані')], 401);
    }
}
