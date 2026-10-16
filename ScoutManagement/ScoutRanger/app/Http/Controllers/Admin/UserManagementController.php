<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // List all users with role filtering
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Show create user form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['required', 'string', 'regex:/^09\d{9}$/', 'unique:users,phone'],
            'birthday' => ['required', 'date', 'before:today'],
            'age'      => ['required', 'integer', 'min:10', 'max:80'],
            'school'   => ['required', 'string'],
            'role'     => ['required', 'string', 'in:admin,scout_leader,senior_scout'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'birthday' => $request->birthday,
            'age'      => $request->age,
            'school'   => $request->school,
            'role'     => $request->role,
            'status'   => 'approved',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update user details
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'phone'    => ['required', 'string', 'regex:/^09\d{9}$/', 'unique:users,phone,' . $id],
            'birthday' => ['required', 'date', 'before:today'],
            'age'      => ['required', 'integer', 'min:10', 'max:80'],
            'school'   => ['required', 'string'],
            'role'     => ['required', 'string', 'in:admin,scout_leader,senior_scout'],
            'status'   => ['required', 'string', 'in:pending,approved,rejected'],
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'birthday' => $request->birthday,
            'age'      => $request->age,
            'school'   => $request->school,
            'role'     => $request->role,
            'status'   => $request->status,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8']]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}