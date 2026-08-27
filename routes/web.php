<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminTransformationController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DimensionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerOptionController;
use App\Http\Controllers\MaturityLevelController;
use App\Http\Controllers\AdminAssessmentController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\RecommendationRuleController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\PulseCheckController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\TransformationPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $user = auth()->user();

    // All assessments belonging to this client's organization
    $assessments = \App\Models\Assessment::where(
        'company_id',
        $user->company_id
    )
        ->latest()
        ->get();


    // Completed assessments used for progress tracking
    $completedAssessments = \App\Models\Assessment::where(
        'company_id',
        $user->company_id
    )
        ->where('status', 'completed')
        ->whereNotNull('company_score')
        ->orderBy('created_at')
        ->get();


    // Data that will later be used by the progress chart
    $progressData = $completedAssessments->map(function ($assessment) {

        return [
            'id' => $assessment->id,
            'date' => $assessment->created_at->format('d M Y'),
            'score' => round((float) $assessment->company_score, 1),
        ];

    })->values();


    // Latest organizational readiness score
    $latestCompleted = $completedAssessments->last();

    $currentScore = $latestCompleted
        ? round((float) $latestCompleted->company_score, 1)
        : null;


    // Difference between the latest and previous completed assessment
    $previousCompleted = $completedAssessments->count() >= 2
        ? $completedAssessments[$completedAssessments->count() - 2]
        : null;

    $scoreChange = ($latestCompleted && $previousCompleted)
        ? round(
            (float) $latestCompleted->company_score
            - (float) $previousCompleted->company_score,
            1
        )
        : null;

$maturityLevels = \App\Models\MaturityLevel::orderBy('min_score')
    ->get();
    $assessmentHistory = $assessments
    ->filter(function ($assessment) {
        return $assessment->status === 'completed'
            && $assessment->company_score !== null;
    })
    ->sortBy('created_at')
    ->map(function ($assessment) use ($maturityLevels) {

        $maturity = $maturityLevels->first(function ($level) use ($assessment) {
            return $assessment->company_score >= $level->min_score
                && $assessment->company_score <= $level->max_score;
        });

        return [
            'id' => $assessment->id,
            'date' => $assessment->created_at->format('d M Y'),
            'score' => round((float) $assessment->company_score, 1),
            'level' => $maturity?->level,
            'maturity' => $maturity
                ? ($maturity->label ?? $maturity->name)
                : null,
        ];
    })
    ->values();
    // Countries used elsewhere on the dashboard
    $countries = \App\Models\CountryAIReadinessScore::select('country')
        ->distinct()
        ->orderBy('country')
        ->pluck('country');


    return view('dashboard', compact(
        'assessments',
        'countries',
        'completedAssessments',
        'progressData',
        'currentScore',
        'maturityLevels',
        'scoreChange',
        'assessmentHistory'
    ));

})->middleware(['auth', 'client'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

Route::get(
    '/assessment/{assessment}/transformation/payment',
    [TransformationPaymentController::class, 'show']
)
    ->middleware('client')
    ->name('transformation.payment.page');

Route::post(
    '/assessment/{assessment}/transformation/checkout',
    [TransformationPaymentController::class, 'checkout']
)
    ->middleware('client')
    ->name('transformation.payment.checkout');

Route::get(
    '/assessment/{assessment}/transformation/payment/success',
    [TransformationPaymentController::class, 'success']
)
    ->middleware('client')
    ->name('transformation.payment.success');


Route::post(
    '/assessment/{assessment}/start-transformation',
    [AssessmentController::class, 'startTransformationFromAssessment']
)
    ->middleware('client')
    ->name('transformation.from-assessment');


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Assessment
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'client'])->group(function () {
Route::get(
    '/assessment/{assessment}/transformation/submitted',
    [AssessmentController::class, 'transformationSubmitted']
)->middleware('auth')
 ->name('assessment.transformation.submitted');
Route::get('/assessment/start', [AssessmentController::class, 'start'])
    ->middleware('auth')
    ->name('assessment.start');

    Route::get('/transformation/start', [AssessmentController::class, 'startTransformation'])
    ->middleware('auth')
    ->name('transformation.start');

Route::get('/assessment/{assessment}/resume', [AssessmentController::class, 'resume'])
    ->middleware('auth')
    ->name('assessment.resume');
    Route::post(
    '/assessment/{assessment}/save-answer',
    [AssessmentController::class, 'saveAnswer']
)
    ->middleware('auth')
    ->name('assessment.save-answer');
Route::post('/assessment/submit', [AssessmentController::class, 'submit'])
    ->middleware('auth');

/*
 * Named "assessment.results" — this name is relied on throughout the
 * app (redirects from generate-ai-summary/generate-roadmap, the
 * "Explore country intelligence" link, etc). It was previously missing
 * its ->name(), which is what caused "Route [assessment.results] not
 * defined."
 */
Route::get('/assessment/results/{assessment}', [AssessmentController::class, 'results'])
    ->middleware('auth')
    ->name('assessment.results');

Route::post(
    '/assessment/{assessment}/generate-ai-summary',
    [AssessmentController::class, 'generateAiSummary']
)
    ->middleware('auth')
    ->name('assessment.generate-ai-summary');

    Route::post(

    '/assessment/{assessment}/roadmap-preferences',
    [AssessmentController::class, 'saveRoadmapPreferences']
)
    ->middleware('auth')
    ->name('assessment.roadmap.preferences');

    Route::get(
    '/assessment/{assessment}/transformation/roadmap/pdf',
    [AssessmentController::class, 'downloadTransformationRoadmapPdf']
)
    ->name('assessment.transformation.roadmap.pdf');

    });
