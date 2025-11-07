<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Employee;

class AuthController extends Controller
{
    /* =======================
     * AUTH (LOGIN / LOGOUT)
     * ======================= */

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required']
        ]);

        $email = trim($request->email);
        $pass  = (string)$request->password;

        // 1) Try Users table (your seeded/legacy users)
        $user = User::where('email', $email)->first();
        if ($user && (
            $user->password === $pass ||              // plain text (legacy)
            Hash::check($pass, $user->password) ||    // hashed match
            ((string)($user->visible_password ?? '')) === $pass // optional visible_password
        )) {
            session(['user' => $user]);
            return redirect()->route('dashboard');
        }

        // 2) Try Registrations table (newly registered accounts)
        $reg = Registration::where('email', $email)->first();
        if ($reg && Hash::check($pass, $reg->password)) {
            if ($reg->status !== 'active') {
                return back()->with('error', 'Your account is inactive. Please contact the admin.');
            }
            session(['user' => $reg]); // store registration model in session
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect()->route('login');
    }

    /* =======================
     * REGISTRATION (CREATE/LIST/STATUS/DELETE)
     * ======================= */

    // Show registration form
    public function showRegistrationForm()
    {
         $employees = Employee::orderBy('name')->get();
    return view('auth.register', compact('employees'));
    }

    // Store registration
    public function registerStore(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:120'],
            'email'    => [
                'required','email','max:190',
                // ensure unique across registrations
                Rule::unique('registrations','email'),
                // also ensure not colliding with existing users table
                function($attr,$value,$fail){
                    if (User::where('email',$value)->exists()) {
                        $fail('This email already exists in users.');
                    }
                }
            ],
            'password' => ['required','min:6','confirmed'],
            'status'   => ['required', Rule::in(['active','inactive'])]
        ]);

        Registration::create([
            'name'     => $request->name,
            'email'    => trim($request->email),
            'password' => Hash::make($request->password),
            'status'   => $request->status,
        ]);

        return redirect()->route('register.list')->with('success','User registered successfully.');
    }

    // List registered users
    public function registerList(Request $request)
    {
        $q = trim((string)$request->q);
        $status = $request->status; // active | inactive | ''

        $query = Registration::query();

        if ($q !== '') {
            $query->where(function($qq) use ($q){
                $qq->where('name','like',"%{$q}%")
                   ->orWhere('email','like',"%{$q}%");
            });
        }
        if (in_array($status, ['active','inactive'], true)) {
            $query->where('status', $status);
        }

        $registrations = $query->latest()->paginate(15)->withQueryString();

        return view('auth.register_list', compact('registrations','q','status'));
    }

    // Toggle status (active/inactive)
    public function registerToggleStatus($id)
    {
        $reg = Registration::findOrFail($id);
        $reg->status = $reg->status === 'active' ? 'inactive' : 'active';
        $reg->save();

        return back()->with('success', 'Status updated to '.ucfirst($reg->status).'.');
    }

    // Delete a registration
    public function registerDestroy($id)
    {
        $reg = Registration::findOrFail($id);
        $reg->delete();

        return back()->with('success','Registration deleted.');
    }
}
