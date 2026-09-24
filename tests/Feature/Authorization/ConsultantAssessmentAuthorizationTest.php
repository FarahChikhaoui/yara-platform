<?php

namespace Tests\Feature\Authorization;

use App\Models\Assessment;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultantAssessmentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_consultant_can_access_paid_transformation_assessment(): void
    {
        $consultant = User::factory()->create([
            'role' => 'consultant',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
        ]);

        $company = Company::create([
            'name' => 'Test Company',
            'industry' => 'Technology',
            'country' => 'Tunisia',
        ]);

        $client->company_id = $company->id;
        $client->save();

        $assessment = Assessment::create([
            'company_id' => $company->id,
            'user_id' => $client->id,
            'title' => 'Transformation Assessment',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'payment_status' => 'paid',
            'transformation_status' => 'submitted',
            'assigned_consultant_id' => $consultant->id,
            'completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($consultant)
            ->get(route(
                'consultant.assessments.review',
                $assessment
            ));

        $response->assertStatus(200);
    }

    public function test_unassigned_consultant_cannot_access_another_consultants_assessment(): void
    {
        $assignedConsultant = User::factory()->create([
            'role' => 'consultant',
        ]);

        $otherConsultant = User::factory()->create([
            'role' => 'consultant',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
        ]);

        $company = Company::create([
            'name' => 'Test Company',
            'industry' => 'Technology',
            'country' => 'Tunisia',
        ]);

        $client->company_id = $company->id;
        $client->save();

        $assessment = Assessment::create([
            'company_id' => $company->id,
            'user_id' => $client->id,
            'title' => 'Transformation Assessment',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'payment_status' => 'paid',
            'transformation_status' => 'submitted',
            'assigned_consultant_id' => $assignedConsultant->id,
            'completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($otherConsultant)
            ->get(route(
                'consultant.assessments.review',
                $assessment
            ));

        $response->assertStatus(403);
    }
}