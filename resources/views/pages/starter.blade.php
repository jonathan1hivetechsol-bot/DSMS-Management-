@extends('layouts.vertical', ['title' => 'Welcome', 'subTitle' => 'Pages'])

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h3 class="mb-2">Welcome to Digizaro School Management System</h3>
                    <p class="text-muted">Your complete solution for managing school operations and student information</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                </div>
                <h5>Total Students</h5>
                <h2 class="text-primary">1,245</h2>
                <p class="text-muted text-sm mb-0">Across all grades</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <iconify-icon icon="solar:presentation-graph-bold-duotone" class="fs-32 text-info"></iconify-icon>
                </div>
                <h5>Teachers</h5>
                <h2 class="text-info">85</h2>
                <p class="text-muted text-sm mb-0">Highly qualified staff</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <iconify-icon icon="solar:buildings-bold-duotone" class="fs-32 text-success"></iconify-icon>
                </div>
                <h5>Classes</h5>
                <h2 class="text-success">42</h2>
                <p class="text-muted text-sm mb-0">From K to Grade 12</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <iconify-icon icon="solar:star-bold-duotone" class="fs-32 text-warning"></iconify-icon>
                </div>
                <h5>Awards</h5>
                <h2 class="text-warning">15</h2>
                <p class="text-muted text-sm mb-0">National & International</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Latest Announcements</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Annual Sports Day - 15th March</h6>
                            <p class="text-muted text-sm mb-0">Join us for our annual sports day celebration featuring various sports and activities for all students.</p>
                        </div>
                        <span class="badge bg-primary float-end mt-1">New</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Spring Holiday Vacation - 20-27 April</h6>
                            <p class="text-muted text-sm mb-0">School will be closed for spring vacation. Online classes will resume on 28th April, 2024.</p>
                        </div>
                        <span class="badge bg-info float-end mt-1">Holiday</span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Parent-Teacher Meeting - 5th March</h6>
                            <p class="text-muted text-sm mb-0">Scheduled meetings with parents to discuss student progress and development. Registration required.</p>
                        </div>
                        <span class="badge bg-success float-end mt-1">Meeting</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">School Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="text-muted mb-1"><strong>Established:</strong></p>
                    <p>1999</p>
                </div>
                <div class="mb-3">
                    <p class="text-muted mb-1"><strong>Mission:</strong></p>
                    <p>To provide quality education and nurture responsible global citizens through academic excellence and character development.</p>
                </div>
                <div class="mb-3">
                    <p class="text-muted mb-1"><strong>Address:</strong></p>
                    <p>123 Education Street, Learning City, ST 54321</p>
                </div>
                <div class="mb-3">
                    <p class="text-muted mb-1"><strong>Contact:</strong></p>
                    <p>(555) 123-4567 | info@digizaro.edu</p>
                </div>
                <div>
                    <p class="text-muted mb-1"><strong>Working Hours:</strong></p>
                    <p>Monday - Friday: 8:00 AM - 4:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection