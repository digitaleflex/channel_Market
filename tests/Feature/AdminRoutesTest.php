<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoutesTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_routes_return_200(): void
    {
        $admin = $this->adminUser();

        $routes = [
            'admin.products.index',
            'admin.orders.index',
            'admin.settings.index',
            'admin.activity.index',
            'admin.workflows.index',
        ];

        foreach ($routes as $name) {
            $this->actingAs($admin)
                ->get(route($name))
                ->assertStatus(200, "La route [$name] a retourné une erreur.");
        }
    }

    public function test_admin_routes_redirect_guests(): void
    {
        $routes = [
            'admin.products.index',
            'admin.activity.index',
            'admin.workflows.index',
        ];

        foreach ($routes as $name) {
            $this->get(route($name))->assertRedirect();
        }
    }

    public function test_admin_routes_block_non_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $routes = [
            'admin.products.index',
            'admin.activity.index',
            'admin.workflows.index',
        ];

        foreach ($routes as $name) {
            $this->actingAs($user)
                ->get(route($name))
                ->assertStatus(403);
        }
    }
}