Route::get('/start-full-assessment', function () {
    session(['assessment_intent' => 'assessment']);

    if (auth()->check()) {
        return redirect()->route('assessment.start');
    }

    return redirect()->route('register');
})->name('assessment.entry');


Route::get('/build-transformation-roadmap', function () {
    session(['assessment_intent' => 'transformation']);

    if (auth()->check()) {
        return redirect()->route('transformation.start');
    }

    return redirect()->route('register');
})->name('transformation.entry');
/*
 * generate-roadmap previously had no 'auth' middleware at all, even
 * though the controller assumes an authenticated user (auth()->user())
 * — added here to match every other assessment route.
 */
Route::post('/assessment/{assessment}/generate-roadmap', [AssessmentController::class, 'generateRoadmap'])
    ->middleware(['auth', 'client'])
    ->name('assessment.generate-roadmap');

Route::post('/company/store', [CompanyController::class, 'store'])
    ->middleware(['auth', 'client']);

Route::get(
    '/assessment/results/{assessment}/country',
    [AssessmentController::class, 'countryInsights']
)
    ->middleware(['auth', 'client'])
    ->name('assessment.country');
    

/*
|--------------------------------------------------------------------------
| Social Login
|--------------------------------------------------------------------------
*/

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->name('auth.callback');


/*
|--------------------------------------------------------------------------
| Pulse Check
|--------------------------------------------------------------------------
*/

/*
 * Fixed: this previously pointed at PulseCheckController::start(), the
 * SAME method used by the POST /pulse-check/start route below. That meant
 * simply visiting GET /pulse-check (e.g. clicking "Launch the Free Pulse
 * Check" from the homepage) silently created a new PulseCheck row and
 * redirected straight to the questions page, skipping the intro screen
 * entirely. This should show the intro view instead, and only create the
 * PulseCheck record once the user actually submits that page's form.
 *
 * Assumes PulseCheckController still has an intro() method that just
 * returns view('pulse-check.intro') — restore/add it if it was removed.
 */
Route::get('/pulse-check', [PulseCheckController::class, 'intro'])
    ->name('pulse.intro');

Route::post('/pulse-check/start', [PulseCheckController::class, 'start'])
    ->name('pulse.start');

Route::get('/pulse-check/{token}', [PulseCheckController::class, 'questions'])
    ->name('pulse.questions');

Route::post('/pulse-check/{token}', [PulseCheckController::class, 'submit'])
    ->name('pulse.submit');

Route::get('/pulse-check/{token}/results', [PulseCheckController::class, 'results'])
    ->name('pulse.results');

/*
|--------------------------------------------------------------------------
| Consultant / Reviewer
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'consultant'])
    ->prefix('consultant')
    ->group(function () {

        /*
         * Consultant dashboard
         * Shows paid Transformation engagements only.
         */
        Route::get(
            'dashboard',
            [ConsultantController::class, 'dashboard']
        )->name('consultant.dashboard');


        /*
         * Open a Transformation request.
         */
        Route::get(
            'assessments/{assessment}',
            [ConsultantController::class, 'review']
        )->name('consultant.assessments.review');


        /*
         * Explicitly start consultant review:
         * submitted → in_review
         */
       Route::post(
    'assessments/{assessment}/roadmap/generate',
    [ConsultantController::class, 'generateRoadmap']
)->name('consultant.roadmap.generate');

Route::patch(
    '/consultant/roadmap/initiatives/{initiative}',
    [ConsultantController::class, 'updateRoadmapInitiative']
)->name('consultant.roadmap.initiatives.update');

Route::delete(
    '/consultant/roadmap/initiatives/{initiative}',
    [ConsultantController::class, 'deleteRoadmapInitiative']
)->name('consultant.roadmap.initiatives.delete');

Route::post(
    '/consultant/assessments/{assessment}/roadmap/initiatives',
    [ConsultantController::class, 'createRoadmapInitiative']
)->name('consultant.roadmap.initiatives.create');

Route::post(
    '/consultant/assessments/{assessment}/roadmap/finalize',
    [ConsultantController::class, 'finalizeRoadmap']
)->name('consultant.roadmap.finalize');

Route::patch(
    '/consultant/assessments/{assessment}/roadmap/guidance',
    [ConsultantController::class, 'updateRoadmapGuidance']
)->name('consultant.roadmap.guidance.update');
        /*
         * Submit consultant review.
         *
         * We'll adapt the controller logic later when we build
         * the roadmap finalization workflow.
         */
        Route::post(
            'assessments/{assessment}/review',
            [ConsultantController::class, 'submitReview']
        )->name('consultant.assessments.submit-review');

    });

    
/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

        Route::resource('dimensions', DimensionController::class);

        Route::resource('questions', QuestionController::class);

        Route::resource('answer-options', AnswerOptionController::class);

        Route::resource('maturity-levels', MaturityLevelController::class);

        Route::resource(
            'recommendation-rules',
            RecommendationRuleController::class
        );
Route::get(
        '/transformations',
        [AdminTransformationController::class, 'index']
    )->name('admin.transformations.index');

    Route::patch(
    '/transformations/{assessment}/assign',
    [AdminTransformationController::class, 'assign']
)->name('admin.transformations.assign');

        Route::get(
            'assessments',
            [AdminAssessmentController::class, 'index']
        )->name('admin.assessments.index');
        
Route::patch(
    'questions/{question}/toggle-pulse',
    [QuestionController::class, 'togglePulse']
)->name('questions.toggle-pulse');
    });


require __DIR__.'/auth.php';