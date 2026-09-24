<?php

namespace Tests\Feature\Assessment;

use App\Models\Assessment;
use App\Models\BenchmarkDataset;
use App\Models\Company;
use App\Models\CountryAIReadinessScore;
use App\Models\Dimension;
use App\Models\Question;
use App\Models\AnswerOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentScoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_assessment_submission_calculates_correct_scores(): void
    {
        // -------------------------------------------------
        // 1. CREATE A FAKE CLIENT + COMPANY
        // -------------------------------------------------

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

        // -------------------------------------------------
        // 2. CREATE A FAKE DIMENSION
        // -------------------------------------------------

        $dimension = Dimension::create([
            'name' => 'Governance',
            'description' => 'Test dimension',
            'order' => 1,
            'weight' => 1,
        ]);

        // -------------------------------------------------
        // 3. CREATE TWO FAKE QUESTIONS
        // -------------------------------------------------

        $question1 = Question::create([
            'dimension_id' => $dimension->id,
            'question_text' => 'Test question 1',
            'order' => 1,
            'weight' => 1,
            'is_active' => true,
        ]);

        $question2 = Question::create([
            'dimension_id' => $dimension->id,
            'question_text' => 'Test question 2',
            'order' => 2,
            'weight' => 1,
            'is_active' => true,
        ]);

        // -------------------------------------------------
        // 4. CREATE THE ANSWERS WE WANT THE CLIENT TO SELECT
        // -------------------------------------------------

        $answer1 = AnswerOption::create([
            'question_id' => $question1->id,
            'label' => 'Fully implemented',
            'score' => 4,
            'order' => 1,
        ]);

        $answer2 = AnswerOption::create([
            'question_id' => $question2->id,
            'label' => 'Partially implemented',
            'score' => 2,
            'order' => 1,
        ]);

        // -------------------------------------------------
        // 5. CREATE THE ASSESSMENT
        // -------------------------------------------------

        $assessment = Assessment::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'title' => 'Test Assessment',
            'status' => 'in_progress',
            'engagement_type' => 'self_assessment',
        ]);

        // -------------------------------------------------
        // 6. CREATE AN ACTIVE COUNTRY BENCHMARK
        // -------------------------------------------------

        BenchmarkDataset::create([
            'name' => 'Test Benchmark 2025',
            'source' => 'Test Source',
            'year' => 2025,
            'is_active' => true,
        ]);

        CountryAIReadinessScore::create([
            'country' => 'Tunisia',
            'year' => 2025,
            'score' => 50,
        ]);

        // -------------------------------------------------
        // 7. PRETEND THE CLIENT CLICKS SUBMIT
        // -------------------------------------------------

      $response = $this
    ->actingAs($user)
    ->post('/assessment/submit', [
                'assessment_id' => $assessment->id,

                'answers' => [
                    $question1->id => $answer1->id,
                    $question2->id => $answer2->id,
                ],
            ]);

        // -------------------------------------------------
        // 8. CHECK WHAT YARA SAVED
        // -------------------------------------------------

        $assessment->refresh();

        $this->assertEquals('completed', $assessment->status);

        $this->assertNotNull($assessment->completed_at);

        $this->assertEqualsWithDelta(
            66.67,
            (float) $assessment->company_score,
            0.01
        );

        $this->assertEqualsWithDelta(
            50,
            (float) $assessment->country_ai_score,
            0.01
        );

        $this->assertEquals(2025, $assessment->country_ai_year);

        $this->assertEqualsWithDelta(
            63.33,
            (float) $assessment->combined_score,
            0.01
        );
    }
    public function test_assessment_cannot_be_submitted_when_an_active_question_is_unanswered(): void
{
    // Create a fake client and company
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

    // Create one dimension
    $dimension = Dimension::create([
        'name' => 'Governance',
        'description' => 'Test dimension',
        'order' => 1,
        'weight' => 1,
    ]);

    // Create TWO active questions
    $question1 = Question::create([
        'dimension_id' => $dimension->id,
        'question_text' => 'Test question 1',
        'order' => 1,
        'weight' => 1,
        'is_active' => true,
    ]);

    $question2 = Question::create([
        'dimension_id' => $dimension->id,
        'question_text' => 'Test question 2',
        'order' => 2,
        'weight' => 1,
        'is_active' => true,
    ]);

    // Create an answer only for question 1
    $answer1 = AnswerOption::create([
        'question_id' => $question1->id,
        'label' => 'Fully implemented',
        'score' => 4,
        'order' => 1,
    ]);

    // Create an assessment
    $assessment = Assessment::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Incomplete Assessment',
        'status' => 'in_progress',
        'engagement_type' => 'self_assessment',
    ]);

    // Client submits ONLY question 1.
    // Question 2 is intentionally missing.
    $response = $this
        ->actingAs($user)
        ->from('/assessment/test')
        ->post('/assessment/submit', [
            'assessment_id' => $assessment->id,
            'answers' => [
                $question1->id => $answer1->id,
            ],
        ]);

    // YARA should send the client back with an error
    $response->assertRedirect('/assessment/test');

    $response->assertSessionHas('assessment_error');

    $response->assertSessionHas(
        'missing_questions_count',
        1
    );

    // Reload the assessment from the database
    $assessment->refresh();

    // It must STILL be unfinished
    $this->assertEquals(
        'in_progress',
        $assessment->status
    );

    // No completion date should exist
    $this->assertNull($assessment->completed_at);

    // No scores should have been calculated
    $this->assertNull($assessment->company_score);
    $this->assertNull($assessment->combined_score);
}
public function test_client_cannot_submit_another_clients_assessment(): void
{
    // Client A = owner of the assessment
    $owner = User::factory()->create([
        'role' => 'client',
    ]);

    $ownerCompany = Company::create([
        'name' => 'Owner Company',
        'industry' => 'Technology',
        'country' => 'Tunisia',
    ]);

    $owner->company_id = $ownerCompany->id;
    $owner->save();

    // Client B = attacker / different client
    $otherClient = User::factory()->create([
        'role' => 'client',
    ]);

    $otherCompany = Company::create([
        'name' => 'Other Company',
        'industry' => 'Finance',
        'country' => 'France',
    ]);

    $otherClient->company_id = $otherCompany->id;
    $otherClient->save();

    // Create a question
    $dimension = Dimension::create([
        'name' => 'Governance',
        'description' => 'Test dimension',
        'order' => 1,
        'weight' => 1,
    ]);

    $question = Question::create([
        'dimension_id' => $dimension->id,
        'question_text' => 'Test question',
        'order' => 1,
        'weight' => 1,
        'is_active' => true,
    ]);

    $answer = AnswerOption::create([
        'question_id' => $question->id,
        'label' => 'Fully implemented',
        'score' => 4,
        'order' => 1,
    ]);

    // This assessment belongs to Client A
    $assessment = Assessment::create([
        'company_id' => $ownerCompany->id,
        'user_id' => $owner->id,
        'title' => 'Owner Assessment',
        'status' => 'in_progress',
        'engagement_type' => 'self_assessment',
    ]);

    // But Client B tries to submit it
    $response = $this
        ->actingAs($otherClient)
        ->post('/assessment/submit', [
            'assessment_id' => $assessment->id,
            'answers' => [
                $question->id => $answer->id,
            ],
        ]);

    // YARA must reject Client B
    $response->assertStatus(403);

    // Make sure the assessment was NOT modified
    $assessment->refresh();

    $this->assertEquals(
        'in_progress',
        $assessment->status
    );

    $this->assertNull($assessment->completed_at);
    $this->assertNull($assessment->company_score);
    $this->assertNull($assessment->combined_score);
}
}