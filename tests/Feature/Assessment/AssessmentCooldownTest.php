<?php

namespace Tests\Feature\Assessment;

use App\Models\Assessment;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentCooldownTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_assessment_blocks_new_assessment_during_three_month_cooldown(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
    'role' => 'client',
]);

        $company = Company::create([
            'name' => 'Test Company',
            'industry' => 'Technology',
            'country' => 'Tunisia',
        ]);

        $user->company_id = $company->id;
        $user->save();

        Assessment::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'title' => 'AI Readiness Assessment',
            'status' => 'completed',
            'engagement_type' => 'self_assessment',
            'completed_at' => now()->subMonth(),
        ]);

       try {
    $response = $this
        ->actingAs($user)
        ->get(route('assessment.start'));
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    dump($e->getMessage());
    throw $e;
}

        $response->assertRedirect(route('dashboard'));

        $response->assertSessionHas('assessment_cooldown');

        $this->assertDatabaseMissing('assessments', [
            'company_id' => $company->id,
            'user_id' => $user->id,
            'status' => 'in_progress',
            'engagement_type' => 'self_assessment',
        ]);
    }
public function test_completed_assessment_can_be_retaken_after_three_month_cooldown(): void
{
    $user = User::factory()->create([
        'role' => 'client',
    ]);

    $company = Company::create([
        'name' => 'Test Company',
        'industry' => 'Technology',
        'country' => 'Tunisia',
    ]);

    $user->company_id = $company->id;
    $user->save();

    Assessment::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Previous AI Readiness Assessment',
        'status' => 'completed',
        'engagement_type' => 'self_assessment',
        'completed_at' => now()->subMonths(4),
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('assessment.start'));

    $response->assertStatus(200);

    $this->assertDatabaseHas('assessments', [
        'company_id' => $company->id,
        'user_id' => $user->id,
        'status' => 'in_progress',
        'engagement_type' => 'self_assessment',
    ]);
}
public function test_existing_in_progress_assessment_is_not_blocked_by_cooldown(): void
{
    $user = User::factory()->create([
        'role' => 'client',
    ]);

    $company = Company::create([
        'name' => 'Test Company',
        'industry' => 'Technology',
        'country' => 'Tunisia',
    ]);

    $user->company_id = $company->id;
    $user->save();

    // Previous completed assessment is still within cooldown.
    Assessment::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Previous AI Readiness Assessment',
        'status' => 'completed',
        'engagement_type' => 'self_assessment',
        'completed_at' => now()->subMonth(),
    ]);

    // But the client already has an unfinished assessment.
    $inProgressAssessment = Assessment::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'AI Readiness Assessment',
        'status' => 'in_progress',
        'engagement_type' => 'self_assessment',
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('assessment.start'));

    $response->assertStatus(200);

    $response->assertViewHas(
        'assessment',
        fn ($assessment) =>
            $assessment->id === $inProgressAssessment->id
    );

    // It must resume the existing one, not create another.
    $this->assertEquals(
        1,
        Assessment::where('company_id', $company->id)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->where('engagement_type', 'self_assessment')
            ->count()
    );
}
public function test_transformation_journey_is_not_blocked_by_self_assessment_cooldown(): void
{
    $user = User::factory()->create([
        'role' => 'client',
    ]);

    $company = Company::create([
        'name' => 'Test Company',
        'industry' => 'Technology',
        'country' => 'Tunisia',
    ]);

    $user->company_id = $company->id;
    $user->save();

    // Normal assessment completed recently.
    Assessment::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Previous AI Readiness Assessment',
        'status' => 'completed',
        'engagement_type' => 'self_assessment',
        'completed_at' => now()->subMonth(),
    ]);

    // Simulate entering through the Transformation journey.
    $response = $this
        ->actingAs($user)
        ->withSession([
            'assessment_intent' => 'transformation',
        ])
        ->get(route('assessment.start'));

    $response->assertStatus(200);

    $this->assertDatabaseHas('assessments', [
        'company_id' => $company->id,
        'user_id' => $user->id,
        'status' => 'in_progress',
        'engagement_type' => 'transformation',
        'transformation_status' => 'planning',
    ]);
}


}