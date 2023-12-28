<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EmailVerificationNotificationController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => __('Користувач не знайдений.')], 404);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();

            return response()->json(['message' => __('Лінк для підтвердження надісланий.')]);
        }

        return response()->json(['message' => __('Пошта вже верифікована.')]);
    }
}
