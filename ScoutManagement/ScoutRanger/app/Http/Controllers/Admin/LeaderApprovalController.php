<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderApprovalController extends Controller
{
    // Display list of pending scout leaders
    public function index()
    {
        $pendingLeaders = User::where('role', 'scout_leader')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.leader-requests', compact('pendingLeaders'));
    }

    // Approve a Scout Leader
    public function approve($id)
    {
        $leader = User::findOrFail($id);
        $leader->update(['status' => 'approved']);

        return redirect()->back()->with('success', "Leader {$leader->name} has been approved successfully.");
    }

    // Reject a Scout Leader
    public function reject($id)
    {
        $leader = User::findOrFail($id);
        $leader->update(['status' => 'rejected']);

        return redirect()->back()->with('info', "Leader {$leader->name} request has been rejected.");
    }
}