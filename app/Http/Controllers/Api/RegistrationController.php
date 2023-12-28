<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegistrationRequest $request): JsonResponse
    {
        $user = User::create([
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        event(new Registered($user));

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }
}
