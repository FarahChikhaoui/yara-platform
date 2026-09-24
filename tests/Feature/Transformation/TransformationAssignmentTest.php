<?php

namespace Tests\Feature\Transformation;

use App\Models\Assessment;
use App\Models\Company;
use App\Models\User;
use App\Notifications\TransformationAssignedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TransformationAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_consultant_to_paid_transformation(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

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
            'title' => 'Paid Transformation',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'transformation_status' => 'submitted',
            'payment_status' => 'paid',
            'paid_at' => now(),
            'completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route('admin.transformations.assign', $assessment),
                [
                    'consultant_id' => $consultant->id,
                ]
            );

        $assessment->refresh();

        // Consultant was really assigned
        $this->assertEquals(
            $consultant->id,
            $assessment->assigned_consultant_id
        );

        // Consultant was notified
        Notification::assertSentTo(
            $consultant,
            TransformationAssignedNotification::class
        );

        // Admin is redirected normally
        $response->assertRedirect(
            route('admin.transformations.index')
        );
    }


    public function test_unpaid_transformation_cannot_be_assigned(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

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
            'title' => 'Unpaid Transformation',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'transformation_status' => 'submitted',
            'payment_status' => 'pending',
            'completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route('admin.transformations.assign', $assessment),
                [
                    'consultant_id' => $consultant->id,
                ]
            );

        // YARA must refuse assignment
        $response->assertStatus(404);

        $assessment->refresh();

        $this->assertNull(
            $assessment->assigned_consultant_id
        );

        Notification::assertNothingSent();
    }


    public function test_admin_cannot_assign_a_non_consultant(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $normalClient = User::factory()->create([
            'role' => 'client',
        ]);

        $owner = User::factory()->create([
            'role' => 'client',
        ]);

        $company = Company::create([
            'name' => 'Test Company',
            'industry' => 'Technology',
            'country' => 'Tunisia',
        ]);

        $owner->company_id = $company->id;
        $owner->save();

        $assessment = Assessment::create([
            'company_id' => $company->id,
            'user_id' => $owner->id,
            'title' => 'Paid Transformation',
            'status' => 'completed',
            'engagement_type' => 'transformation',
            'transformation_status' => 'submitted',
            'payment_status' => 'paid',
            'paid_at' => now(),
            'completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route('admin.transformations.assign', $assessment),
                [
                    'consultant_id' => $normalClient->id,
                ]
            );

        // User exists, but is not a consultant
        $response->assertStatus(404);

        $assessment->refresh();

        $this->assertNull(
            $assessment->assigned_consultant_id
        );

        Notification::assertNothingSent();
    }
}