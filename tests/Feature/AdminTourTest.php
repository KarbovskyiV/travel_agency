<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTourTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_access_adding_tour(): void
    {
        $travel = Travel::factory()->create();
        $response = $this->postJson('/api/v1/admin/tours/'.$travel->id);

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_tour(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('name', '=', 'editor')->value('id'));
        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/admin/tours/'.$travel->id);

        $response->assertStatus(403);
    }

    public function test_saves_tour_successfully_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('name', '=', 'admin')->value('id'));
        $travel = Travel::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/admin/tours/'.$travel->id, [
            'name' => 'Tour name',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/v1/admin/tours/'.$travel->id, [
            'name' => 'Tour name',
            'starting_date' => now()->toDateString(),
            'ending_date' => now()->addDay()->toDateString(),
            'price' => 123.45,
        ]);

        $response->assertStatus(201);

        $response = $this->get('api/v1/tours/'.$travel->slug);
        $response->assertJsonFragment(['name' => 'Tour name']);
    }
}
