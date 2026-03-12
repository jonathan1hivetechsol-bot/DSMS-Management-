@extends('layouts.auth', ['title' => 'Select Login Portal'])

@section('content')
    <div class="col-xl-12">
        <div class="card auth-card portal-selector-card">
            <div class="card-body px-5 py-5">
                <div class="mx-auto mb-5 text-center auth-logo">
                    <a href="{{ route('second', ['dashboards', 'analytics'])}}" class="logo-dark">
                        <img src="/images/logo-dark.png" height="48" alt="logo dark">
                    </a>

                    <a href="{{ route('second', ['dashboards', 'analytics'])}}" class="logo-light">
                        <img src="/images/logo-light.png" height="44" alt="logo light">
                    </a>
                </div>

                <h2 class="fw-bold text-uppercase text-center fs-22 mb-2 text-white">Welcome to DSMS</h2>
                <p class="text-center mt-1 mb-5 text-white">Select your login portal to continue</p>

                <div class="row g-4 justify-content-center align-items-center">
                    <!-- Admin Portal -->
                    <div class="col-12 col-md-4">
                        <a href="{{ route('portal.admin.login') }}" class="portal-card text-decoration-none">
                            <div class="card border-0 shadow-lg h-100 portal-card-item admin-portal">
                                <div class="card-body text-center py-6 py-lg-8 d-flex flex-column justify-content-center align-items-center">
                                    <i class="ri-shield-admin-line text-white mb-3 mb-lg-4"></i>
                                    <h4 class="card-title fw-bold text-white mb-2 mb-lg-3">ADMIN PORTAL</h4>
                                    <p class="text-white-50 mb-0 fs-14">Full System Access</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Teacher Portal -->
                    <div class="col-12 col-md-4">
                        <a href="{{ route('portal.teacher.login') }}" class="portal-card text-decoration-none">
                            <div class="card border-0 shadow-lg h-100 portal-card-item teacher-portal">
                                <div class="card-body text-center py-6 py-lg-8 d-flex flex-column justify-content-center align-items-center">
                                    <i class="ri-book-open-line text-white mb-3 mb-lg-4"></i>
                                    <h4 class="card-title fw-bold text-white mb-2 mb-lg-3">TEACHER PORTAL</h4>
                                    <p class="text-white-50 mb-0 fs-14">Classroom Management</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Student Portal -->
                    <div class="col-12 col-md-4">
                        <a href="{{ route('portal.student.login') }}" class="portal-card text-decoration-none">
                            <div class="card border-0 shadow-lg h-100 portal-card-item student-portal">
                                <div class="card-body text-center py-6 py-lg-8 d-flex flex-column justify-content-center align-items-center">
                                    <i class="ri-graduation-cap-line text-white mb-3 mb-lg-4"></i>
                                    <h4 class="card-title fw-bold text-white mb-2 mb-lg-3">STUDENT PORTAL</h4>
                                    <p class="text-white-50 mb-0 fs-14">Academic Progress</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <p class="text-muted small">
                        <i class="ri-shield-check-line text-success"></i> 
                        Secure Enterprise Authentication
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Full Page Blue Background */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%) !important;
            min-height: 100vh;
        }

        .account-pages {
            background: transparent !important;
            padding: 1rem 0.5rem;
        }

        /* Portal Card Styling */
        .portal-card {
            transition: all 0.3s ease;
            display: block;
            height: 100%;
            text-decoration: none !important;
        }

        .portal-card:hover .portal-card-item {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3) !important;
        }

        .portal-card-item {
            transition: all 0.3s ease;
            border: none;
            border-radius: 0.75rem;
            min-height: 240px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .portal-card-item.admin-portal {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
        }

        .portal-card-item.teacher-portal {
            background: linear-gradient(135deg, #20a39e 0%, #2d9895 100%);
        }

        .portal-card-item.student-portal {
            background: linear-gradient(135deg, #ff9d00 0%, #ffb426 100%);
        }

        .auth-card.portal-selector-card {
            border: none;
            background: transparent !important;
            box-shadow: none !important;
        }

        .auth-card.portal-selector-card .card-body {
            background: transparent;
            padding: 1rem 0;
        }

        /* Card Body Spacing */
        .portal-card-item .card-body {
            padding: 1.5rem 1rem;
        }

        /* Icon Sizing - Mobile */
        .portal-card-item i {
            font-size: 3.5rem;
            margin-bottom: 0.75rem;
        }

        /* Text Sizing - Mobile */
        .portal-card-item .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .portal-card-item p {
            font-size: 0.85rem;
            margin-bottom: 0;
            line-height: 1.4;
            opacity: 0.9;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .row.g-4 {
            gap: 1.5rem !important;
        }

        h2.text-white {
            font-size: 1.5rem !important;
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #1e3a5f !important;
        }

        p.text-white {
            font-size: 0.95rem !important;
            margin-bottom: 2rem;
            color: #555 !important;
        }

        .auth-logo {
            margin-bottom: 1.5rem;
        }

        .fs-14 {
            font-size: 0.85rem;
        }

        /* Desktop: Large Screens (992px and up) - 1 by 3 Layout */
        @media (min-width: 992px) {
            .account-pages {
                padding: 2rem 1rem;
            }

            .auth-card.portal-selector-card .card-body {
                padding: 2rem 0;
            }

            .portal-card-item {
                min-height: 280px;
            }

            .portal-card-item .card-body {
                padding: 2rem 1.5rem;
            }

            /* Icon Sizing - Desktop */
            .portal-card-item i {
                font-size: 4.5rem;
                margin-bottom: 1rem;
            }

            /* Text Sizing - Desktop */
            .portal-card-item .card-title {
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .portal-card-item p {
                font-size: 0.95rem;
                margin-bottom: 0;
            }

            h2.text-white {
                font-size: 2rem !important;
                margin-bottom: 0.75rem;
            }

            p.text-white {
                font-size: 1.05rem !important;
                margin-bottom: 3rem;
            }

            .row.g-4 {
                gap: 2rem !important;
            }

            .auth-logo {
                margin-bottom: 2rem;
            }
        }
    </style>
@endsection
