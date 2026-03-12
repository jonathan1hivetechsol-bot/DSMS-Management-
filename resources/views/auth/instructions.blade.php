@extends('layouts.auth', ['title' => 'Login Instructions'])

@section('content')
    <div class="col-xl-6">
        <div class="card auth-card">
            <div class="card-body px-4 py-5">
                <h2 class="fw-bold text-center fs-20 mb-4">🎓 Lahomes School Management System</h2>
                <h3 class="fw-semibold text-center mb-4">Login Instructions</h3>

                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading fw-bold">✅ New Feature: Portal Selector</h6>
                    <p class="mb-2">After you log in successfully, you'll see a portal selection page where you can choose your role-specific portal:</p>
                    <ul class="mb-0 ps-3">
                        <li><strong>Admin Portal</strong> - Full system access</li>
                        <li><strong>Teacher Portal</strong> - Teaching features</li>
                        <li><strong>Student Portal</strong> - Student features</li>
                    </ul>
                </div>

                <h5 class="fw-semibold border-bottom pb-2 mb-3">📝 Test Credentials</h5>

                <div class="mb-3">
                    <h6 class="fw-bold text-primary">👨‍💼 Admin Account:</h6>
                    <div class="bg-light p-2 rounded small">
                        <div><strong>Email:</strong> <code>admin@school.com</code></div>
                        <div><strong>Password:</strong> <code>password</code></div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-info">👨‍🏫 Teacher Accounts:</h6>
                    <div class="bg-light p-2 rounded small">
                        <div><strong>Password:</strong> <code>password</code> (for all teachers)</div>
                        <div class="text-muted mt-1"><em>Email addresses are randomly generated. Check database after login as admin.</em></div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-success">👨‍🎓 Student Accounts:</h6>
                    <div class="bg-light p-2 rounded small">
                        <div><strong>Password:</strong> <code>password</code> (for all students)</div>
                        <div class="text-muted mt-1"><em>Email addresses are randomly generated. Check database after login as admin.</em></div>
                    </div>
                </div>

                <hr>

                <h5 class="fw-semibold mb-3">🔧 Troubleshooting</h5>

                <div class="accordion" id="accordionFAQ">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                I don't see the portal selection page after login
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <ol>
                                    <li>Make sure you're logged in with the correct credentials</li>
                                    <li>Check that your user has a valid role (admin, teacher, or student)</li>
                                    <li>Clear your browser cache: Ctrl+Shift+Delete</li>
                                    <li>Try a different browser or incognito mode</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Login fails with "Invalid credentials"
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <ul>
                                    <li>Double-check the email address (case-sensitive)</li>
                                    <li>Verify password is exactly <code>password</code></li>
                                    <li>Make sure caps lock is off</li>
                                    <li>For teacher/student accounts, use the emails from the database</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How do I find teacher/student email addresses?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                            <div class="accordion-body">
                                <ol>
                                    <li>Login with admin credentials (admin@school.com / password)</li>
                                    <li>Go to "Teachers" or "Students" section in the sidebar</li>
                                    <li>Check the email addresses in the list</li>
                                    <li>Use those emails to login as teacher or student</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-primary">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
