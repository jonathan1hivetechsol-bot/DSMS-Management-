@extends('layouts.vertical', ['title' => 'Add Teacher', 'subTitle' => 'Create New'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add New Teacher</h4>
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

                <form action="{{ route('teachers.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Teacher Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" placeholder="Enter teacher name" 
                                    value="{{ old('name') }}" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" placeholder="Enter email address" 
                                    value="{{ old('email') }}" required>
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password" placeholder="Enter password" required>
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Teacher ID *</label>
                                <input type="text" class="form-control @error('teacher_id') is-invalid @enderror" 
                                    id="teacher_id" name="teacher_id" placeholder="e.g., TCH-2024-001" 
                                    value="{{ old('teacher_id') }}" required>
                                @error('teacher_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject/Department *</label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                    id="subject" name="subject" placeholder="e.g., Mathematics" 
                                    value="{{ old('subject') }}" required>
                                @error('subject') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="qualification" class="form-label">Qualification *</label>
                                <input type="text" class="form-control @error('qualification') is-invalid @enderror" 
                                    id="qualification" name="qualification" placeholder="e.g., B.Sc, M.A" 
                                    value="{{ old('qualification') }}" required>
                                @error('qualification') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hire_date" class="form-label">Hire Date *</label>
                                <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                    id="hire_date" name="hire_date" 
                                    value="{{ old('hire_date') }}" required>
                                @error('hire_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salary" class="form-label">Salary (Annual) *</label>
                                <input type="number" class="form-control @error('salary') is-invalid @enderror" 
                                    id="salary" name="salary" placeholder="Enter annual salary" 
                                    value="{{ old('salary') }}" step="0.01" required>
                                @error('salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mt-4">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Add Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
