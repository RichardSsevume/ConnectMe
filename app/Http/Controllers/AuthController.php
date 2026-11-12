<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vacancy;
use App\Services\AccountNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function choose()
    {
        return view('auth.register');
    }

    public function youthForm()
    {
        return view('auth.register-youth');
    }

    public function registerYouth(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:30',
            'location' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($data + ['role' => 'youth']);
        AccountNotifier::welcome($user);

        return $this->loginAndGo($request, $user, 'youth.profile',
            'Welcome to Connect Me! Please complete your profile so employers can discover your skills. A confirmation was sent to your email and phone.');
    }

    public function employerForm()
    {
        return view('auth.register-employer');
    }

    public function registerEmployer(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',                       // contact person
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:30',
            'location' => 'required|string|max:255',
            'industry' => ['required', Rule::in(Vacancy::INDUSTRIES)],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($data + ['role' => 'employer']);
        AccountNotifier::welcome($user);

        return $this->loginAndGo($request, $user, 'employer.company',
            'Welcome to Connect Me! Please complete your company profile. A confirmation was sent to your email and phone.');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        if (Auth::user()->account_status !== 'active') {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been suspended. Please contact Connect Me.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $role = Auth::user()->role;
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function loginAndGo(Request $request, User $user, string $route, string $message)
    {
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($route)->with('success', $message);
    }
}
