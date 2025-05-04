<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Step;
use Illuminate\Http\Request;

class StepController extends Controller
{
    public function create(Request $request)
    {
        $goal = Goal::findOrFail($request->goal_id);
        return view('steps.create', compact('goal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'goal_id' => 'required|exists:goals,id',
            'due_date' => 'nullable|date',
        ]);

        Step::create($request->all());

        return redirect()->route('goals.show', $request->goal_id)
            ->with('success', 'Step created successfully.');
    }

    public function edit(Step $step)
    {
        return view('steps.edit', compact('step'));
    }

    public function update(Request $request, Step $step)
    {
        $request->validate([
            'title' => 'required|max:255',
            'due_date' => 'nullable|date',
        ]);

        $step->update($request->all());

        return redirect()->route('goals.show', $step->goal_id)
            ->with('success', 'Step updated successfully.');
    }

    public function destroy(Step $step)
    {
        $step->delete();
        return back()->with('success', 'Step deleted successfully.');
    }

    public function toggle(Step $step)
    {
        $step->update(['completed' => !$step->completed]);
        
        // Recalculate goal progress
        $goal = $step->goal;
        $completedSteps = $goal->steps()->where('completed', true)->count();
        $totalSteps = $goal->steps()->count();
        
        $progress = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
        $goal->update(['progress' => $progress]);

        return response()->json(['success' => true]);
    }
}