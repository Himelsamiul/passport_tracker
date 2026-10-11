<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Support\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Admin-user management (super admins only): create panel logins and
 * assign them roles. Users with role_id = null are super admins.
 */
class AdminUserController extends Controller
{
    public function __construct()
    {
        abort_unless(Access::admin()?->role_id === null, 403, 'Super admin access only.');
    }

    public function index()
    {
        return view('backend.pages.admin.users.index', [
            'users' => User::with('role')->orderBy('id')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('backend.pages.admin.users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin user created.');
    }

    public function edit(User $user)
    {
        return view('backend.pages.admin.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user->id, withPassword: true);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role_id'];
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Admin user updated.');
    }

    public function destroy(User $user)
    {
        $current = Access::admin();

        if ($current && $user->id === $current->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->role_id === null && User::whereNull('role_id')->count() === 1) {
            return redirect()->route('admin.users.index')->with('error', 'At least one super admin must remain.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Admin user deleted.');
    }

    /** Shared validation. On update, password stays optional. */
    private function validated(Request $request, ?int $ignoreId = null, bool $withPassword = false): array
    {
        $emailRule = 'required|email|max:190|unique:users,email';
        if ($ignoreId) {
            $emailRule .= ',' . $ignoreId;
        }

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => [$emailRule],
            'role_id' => ['nullable', 'exists:roles,id'],
        ];

        if (! $withPassword) {
            $rules['password'] = ['required', 'string', 'min:6'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:6'];
        }

        return $request->validate($rules);
    }
}
