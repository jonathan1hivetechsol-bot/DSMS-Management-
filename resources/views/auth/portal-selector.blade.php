@extends('layouts.auth', ['title' => 'Select Portal'])

@section('content')
    <div class="col-xl-8">
        <div class="card auth-card">
            <div class="card-body px-3 py-5">
                <div class="mx-auto mb-4 text-center auth-logo">
                    <a href="{{ route('any', ['dashboards', 'analytics'])}}" class="logo-dark">
                        <img src="/images/logo-dark.png" height="32" alt="logo dark">
                    </a>

                    <a href="{{ route('any', ['dashboards', 'analytics'])}}" class="logo-light">
                        <img src="/images/logo-light.png" height="28" alt="logo light">
                    </a>
                </div>

                <h2 class="fw-bold text-uppercase text-center fs-18">Welcome, {{ Auth::user()->name }}</h2>
                <p class="text-muted text-center mt-1 mb-5">Select your portal to continue</p>

                <div class="row g-3">
                    @if(Auth::user()->role === 'admin')
                        <div class="col-md-4">
                            <a href="{{ route('portal.select', 'admin') }}" class="portal-card text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 portal-card-item admin-portal">
                                    <div class="card-body text-center py-5">
                                        <i class="ri-shield-admin-line display-4 text-danger mb-3"></i>
                                        <h5 class="card-title fw-bold">Admin Portal</h5>
                                        <p class="text-muted small">Manage school, staff, students & finances</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @elseif(Auth::user()->role === 'teacher')
                        <div class="col-md-4">
                            <a href="{{ route('portal.select', 'teacher') }}" class="portal-card text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 portal-card-item teacher-portal">
                                    <div class="card-body text-center py-5">
                                        <i class="ri-user-star-line display-4 text-primary mb-3"></i>
                                        <h5 class="card-title fw-bold">Teacher Portal</h5>
                                        <p class="text-muted small">Manage attendance, grades & classes</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @elseif(Auth::user()->role === 'student')
                        <div class="col-md-4">
                            <a href="{{ route('portal.select', 'student') }}" class="portal-card text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 portal-card-item student-portal">
                                    <div class="card-body text-center py-5">
                                        <i class="ri-user-line display-4 text-success mb-3"></i>
                                        <h5 class="card-title fw-bold">Student Portal</h5>
                                        <p class="text-muted small">View grades, attendance & announcements</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-5 text-center">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .portal-card {
            transition: all 0.3s ease;
            display: block;
        }

        .portal-card:hover .portal-card-item {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .portal-card-item {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .portal-card-item.admin-portal {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
        }

        .portal-card-item.teacher-portal {
            border-left-color: #0d6efd;
            background: linear-gradient(135deg, #f0f6ff 0%, #ffffff 100%);
        }

        .portal-card-item.student-portal {
            border-left-color: #198754;
            background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%);
        }
    </style>
@endsection
