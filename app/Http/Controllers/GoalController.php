<?php

namespace App\Http\Controllers;


use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    /**
     * Display a listing of the goals with filters
     */
    public function index(Request $request)
    {
        $query = Auth::user()->goals()->with('steps');

        // Filtrage par statut
        if ($request->has('status')) {
            switch($request->status) {
                case 'completed':
                    $query->where('progress', 100);
                    break;
                case 'in-progress':
                    $query->whereBetween('progress', [1, 99]);
                    break;
                case 'not-started':
                    $query->where('progress', 0);
                    break;
            }
        }

        // Tri
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        return view('goals.index', [
            'goals' => $query->paginate(10),
            'currentSort' => $sort,
            'currentDirection' => $direction,
            'statusFilter' => $request->status
        ]);
    }

    /**
     * Show the form for creating a new goal
     */
    public function create()
    {
        return view('goals.create');
    }

    /**
     * Store a newly created goal in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:study,sport,project,health',
            'visibility' => 'required|in:private,friends,public',
            'steps' => 'sometimes|array',
            'steps.*.title' => 'required|string|max:255',
            'steps.*.deadline' => 'required|date',
            'steps.*.priority' => 'in:high,medium,low'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $goal = Auth::user()->goals()->create($request->only([
            'title', 'type', 'visibility'
        ]));

        if ($request->has('steps')) {
            foreach ($request->steps as $stepData) {
                $goal->steps()->create($stepData);
            }
            $goal->updateProgress();
        }

        return redirect()->route('goals.show', $goal);
    }

    /**
     * Display the specified goal
     */
    public function show(Goal $goal)
    {
        $this->authorize('view', $goal);

        $goal->load(['steps' => function($query) {
            $query->orderBy('deadline')->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
        }]);

        return view('goals.show', [
            'goal' => $goal,
            'progressPercentage' => $goal->progress,
            'completedSteps' => $goal->steps->where('completed', true)->count(),
            'totalSteps' => $goal->steps->count()
        ]);
    }

    /**
     * Update the specified goal's progress
     */
    public function updateProgress(Goal $goal)
    {
        $this->authorize('update', $goal);
        
        $goal->updateProgress();
        
        return response()->json([
            'progress' => $goal->progress,
            'completed_steps' => $goal->steps()->where('completed', true)->count()
        ]);
    }

    /**
     * Remove the specified goal from storage
     */
    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        
        $goal->delete();
        
        return redirect()->route('goals.index')
            ->with('success', 'Goal deleted successfully');
    }

    /**
     * API Endpoint: Get goals data for mindmap
     */
    public function mindmapData()
    {
        $goals = Auth::user()->goals()
                    ->with('steps')
                    ->get();

        return response()->json([
            'nodes' => $this->formatMindmapNodes($goals)
        ]);
    }

    /**
     * Format goals for mindmap visualization
     */
    private function formatMindmapNodes($goals)
    {
        return $goals->map(function ($goal) {
            return [
                'id' => $goal->id,
                'label' => $goal->title,
                'type' => $goal->type,
                'progress' => $goal->progress,
                'children' => $goal->steps->map(function ($step) {
                    return [
                        'id' => 'step-'.$step->id,
                        'label' => $step->title,
                        'deadline' => $step->deadline,
                        'completed' => $step->completed
                    ];
                })
            ];
        });
    }
}