<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\User;
use App\Notifications\NewTransformationRequestNotification;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class TransformationPaymentController extends Controller
{
    /**
     * Display the YARA payment summary page.
     */
    public function show(Assessment $assessment)
    {
        $user = auth()->user();

/*
 * Security:
 * only the client who owns this assessment
 * may access its Transformation payment page.
 */
abort_unless(
    (int) $assessment->user_id === (int) $user->id,
    403,
    'This assessment does not belong to you.'
);

        /*
         * Payment is only available after
         * the assessment itself is completed.
         */
        abort_unless(
            $assessment->status === 'completed',
            404
        );

        

       
        /*
         * The Transformation brief must already
         * exist before payment can begin.
         */
        $preference = $assessment->roadmapPreference;

        abort_unless(
            $preference,
            409
        );

        return view(
            'assessments.transformation-payment',
            compact('assessment', 'preference')
        );
    }


    /**
     * Create the Stripe Checkout Session.
     */
    public function checkout(Assessment $assessment)
    {
        $user = auth()->user();
/*
 * Security:
 * only the client who owns this assessment
 * may start checkout for it.
 */
abort_unless(
    (int) $assessment->user_id === (int) $user->id,
    403,
    'This assessment does not belong to you.'
);

        /*
         * Assessment must be completed.
         */
        abort_unless(
            $assessment->status === 'completed',
            404
        );
/*
 * Do not create another checkout once this
 * Transformation service has already been paid for.
 */
abort_if(
    $assessment->payment_status === 'paid',
    409,
    'This Transformation Roadmap has already been purchased.'
);
        /*
         * A Transformation brief must exist.
         */
        abort_unless(
            $assessment->roadmapPreference()->exists(),
            409
        );

        /*
         * Configure Stripe using the TEST secret key
         * stored in config/services.php.
         */
        Stripe::setApiKey(
            config('services.stripe.secret')
        );

        /*
         * Create Stripe Checkout Session.
         */
        $session = StripeSession::create([
            'mode' => 'payment',

            'payment_method_types' => [
                'card',
            ],

            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',

                        'product_data' => [
                            'name' => 'YARA Transformation Roadmap',

                            'description' =>
                                'Expert-reviewed AI Transformation Roadmap',
                        ],

                        /*
                         * TEST PRICE
                         *
                         * 19900 cents = €199.00
                         */
                        'unit_amount' => 19900,
                    ],

                    'quantity' => 1,
                ],
            ],

            /*
             * Metadata lets us verify later that
             * this Stripe Session belongs to the
             * correct assessment.
             */
            'metadata' => [
                'assessment_id' => (string) $assessment->id,
                'user_id' => (string) $user->id,
            ],

            /*
             * Stripe redirects here after
             * successful payment.
             */
            'success_url' =>
                route(
                    'transformation.payment.success',
                    $assessment
                )
                . '?session_id={CHECKOUT_SESSION_ID}',

            /*
             * If the client cancels Stripe Checkout,
             * return them to the YARA payment page.
             */
            'cancel_url' =>
                route(
                    'transformation.payment.page',
                    $assessment
                ),
        ]);

        /*
         * Remember the Stripe Session.
         *
         * The Transformation is NOT submitted yet.
         */
        $assessment->update([
            'payment_status' => 'pending',

            'stripe_checkout_session_id' => $session->id,
        ]);

        /*
         * Redirect to Stripe's hosted Checkout.
         */
        return redirect()->away($session->url);
    }


    /**
     * Handle successful Stripe Checkout return.
     */
    public function success(
        Request $request,
        Assessment $assessment
    ) {
        $user = auth()->user();

/*
 * Security:
 * only the client who owns this assessment
 * may complete its payment return flow.
 */
abort_unless(
    (int) $assessment->user_id === (int) $user->id,
    403,
    'This assessment does not belong to you.'
);

        /*
         * Get the Stripe Checkout Session ID
         * returned by Stripe.
         */
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            abort(
                400,
                'Missing Stripe Checkout session.'
            );
        }

        /*
         * Configure Stripe.
         */
        Stripe::setApiKey(
            config('services.stripe.secret')
        );

        /*
         * Retrieve the Checkout Session directly
         * from Stripe.
         */
        $session = StripeSession::retrieve(
            $sessionId
        );

        /*
         * Make sure the Stripe Session belongs
         * to THIS assessment.
         */
        if (
            (string) ($session->metadata->assessment_id ?? '')
            !== (string) $assessment->id
        ) {
            abort(403);
        }
        /*
 * Make sure the Stripe Session also belongs
 * to the currently authenticated client.
 */
if (
    (string) ($session->metadata->user_id ?? '')
    !== (string) $user->id
) {
    abort(403);
}

        /*
         * Make sure this is also the Stripe Session
         * that YARA stored for this assessment.
         */
        if (
    (string) $assessment->stripe_checkout_session_id
    !== (string) $session->id
) {
    abort(403);
}

        /*
         * Do NOT trust the redirect alone.
         *
         * Stripe itself must confirm that
         * the payment is paid.
         */
        if ($session->payment_status !== 'paid') {
            return redirect()
                ->route(
                    'transformation.payment.page',
                    $assessment
                )
                ->with(
                    'error',
                    'Payment was not completed.'
                );
        }

        /*
         * Payment confirmed.
         *
         * NOW the Transformation request can
         * officially enter expert review.
         */
        $wasAlreadyPaid = $assessment->payment_status === 'paid';
      $assessment->update([
    'engagement_type' => 'transformation',
    'transformation_status' => 'submitted',

    'payment_status' => 'paid',
    'paid_at' => now(),

    'stripe_checkout_session_id' => $session->id,
]);
/*
 * Notify administrators only the first time
 * this successful payment is processed.
 */
if (!$wasAlreadyPaid) {

    $admins = User::where('role', 'admin')->get();

    foreach ($admins as $admin) {
        $admin->notify(
            new NewTransformationRequestNotification($assessment)
        );
    }
}
        /*
         * Show the confirmation page we
         * already created earlier.
         */
        return redirect()->route(
            'assessment.transformation.submitted',
            $assessment
        );
    }
}