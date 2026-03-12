@extends('layouts.vertical', ['title' => 'Classes','subTitle' => 'Manage Classes'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">All Classes</h4>
                <a href="{{ route('classes.create') }}" class="btn btn-primary">Add Class</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    @if($classes->count() > 0)
                        @foreach($classes as $class)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title">{{ $class->name }}</h5>
                                        <span class="badge bg-primary">Class</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <p class="text-muted mb-1"><strong>Class Teacher:</strong></p>
                                        <p>
                                            @if($class->teacher)
                                                <span class="badge bg-info">{{ $class->teacher->user->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">Not Assigned</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <p class="text-muted mb-1"><small><strong>Students:</strong></small></p>
                                            <p class="fs-18 fw-bold text-primary">{{ $class->students->count() }}/{{ $class->capacity }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-muted mb-1"><small><strong>Capacity:</strong></small></p>
                                            <p class="fs-18 fw-bold">{{ $class->capacity }}</p>
                                        </div>
                                    </div>

                                    @php
                                        $occupancy = ($class->students->count() / $class->capacity) * 100;
                                    @endphp
                                    <div class="progress mb-3">
                                        <div class="progress-bar @if($occupancy > 90) bg-danger @elseif($occupancy > 70) bg-warning @else bg-success @endif" 
                                            role="progressbar" style="width: {{ $occupancy }}%" 
                                            aria-valuenow="{{ $occupancy }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ round($occupancy) }}%
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-info flex-grow-1">
                                            <i class="ri-eye-line"></i> View
                                        </a>
                                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-warning flex-grow-1">
                                            <i class="ri-edit-line"></i> Edit
                                        </a>
                                        <form action="{{ route('classes.destroy', $class) }}" method="POST" style="flex-grow: 1;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure?')">
                                                <i class="ri-delete-bin-line"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="text-center py-5 text-muted">
                                <p class="mb-2">No classes found.</p>
                                <a href="{{ route('classes.create') }}" class="btn btn-primary">Create First Class</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
