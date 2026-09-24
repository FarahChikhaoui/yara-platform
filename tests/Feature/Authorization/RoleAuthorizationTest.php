<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_access_client_dashboard(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_client_cannot_access_admin_dashboard(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_client_cannot_access_consultant_dashboard(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
        ]);

        $response = $this
            ->actingAs($client)
            ->get(route('consultant.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_client_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_consultant_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('consultant.dashboard'));

        $response->assertStatus(403);
    }

    public function test_consultant_can_access_consultant_dashboard(): void
    {
        $consultant = User::factory()->create([
            'role' => 'consultant',
        ]);

        $response = $this
            ->actingAs($consultant)
            ->get(route('consultant.dashboard'));

        $response->assertStatus(200);
    }

    public function test_consultant_cannot_access_admin_dashboard(): void
    {
        $consultant = User::factory()->create([
            'role' => 'consultant',
        ]);

        $response = $this
            ->actingAs($consultant)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_consultant_cannot_access_client_dashboard(): void
    {
        $consultant = User::factory()->create([
            'role' => 'consultant',
        ]);

        $response = $this
            ->actingAs($consultant)
            ->get(route('dashboard'));

        $response->assertStatus(403);
    }
}