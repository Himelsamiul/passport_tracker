<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Support\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Role management (super admins only). Permissions are stored as a flat
 * array of ability strings ("module.action") validated against
 * Access::MODULES.
 */
class RoleController extends Controller
{
    public function __construct()
    {
        // Administration screens are super-admin territory.
        abort_unless(Access::admin()?->role_id === null, 403, 'Super admin access only.');
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $roles = Role::withCount('users')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('id')
            ->get();

        return view('backend.pages.admin.roles.index', compact('roles', 'search'));
    }

    public function create()
    {
        return view('backend.pages.admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);

        // Unique slug (name collision falls back to a suffixed slug)
        if (Role::where('slug', $data['slug'])->exists()) {
            $data['slug'] .= '-' . Str::random(4);
        }

        Role::create($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('backend.pages.admin.roles.edit', ['role' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $this->validated($request);
        $role->update($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'This role is still assigned to users — reassign them first.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }

    /** Validate name + description + permissions against Access::MODULES. */
    private function validated(Request $request): array
    {
        $valid = [];
        foreach (Access::MODULES as $module => $actions) {
            foreach ($actions as $action) {
                $valid[] = $module . '.' . $action;
            }
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:' . implode(',', $valid)],
        ]);
    }
}
