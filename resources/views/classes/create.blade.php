@extends('layouts.vertical', ['title' => 'Add Class', 'subTitle' => 'Create New'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add New Class</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Class Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" placeholder="e.g., Grade 10-A, Class 5-B" 
                            value="{{ old('name') }}" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Class Teacher *</label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                    id="teacher_id" name="teacher_id" required>
                                    <option value="">-- Select Teacher --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }} ({{ $teacher->subject }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Class Capacity *</label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                    id="capacity" name="capacity" placeholder="e.g., 45" 
                                    value="{{ old('capacity') }}" min="1" max="200" required>
                                @error('capacity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <small class="text-muted d-block mt-1">Maximum number of students in this class</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mt-4">
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
