@extends('layouts.auth', ['title' => 'Student Login'])

@section('content')
    <div class="col-xl-5">
        <div class="card auth-card">
            <div class="card-body px-3 py-5">
                <!-- Back to Portal Navigation -->
                <div class="mb-3">
                    <a href="{{ route('portal.selector.view') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-arrow-left-line"></i> Back to Portal Selection
                    </a>
                </div>

                <div class="mx-auto mb-4 text-center auth-logo">
                    <a href="{{ route('second', ['dashboards', 'analytics'])}}" class="logo-dark">
                        <img src="/images/logo-dark.png" height="32" alt="logo dark">
                    </a>

                    <a href="{{ route('second', ['dashboards', 'analytics'])}}" class="logo-light">
                        <img src="/images/logo-light.png" height="28" alt="logo light">
                    </a>
                </div>

                <h2 class="fw-bold text-uppercase text-center fs-18">
                    <i class="ri-user-line text-success"></i> Student Sign In
                </h2>
                <p class="text-muted text-center mt-1 mb-4">Enter your student credentials to access the student portal.</p>

                <div class="px-4">
                    <form method="POST" action="{{ route('portal.student.login.store') }}" class="authentication-form">
                        @csrf
                        @if (sizeof($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endforeach
                        @endif

                        <div class="mb-3">
                            <label class="form-label" for="student-email">Email Address</label>
                            <input type="email" id="student-email" name="email"
                                   class="form-control bg-light bg-opacity-50 border-light py-2 @error('email') is-invalid @enderror"
                                   placeholder="student@school.com" 
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('password.request')}}"
                               class="float-end text-muted text-unline-dashed ms-1">
                                <i class="ri-lock-open-line"></i> Forgot Password?
                            </a>
                            <label class="form-label" for="student-password">Password</label>
                            <input type="password" id="student-password"
                                   class="form-control bg-light bg-opacity-50 border-light py-2 @error('password') is-invalid @enderror"
                                   placeholder="Enter your password" 
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember">
                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                            </div>
                        </div>

                        <div class="mb-1 text-center d-grid">
                            <button class="btn btn-success py-2 fw-medium" type="submit">
                                <i class="ri-login-box-line"></i> Sign In to Student Portal
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <p class="text-muted small mb-0">
                            <i class="ri-shield-check-line text-success"></i> 
                            Secure Enterprise Authentication
                        </p>
                        <a href="{{ route('auth.instructions') }}" class="text-secondary text-decoration-none small">
                            <i class="ri-question-line"></i> Need help?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .auth-card {
            border: 2px solid #198754;
        }
    </style>
@endsection
