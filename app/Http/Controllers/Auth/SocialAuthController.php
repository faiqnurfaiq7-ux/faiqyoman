<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth provider.
     */
    public function redirect(\Illuminate\Http\Request $request, $provider)
    {
        $allowed = ['google','facebook'];
        if (!in_array($provider, $allowed)) {
            abort(404);
        }

        // If the caller specified action=link, record intent in session so callback will attach to current user
        if ($request->query('action') === 'link' && auth()->check()) {
            session(['social_link' => true]);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider and login / register.
     */
    public function callback($provider)
    {
        $allowed = ['google','facebook'];
        if (!in_array($provider, $allowed)) {
            abort(404);
        }

        try {
            // Use stateless to avoid session issues if needed
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Autentikasi ' . $provider . ' gagal.');
        }

        // Normalize token data
        $token = $socialUser->token ?? null;
        $refresh = $socialUser->refreshToken ?? null;
        $expiresIn = $socialUser->expiresIn ?? null;
        $avatar = method_exists($socialUser, 'getAvatar') ? $socialUser->getAvatar() : ($socialUser->avatar ?? null);

        // If this was a linking intent and user is authenticated, attach to the current user
        if (session('social_link') && auth()->check()) {
            $current = auth()->user();

            // create or update social account record
            \App\Models\SocialAccount::updateOrCreate(
                ['provider' => $provider, 'provider_id' => $socialUser->getId()],
                [
                    'user_id' => $current->id,
                    'avatar' => $avatar,
                    'access_token' => $token,
                    'refresh_token' => $refresh,
                    'expires_at' => $expiresIn ? now()->addSeconds($expiresIn) : null,
                ]
            );

            // also update user provider fields
            $current->provider = $provider;
            $current->provider_id = $socialUser->getId();
            $current->save();

            session()->forget('social_link');
            return redirect()->route('profile.show')->with('success', 'Akun ' . ucfirst($provider) . ' berhasil ditautkan.');
        }

    $email = $socialUser->getEmail();
    $providerId = $socialUser->getId();
        if (!$providerId) {
            return redirect()->route('login')->with('error', 'Tidak dapat mengambil ID dari provider.');
        }

        // 1) Try to find by provider_id first (users who already connected social account)
        $user = User::where('provider', $provider)->where('provider_id', $providerId)->first();

        // 2) Otherwise try to find by email (user exists but not linked yet)
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // link account
                $user->update([ 'provider' => $provider, 'provider_id' => $providerId ]);
            }
        }

        // 3) If still no user, create a new one
        if (!$user) {
            if (!$email) {
                return redirect()->route('login')->with('error', 'Tidak dapat mengambil email dari provider.');
            }

            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? $email,
                'email' => $email,
                'password' => Str::random(24),
                'provider' => $provider,
                'provider_id' => $providerId,
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
