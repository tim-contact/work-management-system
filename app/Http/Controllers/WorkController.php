<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\WorkSession;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WorkController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $works = auth()->user()->works()->latest()->get(); 
        return view('work.work', ['works' => $works]);
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
        $this->authorize('create', Work::class);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullalbe|in:pending,running,completed',
        ]);

        $validated['status'] = $validated['status'] ?? 'pending';

        auth()->user()->works()->create($validated); 

        return redirect('/works')->with('success', 'Work created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        $this->authorize('update', $work);
        return view ('work.edit', compact('work'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work $work)
    {
        $this->authorize('update', $work);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $work->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? $work->description,
        ]);

        return redirect('/works')->with('success', 'Work updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        $this->authorize('delete', $work);
        $work->delete();
        return redirect('/works')->with('success', 'Work deleted successfully.');     
    }

    public function start(Work $work) {
        $this->authorize('start', $work);

        if ($work->status === 'completed') {
            return back()->withErrors(['status' => 'Completed work cannot be started.']);
        }

        $runningSession = $work->workSessions()->whereNull('stopped_at')->first();

        if ($runningSession) {
            return back()->withErrors(['session' => 'A work session is already running for this work.']);
        }

        $work->workSessions()->create([
            'started_at' => now(),
        ]);

        $work->update(['status' => 'running', 'started_at' => $work->started_at ?? now()]);

        return back()->with('success', 'Work session started.');

    }

    public function stop(Work $work) {
        $this->authorize('stop', $work);

        if ($work->status === 'completed') {
            return back()->withErrors(['status' => 'Completed work cannot be stopped.']);
        }

        if ($work->status !== 'running') {
            return back()->withErrors(['status' => 'Only running work can be stopped.']);
        }

        $session = $work->workSessions()->whereNull('stopped_at')->first();

        if (! $session) {
            return back()->withErrors(['session' => 'No active work session found for this work.']);
        }

        $duration = abs(now()->diffInSeconds($session->started_at));

        $session->update([
            'stopped_at' => now(),
            'duration' => $duration,
        ]);

        $work->update(['status' => 'pending']);

        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;
        $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        return back()->with('success', "Work session stopped. Duration: {$formattedDuration}");


    }

    public function completed(Work $work) {
        $this->authorize('completed', $work);

        if ($work->status === 'completed') {
            return back()->withErrors(['status' => 'Work is already completed.']);
        }
        
        $runningSession = $work->workSessions()->whereNull('stopped_at')->first();

        if ($runningSession) {
            $duration = abs(now()->diffInSeconds($runningSession->started_at));

            $runningSession->update([
                'stopped_at' => now(),
                'duration' => $duration,
            ]);

        }

        $work->update(['status' => 'completed']);

        return back()->with('success', 'Work marked as completed.');
    }

}
