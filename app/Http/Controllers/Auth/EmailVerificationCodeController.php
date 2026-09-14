<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailVerificationCodeController extends Controller
{
    /**
     * Show the 6-digit verification page.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->email_verified_at) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-code');
    }

    /**
     * Verify the submitted 6-digit code.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        $user = $request->user();

        if (!$user->email_verification_code ||
            !$user->email_verification_code_expires_at) {

            return back()->withErrors([
                'code' => 'No active verification code. Please request a new one.',
            ]);
        }

        if (now()->greaterThan($user->email_verification_code_expires_at)) {
            return back()->withErrors([
                'code' => 'This verification code has expired.',
            ]);
        }

        if (!Hash::check($request->code, $user->email_verification_code)) {
            return back()->withErrors([
                'code' => 'The verification code is incorrect.',
            ]);
        }

        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->email_verification_code_expires_at = null;
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Your email has been verified successfully.');
    }
 public function resend(Request $request): RedirectResponse
{
    $user = $request->user();

    if ($user->email_verified_at) {
        return redirect()->route('dashboard');
    }

    $key = 'verification-code-resend:' . $user->id;

    if (RateLimiter::tooManyAttempts($key, 1)) {
        $seconds = RateLimiter::availableIn($key);

        return back()->with(
            'resend_error',
            "Please wait {$seconds} seconds before requesting another code."
        );
    }

    $verificationCode = (string) random_int(100000, 999999);

    $user->email_verification_code = Hash::make($verificationCode);
    $user->email_verification_code_expires_at = now()->addMinutes(10);
    $user->save();

    Mail::send(
        'emails.verification-code',
        [
            'user' => $user,
            'verificationCode' => $verificationCode,
        ],
        function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your new verification code - YARA');
        }
    );

    RateLimiter::hit($key, 60);

    return back()->with(
        'status',
        'A new verification code has been sent to your email.'
    );
}
}