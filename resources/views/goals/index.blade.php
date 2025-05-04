@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Goals</h1>
        <a href="{{ route('goals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Goal
        </a>
    </div>

    <!-- Barre de tri et filtre -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="btn-group" role="group">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'deadline', 'direction' => request('sort') === 'deadline' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                   class="btn btn-outline-secondary {{ request('sort') === 'deadline' ? 'active' : '' }}">
                   <i class="fas fa-calendar-alt"></i> Date
                   @if(request('sort') === 'deadline')
                       <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                   @endif
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'progress', 'direction' => request('sort') === 'progress' && request('direction') === 'desc' ? 'asc' : 'desc']) }}" 
                   class="btn btn-outline-secondary {{ request('sort') === 'progress' ? 'active' : '' }}">
                   <i class="fas fa-tasks"></i> Progress
                   @if(request('sort') === 'progress')
                       <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                   @endif
                </a>
            </div>
        </div>
        <div class="col-md-6 text-md-right">
            <div class="dropdown d-inline-block">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item {{ !request('status') ? 'active' : '' }}" 
                       href="{{ request()->fullUrlWithQuery(['status' => null]) }}">
                       <i class="fas fa-list"></i> All
                    </a>
                    <a class="dropdown-item {{ request('status') === 'completed' ? 'active' : '' }}" 
                       href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}">
                       <i class="fas fa-check-circle"></i> Completed
                    </a>
                    <a class="dropdown-item {{ request('status') === 'in_progress' ? 'active' : '' }}" 
                       href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}">
                       <i class="fas fa-spinner"></i> In Progress
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des goals -->
    <div class="row">
        @forelse($goals as $goal)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between">
                        <span>{{ $goal->title }}</span>
                        @if($goal->deadline)
                            <span class="badge {{ $goal->deadline->isPast() ? 'badge-danger' : 'badge-info' }}">
                                {{ $goal->deadline->format('M d') }}
                            </span>
                        @endif
                    </h5>
                    
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped {{ $goal->progress == 100 ? 'bg-success' : '' }}" 
                             role="progressbar" 
                             style="width: {{ $goal->progress }}%" 
                             aria-valuenow="{{ $goal->progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ $goal->progress }}%
                        </div>
                    </div>
                    
                    <p class="card-text">{{ Str::limit($goal->description, 100) }}</p>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('goals.show', $goal->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="far fa-eye"></i> View
                        </a>
                        <div>
                            <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="far fa-edit"></i>
                            </a>
                            <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to delete this goal?')">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                No goals found. <a href="{{ route('goals.create') }}">Create your first goal</a>.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $goals->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-bar {
        transition: width 0.6s ease;
    }
    .dropdown-item.active {
        background-color: #4285F4;
    }
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endpush