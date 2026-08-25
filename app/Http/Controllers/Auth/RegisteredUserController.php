<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        /*
         * Validate account information.
         */
        $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:' . User::class,
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        /*
         * Store first and last names separately.
         *
         * We also keep the existing "name" field populated
         * so existing parts of YARA that use $user->name
         * continue to work normally.
         */
        $user = User::create([
            'first_name' => trim($request->first_name),
            'last_name' => trim($request->last_name),

            'name' => trim(
                $request->first_name . ' ' . $request->last_name
            ),

            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        /*
         * Fire Laravel's registered event.
         */
        event(new Registered($user));

        /*
         * Automatically authenticate the newly
         * registered client.
         */
        Auth::login($user);

        /*
         * Do NOT start the assessment immediately.
         *
         * A new client must first create their
         * organization profile.
         *
         * The assessment_intent stored in the session
         * remains available so CompanyController can
         * continue the correct flow after organization
         * creation:
         *
         * assessment     -> Full AI Readiness Assessment
         * transformation -> Transformation flow
         */
        return redirect(RouteServiceProvider::HOME);
    }
}