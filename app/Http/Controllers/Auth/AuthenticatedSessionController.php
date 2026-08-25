<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        $user = auth()->user();

        /*
         * Admin keeps the existing admin redirect.
         */
        if ($user->role === 'admin') {
            return redirect('/admin/dimensions');
        }

        /*
         * Consultant keeps the existing consultant redirect.
         */
        if ($user->role === 'consultant') {
            return redirect('/consultant/dashboard');
        }

        /*
         * Existing clients should always land on their dashboard
         * after logging in.
         *
         * If they reached the login page after clicking one of the
         * public CTAs, we do NOT want that old CTA to automatically
         * launch an assessment after login.
         *
         * Therefore remove the temporary assessment intent.
         */
        session()->forget('assessment_intent');

        return redirect('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}