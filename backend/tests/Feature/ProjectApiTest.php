<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_index_returns_only_published_projects(): void
    {
        Project::factory()->create(['status' => 'completed']);
        Project::factory()->create(['status' => 'archived']);

        $response = $this->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.data');
    }

    public function test_featured_endpoint_returns_only_featured_projects(): void
    {
        Project::factory()->featured()->create(['status' => 'completed']);
        Project::factory()->create(['status' => 'completed', 'featured' => false]);

        $response = $this->getJson('/api/projects/featured');

        $response->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_show_returns_project_by_slug(): void
    {
        $project = Project::factory()->create(['status' => 'completed', 'slug' => 'my-project']);

        $response = $this->getJson('/api/projects/my-project');

        $response->assertOk()->assertJsonPath('data.slug', $project->slug);
    }

    public function test_show_returns_404_for_archived_project(): void
    {
        Project::factory()->archived()->create(['slug' => 'archived-project']);

        $this->getJson('/api/projects/archived-project')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_show_returns_404_for_unknown_slug(): void
    {
        $this->getJson('/api/projects/does-not-exist')->assertNotFound();
    }

    public function test_guest_cannot_access_admin_project_routes(): void
    {
        $this->getJson('/api/admin/projects')->assertUnauthorized();
    }

    public function test_authenticated_admin_can_create_project(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/admin/projects', [
            'title' => 'New Project',
            'slug' => 'new-project',
            'short_description' => 'Short description here.',
            'description' => 'Full description here.',
            'status' => 'planned',
        ]);

        $response->assertCreated()->assertJsonPath('data.title', 'New Project');
        $this->assertDatabaseHas('projects', ['slug' => 'new-project']);
    }

    public function test_creating_project_requires_valid_status(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/admin/projects', [
            'title' => 'Bad Project',
            'slug' => 'bad-project',
            'short_description' => 'Short.',
            'description' => 'Full.',
            'status' => 'not-a-real-status',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('status');
    }

    public function test_admin_can_soft_delete_and_restore_project(): void
    {
        $admin = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/admin/projects/{$project->id}")
            ->assertOk();

        $this->assertSoftDeleted('projects', ['id' => $project->id]);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/admin/projects/{$project->id}/restore")
            ->assertOk();

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'deleted_at' => null]);
    }
}
