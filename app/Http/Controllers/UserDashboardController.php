<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $reports = $user->lostReports()->latest()->get();
        $claims  = $user->claims()->with('item')->latest()->get();

        $reportStats = [
            'total'    => $reports->count(),
            'open'     => $reports->where('status', 'open')->count(),
            'matched'  => $reports->where('status', 'matched')->count(),
            'closed'   => $reports->where('status', 'closed')->count(),
        ];

        $claimStats = [
            'total'    => $claims->count(),
            'pending'  => $claims->where('status', 'pending')->count(),
            'approved' => $claims->where('status', 'approved')->count(),
            'rejected' => $claims->where('status', 'rejected')->count(),
        ];

        return view('user.dashboard', compact('reports', 'claims', 'reportStats', 'claimStats'));
    }
}
