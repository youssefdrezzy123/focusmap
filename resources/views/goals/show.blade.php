@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $goal->title }}</h1>
            <div class="progress mb-4">
                <div class="progress-bar" role="progressbar" 
                     style="width: {{ $goal->progress }}%" 
                     aria-valuenow="{{ $goal->progress }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                    {{ $goal->progress }}%
                </div>
            </div>
            
            <p>{{ $goal->description }}</p>
            <p><strong>Deadline:</strong> {{ $goal->deadline ? $goal->deadline->format('M d, Y') : 'No deadline' }}</p>

            <h3 class="mt-5">Steps</h3>
            <ul class="list-group mb-4">
                @foreach($goal->steps as $step)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" {{ $step->completed ? 'checked' : '' }} 
                               onchange="toggleStep({{ $step->id }})">
                        <span class="ml-2 {{ $step->completed ? 'text-muted' : '' }}">
                            {{ $step->title }}
                            <span class="badge 
                                  @if($step->priority === 'high') badge-danger
                                  @elseif($step->priority === 'medium') badge-warning
                                  @else badge-info @endif ml-2">
                                {{ ucfirst($step->priority) }}
                            </span>
                            @if($step->due_date)
                                <span class="badge 
                                      @if($step->due_date->isPast() && !$step->completed) badge-danger
                                      @elseif($step->due_date->isToday()) badge-warning
                                      @else badge-secondary @endif ml-2">
                                    {{ $step->due_date->format('d/m/Y') }}
                                </span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('steps.edit', $step->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="far fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('steps.destroy', $step->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                <i class="far fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>

            <a href="{{ route('steps.create', ['goal_id' => $goal->id]) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Step
            </a>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Location</div>
                <div class="card-body">
                    <div id="map" style="height: 300px;"></div>
                    @if($goal->latitude && $goal->longitude)
                        <div class="mt-2 text-center">
                            <small class="text-muted">
                                Coordinates: {{ round($goal->latitude, 4) }}, {{ round($goal->longitude, 4) }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Progress Summary Card -->
            <div class="card mt-4">
                <div class="card-header">Progress Summary</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Completed:</span>
                        <strong>{{ $goal->steps()->where('completed', true)->count() }}/{{ $goal->steps()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Due Soon:</span>
                        <strong>{{ $goal->steps()->whereDate('due_date', '<=', now()->addDays(3))->where('completed', false)->count() }}</strong>
                    </div>
                    <hr>
                    <div class="priority-summary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-danger">High</span>
                            <span>{{ $goal->steps()->where('priority', 'high')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-warning">Medium</span>
                            <span>{{ $goal->steps()->where('priority', 'medium')->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-info">Low</span>
                            <span>{{ $goal->steps()->where('priority', 'low')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize map
    function initMap() {
        const map = L.map('map').setView([{{ $goal->latitude ?? 46.2276 }}, {{ $goal->longitude ?? 2.2137 }}], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        @if($goal->latitude && $goal->longitude)
        L.marker([{{ $goal->latitude }}, {{ $goal->longitude }}])
            .addTo(map)
            .bindPopup("{{ $goal->title }}");
        @endif
    }
    document.addEventListener('DOMContentLoaded', initMap);

    // Toggle step completion
    function toggleStep(stepId) {
        fetch(`/steps/${stepId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the step');
        });
    }
</script>
@endpush

@push('styles')
<style>
    .list-group-item {
        transition: all 0.3s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.75em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .badge-danger {
        background-color: #dc3545;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-info {
        background-color: #17a2b8;
    }
    .badge-secondary {
        background-color: #6c757d;
    }
    input[type="checkbox"] {
        transform: scale(1.2);
        margin-right: 8px;
    }
    .priority-summary .badge {
        min-width: 60px;
        text-align: center;
    }
</style>
@endpush
@endsection