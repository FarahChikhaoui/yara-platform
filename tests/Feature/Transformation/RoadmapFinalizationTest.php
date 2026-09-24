<?php

namespace Tests\Feature\Transformation;

use App\Models\Assessment;
use App\Models\Company;
use App\Models\RoadmapInitiative;
use App\Models\TransformationRoadmap;
use App\Models\User;
use App\Notifications\TransformationRoadmapReadyNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RoadmapFinalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_consultant_can_finalize_roadmap_with_initiatives(): void
    {
        Notification::fake();

        // Create consultant
        $consultant = User::factory()->create([
            'role' => 'consultant',
        ]);

        // Create client
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

        // Create paid Transformation assigned to consultant
        $assessment = Assessment::create([
            'company_id' => $company->id,
            'user_id' => $client->id,
            'title' => 'Transformation Assessment',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'transformation_status' => 'in_review',
            'payment_status' => 'paid',
            'assigned_consultant_id' => $consultant->id,
            'completed_at' => now(),
            'paid_at' => now(),
        ]);

        // Create draft roadmap
        $roadmap = TransformationRoadmap::create([
            'assessment_id' => $assessment->id,
            'consultant_id' => $consultant->id,
            'status' => 'draft',
        ]);

        // Give the roadmap one initiative
        RoadmapInitiative::create([
            'transformation_roadmap_id' => $roadmap->id,
            'title' => 'Establish AI Governance',
            'description' => 'Create an AI governance framework.',
            'dimension' => 'Governance',
            'priority' => 'high',
            'phase' => '0-30 days',
            'effort' => 'medium',
            'impact' => 'high',
            'sort_order' => 1,
        ]);

        // Consultant clicks Finalize
        $response = $this
            ->actingAs($consultant)
            ->post(
                route('consultant.roadmap.finalize', $assessment)
            );

        // Request succeeded
        $response->assertStatus(200);

        $roadmap->refresh();
        $assessment->refresh();

        // Roadmap is now final
        $this->assertEquals(
            'ready',
            $roadmap->status
        );

        $this->assertNotNull(
            $roadmap->finalized_at
        );

        // Assessment workflow is now finished
        $this->assertEquals(
            'roadmap_ready',
            $assessment->transformation_status
        );

        $this->assertEquals(
            $consultant->id,
            $assessment->reviewed_by
        );

        $this->assertNotNull(
            $assessment->reviewed_at
        );

        // Client should be notified
        Notification::assertSentTo(
            $client,
            TransformationRoadmapReadyNotification::class
        );
    }


    public function test_roadmap_without_initiatives_cannot_be_finalized(): void
    {
        Notification::fake();

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
            'transformation_status' => 'in_review',
            'payment_status' => 'paid',
            'assigned_consultant_id' => $consultant->id,
            'completed_at' => now(),
            'paid_at' => now(),
        ]);

        // Roadmap exists...
        $roadmap = TransformationRoadmap::create([
            'assessment_id' => $assessment->id,
            'consultant_id' => $consultant->id,
            'status' => 'draft',
        ]);

        // ...but NO initiatives exist.

        $response = $this
            ->actingAs($consultant)
            ->post(
                route('consultant.roadmap.finalize', $assessment)
            );

        // YARA must refuse
        $response->assertStatus(409);

        $roadmap->refresh();
        $assessment->refresh();

        // Nothing should have been finalized
        $this->assertEquals(
            'draft',
            $roadmap->status
        );

        $this->assertNull(
            $roadmap->finalized_at
        );

        $this->assertNotEquals(
            'roadmap_ready',
            $assessment->transformation_status
        );

        // Client shouldn't receive "roadmap ready"
        Notification::assertNothingSent();
    }
}