<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Company;
use App\Models\User;
use App\Models\MaturityLevel;
use App\Models\Response;
use App\Models\Dimension;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // -----------------------------------------
        // GENERAL KPIs
        // -----------------------------------------

        // Total organizations registered on YARA
        $totalOrganizations = Company::count();

        // Total assessments
        $totalAssessments = Assessment::count();

        // Completed assessments
        $completedAssessments = Assessment::where(
            'status',
            'completed'
        )->count();

        // Assessments still in progress
        $inProgressAssessments = Assessment::where(
            'status',
            'in_progress'
        )->count();

        // Completion rate
        $completionRate = $totalAssessments > 0
            ? round(
                ($completedAssessments / $totalAssessments) * 100,
                1
            )
            : 0;

        // Total client users
        $totalClients = User::where(
            'role',
            'client'
        )->count();


        // -----------------------------------------
        // AI READINESS / MATURITY ANALYTICS
        // -----------------------------------------

        // Average organizational readiness score.
        // Country score is intentionally NOT included here.
        $averageCompanyScore = Assessment::where(
            'status',
            'completed'
        )
            ->whereNotNull('company_score')
            ->avg('company_score');

        $averageCompanyScore = $averageCompanyScore !== null
            ? round($averageCompanyScore, 1)
            : 0;


        // -----------------------------------------
        // MATURITY DISTRIBUTION
        // -----------------------------------------

        // Get maturity levels in score order
        $maturityLevels = MaturityLevel::orderBy(
            'min_score'
        )->get();

        // Count completed assessments in each maturity level
        $maturityDistribution = $maturityLevels->map(
            function ($level) {

                $count = Assessment::where(
                    'status',
                    'completed'
                )
                    ->whereNotNull('company_score')
                    ->whereBetween(
                        'company_score',
                        [
                            $level->min_score,
                            $level->max_score
                        ]
                    )
                    ->count();

                return [
                    'id' => $level->id,
                    'name' => $level->name,
                    'min_score' => $level->min_score,
                    'max_score' => $level->max_score,
                    'count' => $count,
                ];
            }
        );


        // -----------------------------------------
        // DIMENSION PERFORMANCE
        // -----------------------------------------

        $dimensions = Dimension::orderBy('id')->get();

        $dimensionPerformance = $dimensions->map(
            function ($dimension) {

                /*
                 * Get responses for this dimension
                 * only from completed assessments.
                 */
                $responses = Response::with([
                    'answerOption',
                    'question'
                ])
                    ->whereHas(
                        'assessment',
                        function ($query) {
                            $query->where(
                                'status',
                                'completed'
                            );
                        }
                    )
                    ->whereHas(
                        'question',
                        function ($query) use ($dimension) {
                            $query->where(
                                'dimension_id',
                                $dimension->id
                            );
                        }
                    )
                    ->get();

                $totalWeightedScore = 0;
                $totalWeight = 0;

                foreach ($responses as $response) {

                    if (
                        !$response->answerOption ||
                        !$response->question
                    ) {
                        continue;
                    }

                    $answerScore =
                        $response->answerOption->score;

                    $questionWeight =
                        $response->question->weight ?? 1;

                    /*
                     * Same normalization used by
                     * the assessment engine:
                     *
                     * 1 = 0
                     * 2 = 33.33
                     * 3 = 66.67
                     * 4 = 100
                     */
                    $normalizedScore =
                        (($answerScore - 1) / 3) * 100;

                    $totalWeightedScore +=
                        $normalizedScore * $questionWeight;

                    $totalWeight += $questionWeight;
                }

                $averageScore = $totalWeight > 0
                    ? $totalWeightedScore / $totalWeight
                    : null;

                return [
                    'id' => $dimension->id,
                    'code' => $dimension->code,
                    'name' => $dimension->name,

                    'score' => $averageScore !== null
                        ? round($averageScore, 1)
                        : null,

                    'responses' => $responses->count(),
                ];
            }
        );


        // -----------------------------------------
        // RECENT ASSESSMENTS
        // -----------------------------------------

        $recentAssessments = Assessment::with('company')
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function ($assessment) {

                return [
                    'id' => $assessment->id,

                    'organization' => $assessment->company
                        ? $assessment->company->name
                        : 'Unknown organization',

                    'status' => $assessment->status,

                    // Organizational readiness score
                    'score' => $assessment->company_score,

                    'updated_at' => $assessment->updated_at
                        ? $assessment->updated_at->diffForHumans()
                        : null,
                ];
            });


        // -----------------------------------------
        // TOP ORGANIZATIONS
        // -----------------------------------------

        /*
         * For the current MVP/test dashboard:
         *
         * 1. Only completed assessments are considered.
         * 2. Assessments must have an organizational score.
         * 3. Highest scores are shown first.
         * 4. Each organization appears only once.
         *
         * Later we can change this to explicitly use the
         * latest completed assessment per organization.
         */

        $topOrganizations = Assessment::with('company')
            ->where('status', 'completed')
            ->whereNotNull('company_score')
            ->orderByDesc('company_score')
            ->get()

            // Ignore assessments without a company
            ->filter(function ($assessment) {
                return $assessment->company !== null;
            })

            // One entry per organization
            ->unique('company_id')

            // Top 5
            ->take(5)
            ->values()

            ->map(function ($assessment) {

                return [
                    'id' => $assessment->company->id,
                    'name' => $assessment->company->name,
                    'score' => $assessment->company_score,
                ];
            });


        // -----------------------------------------
        // SEND DATA TO ADMIN DASHBOARD
        // -----------------------------------------

        return view(
            'admin.dashboard',
            compact(
                'totalOrganizations',
                'totalAssessments',
                'completedAssessments',
                'inProgressAssessments',
                'completionRate',
                'totalClients',
                'averageCompanyScore',
                'maturityDistribution',
                'dimensionPerformance',
                'recentAssessments',
                'topOrganizations'
            )
        );
    }
}