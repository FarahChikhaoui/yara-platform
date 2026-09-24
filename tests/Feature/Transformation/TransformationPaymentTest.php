<?php

namespace Tests\Feature\Transformation;

use App\Models\Assessment;
use App\Models\Company;
use App\Models\RoadmapPreference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransformationPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_access_transformation_payment_page(): void
    {
        // Create the client
        $user = User::factory()->create([
            'role' => 'client',
        ]);

        // Create their company
        $company = Company::create([
            'name' => 'Test Company',
            'industry' => 'Technology',
            'country' => 'Tunisia',
        ]);

        $user->company_id = $company->id;
        $user->save();

        // Create a completed assessment owned by this client
        $assessment = Assessment::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'title' => 'Transformation Assessment',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'transformation_status' => 'planning',
            'completed_at' => now(),
        ]);

        // Create the Transformation brief required before payment
        RoadmapPreference::create([
            'assessment_id' => $assessment->id,
            'target_maturity' => 'Advanced',
            'timeframe' => '12 months',
            'budget_level' => 'Medium',
            'strategic_priorities' => [
                'AI Governance',
                'Workforce Adoption',
            ],
            'constraints' => 'Test constraints',
        ]);

        // The owner opens their payment page
        $response = $this
            ->actingAs($user)
            ->get(route(
                'transformation.payment.page',
                $assessment
            ));

        // They should be allowed in
        $response->assertStatus(200);
    }


    public function test_other_client_cannot_access_transformation_payment_page(): void
    {
        // Client A - owns the assessment
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

        // Client B - different client
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

        // Assessment belongs to Client A
        $assessment = Assessment::create([
            'company_id' => $ownerCompany->id,
            'user_id' => $owner->id,
            'title' => 'Transformation Assessment',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'transformation_status' => 'planning',
            'completed_at' => now(),
        ]);

        // Transformation brief exists
        RoadmapPreference::create([
            'assessment_id' => $assessment->id,
            'target_maturity' => 'Advanced',
            'timeframe' => '12 months',
            'budget_level' => 'Medium',
            'strategic_priorities' => [
                'AI Governance',
            ],
            'constraints' => null,
        ]);

        // Client B tries to open Client A's payment page
        $response = $this
            ->actingAs($otherClient)
            ->get(route(
                'transformation.payment.page',
                $assessment
            ));

        // YARA must reject them
        $response->assertStatus(403);
    }
}