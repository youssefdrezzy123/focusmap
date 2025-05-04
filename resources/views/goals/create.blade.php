@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Step for Goal: {{ $goal->title }}</div>

                <div class="card-body">
                    <form action="{{ route('steps.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="goal_id" value="{{ $goal->id }}">
                        
                        <div class="form-group">
                            <label for="title">Step Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label>Priority</label>
                            <select name="priority" class="form-control">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Location (optional)</label>
                            <div id="map" style="height: 300px; margin-bottom: 15px;"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Latitude</label>
                                    <input type="text" class="form-control" id="latitude" name="latitude" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control" id="longitude" name="longitude" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Step
                            </button>
                            <a href="{{ route('goals.show', $goal->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map centered on goal location or default location
        const goalLat = {{ $goal->latitude ?? 'null' }};
        const goalLng = {{ $goal->longitude ?? 'null' }};
        
        const defaultLat = 46.2276;
        const defaultLng = 2.2137;
        
        const map = L.map('map').setView([
            goalLat || defaultLat, 
            goalLng || defaultLng
        ], goalLat ? 13 : 5);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        // Add goal location marker if exists
        if (goalLat && goalLng) {
            L.marker([goalLat, goalLng])
                .addTo(map)
                .bindPopup("Goal Location");
        }
        
        // Handle map clicks
        let marker;
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            
            marker = L.marker(e.latlng).addTo(map)
                .bindPopup("Step Location")
                .openPopup();
            
            document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
        });
        
        // If editing existing step with location
        @if(isset($step) && $step->latitude && $step->longitude)
            marker = L.marker([{{ $step->latitude }}, {{ $step->longitude }}])
                .addTo(map)
                .bindPopup("Current Step Location")
                .openPopup();
                
            document.getElementById('latitude').value = {{ $step->latitude }};
            document.getElementById('longitude').value = {{ $step->longitude }};
        @endif
    });
</script>
@endpush

@push('styles')
<style>
    #map {
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .leaflet-container {
        z-index: 0;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
</style>
@endpush
@endsection