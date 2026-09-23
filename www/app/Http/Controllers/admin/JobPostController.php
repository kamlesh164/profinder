<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = JobPost::with(['user:id,name', 'city:id,name'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $totalJobs     = JobPost::count();
        $liveToday     = JobPost::whereDate('created_at', today())->count();
        $assignedJobs  = JobPost::where('status', 'assigned')->count();
        $pendingJobs   = JobPost::where('status', 'open')->count();

        return view('admin.jobpost.index', compact(
            'jobs',
        'totalJobs',
        'liveToday',
        'assignedJobs',
        'pendingJobs'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = JobPost::with([
            'user:id,name,email,phone',
            'city:id,name',
            'bids.user:id,name,email,phone',
        ])->findOrFail($id);

        return view('admin.jobpost.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
