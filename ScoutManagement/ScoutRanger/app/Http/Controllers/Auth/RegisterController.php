<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['required', 'string', 'regex:/^09\d{9}$/', 'unique:users,phone'],
            'birthday' => ['required', 'date', 'before:today'],
            'age'      => ['required', 'integer', 'min:10', 'max:80'],
            'school'   => ['required', 'string'],
            'role'     => ['required', 'string', 'in:senior_scout,scout_leader'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = ($request->role === 'scout_leader') ? 'pending' : 'approved';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'birthday' => $request->birthday,
            'age'      => $request->age,
            'school'   => $request->school,
            'role'     => $request->role,
            'status'   => $status,
            'password' => Hash::make($request->password),
        ]);

        if ($user->status === 'pending') {
            return redirect()->route('login')->with('status', 'Thank you for signing up! Please wait while the Admin confirms your identity.');
        }

        Auth::login($user);
        return redirect()->route('dashboard');
    }   

    // Real-time phone check AJAX endpoint
    public function checkPhone(Request $request)
    {
        $phone = $request->input('phone') ?? $request->input('phone_number');

        $exists = User::where('phone', $phone)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
}