<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Smoke-renders every admin page with a faked admin session so blade/runtime
 * errors surface in CI before a deploy. Read-only: no data is mutated.
 */
class AdminPagesSmokeTest extends TestCase
{
    /**
     * Routes to render. Each entry: [route name, extra params, query string].
     */
    private array $pages = [
        ['dashboard'],
        ['agents.index'],
        ['agents.create'],
        ['agents.edit', ['id' => 1]],
        ['passports.index'],
        ['passports.create'],
        ['passports.edit', ['id' => 1]],
        ['passports.show', ['id' => 1]],
        ['employees.index'],
        ['employees.create'],
        ['employees.edit', ['id' => 1]],
        ['categories.index'],
        ['categories.create'],
        ['categories.edit', ['id' => 1]],
        ['agencies.index'],
        ['agencies.create'],
        ['agencies.edit', ['id' => 1]],
        ['officers.index'],
        ['officers.create'],
        ['officers.edit', ['id' => 1]],
        ['processings.index'],
        ['processings.create'],
        ['register.list'],
        ['register.form'],
        ['reports.processing'],
        ['reports.collection'],
        ['reports.summary'],
    ];

    public function test_all_admin_pages_render(): void
    {
        $admin = new User(['name' => 'Smoke Admin', 'email' => 'smoke@test.local']);

        foreach ($this->pages as [$route, $params, $query]) {
            $response = $this->withSession(['user' => $admin])
                ->get(route($route, $params ?? []) . (isset($query) ? '?' . http_build_query($query) : ''));

            $response->assertStatus(200);
        }
    }

    public function test_login_page_renders(): void
    {
        $this->get(route('login'))->assertStatus(200);
    }
}
