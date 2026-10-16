<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 8 Pasay High Schools List
        $schoolsList = [
            'Kalayaan High School',
            'Pasay City South High School',
            'Pasay City National High School',
            'Pasay City West High School',
            'Pasay City National Science High School',
            'President Corazon “Cory” C. Aquino National High School',
            'Pasay City North High School – M. Dela Cruz Campus',
            'Pasay City East High School',
        ];

        // Read active filters and active tab parameter from URL
        $selectedSchool = $request->query('school');
        $searchKeyword  = $request->query('search');
        $activeTab      = $request->query('tab', 'overview');

        // Scout Ideals
        $ideals = [
            'motto'  => 'Laging Handa (Be Prepared)',
            'slogan' => 'Gawa, Hindi Salita (Do a Good Turn Daily)',
            'oath'   => 'On my honor I will do my best to do my duty to God and my country, the Republic of the Philippines, and to obey the Scout law; To help other people at all times; To keep myself physically strong, mentally awake, and morally straight.',
            'law'    => [
                'A Scout is Trustworthy', 'A Scout is Loyal', 'A Scout is Helpful',
                'A Scout is Friendly', 'A Scout is Courteous', 'A Scout is Kind',
                'A Scout is Obedient', 'A Scout is Cheerful', 'A Scout is Thrifty',
                'A Scout is Brave', 'A Scout is Clean', 'A Scout is Reverent'
            ]
        ];

        $pendingLeaders = collect();
        $allUsers       = collect();
        $schoolScouts   = collect();

        if ($user->role === 'admin') {
            $pendingLeaders = User::where('role', 'scout_leader')->where('status', 'pending')->get();
            
            $query = User::where('id', '!=', $user->id);
            if ($selectedSchool) {
                $query->where('school', $selectedSchool);
            }
            if ($searchKeyword) {
                $query->where(function ($q) use ($searchKeyword) {
                    $q->where('name', 'like', "%{$searchKeyword}%")
                      ->orWhere('email', 'like', "%{$searchKeyword}%");
                });
            }
            $allUsers = $query->get();

        } elseif ($user->role === 'scout_leader') {
            $query = User::whereIn('role', ['scout_leader', 'senior_scout'])->where('status', 'approved');
            if ($selectedSchool) {
                $query->where('school', $selectedSchool);
            }
            if ($searchKeyword) {
                $query->where(function ($q) use ($searchKeyword) {
                    $q->where('name', 'like', "%{$searchKeyword}%")
                      ->orWhere('email', 'like', "%{$searchKeyword}%");
                });
            }
            $allUsers = $query->get();

        } elseif ($user->role === 'senior_scout') {
            $query = User::where('role', 'senior_scout')
                         ->where('school', $user->school)
                         ->where('status', 'approved');

            if ($searchKeyword) {
                $query->where(function ($q) use ($searchKeyword) {
                    $q->where('name', 'like', "%{$searchKeyword}%")
                      ->orWhere('email', 'like', "%{$searchKeyword}%");
                });
            }
            $schoolScouts = $query->get();
        }

        return view('dashboard', compact(
            'user', 
            'ideals', 
            'pendingLeaders', 
            'allUsers', 
            'schoolScouts', 
            'schoolsList', 
            'selectedSchool', 
            'searchKeyword',
            'activeTab'
        ));
    }

    // Approve pending Scout Leader request
    public function approveLeader($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $leader = User::findOrFail($id);
        $leader->update(['status' => 'approved']);

        return redirect()->route('dashboard', ['tab' => 'requests'])
                         ->with('success', "Scout Leader {$leader->name} has been approved.");
    }

    // Reject pending Scout Leader request
    public function rejectLeader($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $leader = User::findOrFail($id);
        $leader->update(['status' => 'rejected']);

        return redirect()->route('dashboard', ['tab' => 'requests'])
                         ->with('info', "Scout Leader {$leader->name} request has been rejected.");
    }

    // Create User via Modal on Dashboard
    public function storeUser(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

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

        return redirect()->route('dashboard', ['tab' => 'users'])
                         ->with('success', 'User account created successfully.');
    }

    // Update User via Modal on Dashboard
    public function updateUser(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'phone'  => ['required', 'string', 'regex:/^09\d{9}$/', 'unique:users,phone,' . $id],
            'school' => ['required', 'string'],
            'role'   => ['required', 'string', 'in:admin,scout_leader,senior_scout'],
        ]);

        $data = [
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'school' => $request->school,
            'role'   => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:8']]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('dashboard', ['tab' => 'users'])
                        ->with('success', "User {$user->name} updated successfully.");
    }

    // Delete User from Dashboard
    public function destroyUser($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard', ['tab' => 'users'])
                         ->with('success', 'User account deleted successfully.');
    }
}