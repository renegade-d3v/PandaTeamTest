<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::whereId($id)->first();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if (hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
