<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function revokeToken($token)
    {
        $response = Http::get('https://accounts.google.com/o/oauth2/revoke', [
            'token' => $token,
        ]);

        // Check the response status code to determine if the token was revoked successfully
        if ($response->status() === 200) {
            // return redirect('/');
        } else {
            return redirect('/dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $token = Session::get('socialite_token');
        if ($token) {
            $this->revokeToken($token);
        } else {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Auth::guard('web')->logout();
            Auth::logout();
           

        }
        session()->flush();
        return redirect('/');
    }
}
