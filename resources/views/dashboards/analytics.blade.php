@extends('layouts.vertical', ['title' => 'School Dashboard','subTitle' => 'Overview'])

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:users-group-two-rounded-broken"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Total Students</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\Student::count() }} <span
                                    class="badge text-success bg-success-subtle fs-12"><i class="ri-arrow-up-line"></i>5.2%</span>
                            </h3>
                        </div>
                        <div class="col-6">
                            <div id="total_students" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:user-broken"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Total Teachers</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\Teacher::count() }} <span
                                    class="badge text-success bg-success-subtle fs-12"><i class="ri-arrow-up-line"></i>12.5%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_teachers" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-5">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:buildings-2-broken"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Total Classes</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\SchoolClass::count() }} <span
                                    class="badge text-success bg-success-subtle fs-12"><i class="ri-arrow-up-line"></i>10.0%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_classes" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-5">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:user-check-broken"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">System Users</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\User::count() }} <span
                                    class="badge text-success bg-success-subtle fs-12"><i class="ri-arrow-up-line"></i>15.3%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_users" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:wallet-3-line"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Unpaid Invoices</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\Invoice::whereNull('paid_at')->count() }} <span
                                    class="badge text-danger bg-danger-subtle fs-12"><i class="ri-arrow-down-line"></i>2.1%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_unpaid_invoices" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="ri-book-line"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Total Books</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\Book::count() }} <span
                                    class="badge text-success bg-success-subtle fs-12"><i class="ri-arrow-up-line"></i>4.0%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_books" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="ri-booklet-line"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Loans Out</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\Loan::whereNull('returned_at')->count() }} <span
                                    class="badge text-warning bg-warning-subtle fs-12"><i class="ri-arrow-down-line"></i>1.2%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_loans" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:book-bookmark-line"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Subjects</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\Subject::count() }} <span
                                    class="badge text-success bg-success-subtle fs-12"><i class="ri-arrow-up-line"></i>3.1%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_subjects" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:announcement-line"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Announcements</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\Announcement::count() }} <span
                                    class="badge text-success bg-success-subtle fs-12"><i class="ri-arrow-up-line"></i>0.8%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_announcements" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:calendar-event-line"
                                              class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <p class="text-muted mb-2 mt-3">Upcoming Exams</p>
                            <h3 class="text-dark fw-bold d-flex align-items-center gap-2 mb-0">{{ \App\Models\ExamSchedule::where('exam_date', '>=', today())->count() }} <span
                                    class="badge text-info bg-info-subtle fs-12"><i class="ri-arrow-up-line"></i>1.9%</span>
                            </h3>
                        </div>
                        <div class="col-6 text-end">
                            <div id="total_exams" class="apex-charts"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pb-1">
                    <div>
                        <h4 class="card-title">Student Enrollment Trend</h4>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light rounded"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            This Year
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#!" class="dropdown-item">This Month</a>
                            <a href="#!" class="dropdown-item">This Quarter</a>
                            <a href="#!" class="dropdown-item">This Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="apex-charts" id="enrollment_trend"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card bg-primary bg-gradient">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-xl-7 col-lg-6 col-md-6">
                            <h3 class="text-white fw-bold">{{ \App\Models\SchoolClass::count() > 0 ? round(\App\Models\Student::count() / \App\Models\SchoolClass::count()) : 0 }}</h3>
                            <p class="text-white-50">Avg. Students Per Class</p>
                            <div class="row mt-4">
                                <div class="col-lg-6 col-md-6 col-6">
                                    <div class="d-flex gap-2">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success bg-opacity-50 text-white rounded">
                                                <i class="ri-user-add-line fs-4"></i>
                                            </span>
                                        </div>
                                        <div class="d-block">
                                            <h5 class="text-white fw-medium mb-0">{{ \App\Models\Student::where('created_at', '>=', now()->startOfMonth())->count() }}</h5>
                                            <p class="mb-0 text-white-50">This Month</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-6">
                                    <div class="d-flex gap-2">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning bg-opacity-50 text-white rounded">
                                                <i class="ri-teacher-line fs-4"></i>
                                            </span>
                                        </div>
                                        <div class="d-block">
                                            <h5 class="text-white fw-medium mb-0">{{ \App\Models\Teacher::count() }}</h5>
                                            <p class="mb-0 text-white-50">Teachers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 g-2">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <a href="{{ route('students.index') }}" class="btn btn-warning w-100 btn-sm">View Students</a>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <a href="{{ route('teachers.index') }}" class="btn bg-light bg-opacity-25 text-white w-100 btn-sm">View Teachers</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-xl-6 col-md-6">
                            <div class="text-center p-3 border-end">
                                <h5 class="card-title mb-0 text-dark fw-medium">Classes</h5>
                                <div class="avatar-md bg-light bg-opacity-50 rounded mx-auto my-3">
                                    <iconify-icon icon="solar:book-bold-duotone"
                                                  class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                                <h4 class="text-dark fw-medium">{{ \App\Models\SchoolClass::count() }}</h4>
                                <p class="text-muted">Active Classes</p>
                                <div class="progress mt-3" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                         role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="text-center p-3">
                                <h5 class="card-title mb-0 text-dark fw-medium">Enrollment</h5>
                                <div class="avatar-md bg-light bg-opacity-50 rounded mx-auto my-3">
                                    <iconify-icon icon="solar:users-group-bold-duotone"
                                                  class="fs-32 text-success avatar-title"></iconify-icon>
                                </div>
                                <h4 class="text-dark fw-medium">{{ \App\Models\Student::count() }}</h4>
                                <p class="text-muted">Enrolled Students</p>
                                <div class="progress mt-3" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                         role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Distribution</h4>
                </div>
                <div class="card-body">
                    <div id="user_distribution" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Class Statistics</h4>
                </div>
                <div class="card-body">
                    <div id="class_stats" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Attendance Overview</h4>
                </div>
                <div class="card-body">
                    <div id="attendance_overview" class="apex-charts"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Classes Overview</h4>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Class Name</th>
                                <th>Teacher</th>
                                <th>Capacity</th>
                                <th>Enrolled</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $classes = \App\Models\SchoolClass::with('teacher.user', 'students')->get();
                            @endphp
                            @forelse($classes as $class)
                                @php
                                    $occupancy = $class->capacity > 0 ? round(($class->students->count() / $class->capacity) * 100) : 0;
                                @endphp
                                <tr>
                                    <td><span class="fw-medium">{{ $class->name }}</span></td>
                                    <td>{{ $class->teacher ? $class->teacher->user->name : 'Not Assigned' }}</td>
                                    <td>{{ $class->capacity }}</td>
                                    <td><span class="badge bg-primary">{{ $class->students->count() }}</span></td>
                                    <td>
                                        @if($occupancy > 90)
                                            <span class="badge bg-danger">Full</span>
                                        @elseif($occupancy > 70)
                                            <span class="badge bg-warning">Nearly Full</span>
                                        @else
                                            <span class="badge bg-success">Available</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No classes found. <a href="{{ route('classes.create') }}">Create one</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Student Mini Chart
            var studentChartOptions = {
                series: [{
                    data: [12, 14, 10, 15, 18, 16]
                }],
                chart: {
                    type: 'area',
                    height: 50,
                    sparkline: { enabled: true }
                },
                colors: ['#0084ff'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_students')) {
                new ApexCharts(document.getElementById('total_students'), studentChartOptions).render();
            }

            // Teacher Mini Chart
            var teacherChartOptions = {
                series: [{
                    data: [8, 9, 7, 10, 11, 9]
                }],
                chart: {
                    type: 'area',
                    height: 50,
                    sparkline: { enabled: true }
                },
                colors: ['#00d4ff'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_teachers')) {
                new ApexCharts(document.getElementById('total_teachers'), teacherChartOptions).render();
            }

            // Classes Mini Chart
            var classChartOptions = {
                series: [{
                    data: [4, 5, 4, 6, 7, 6]
                }],
                chart: {
                    type: 'area',
                    height: 50,
                    sparkline: { enabled: true }
                },
                colors: ['#00bfa5'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_classes')) {
                new ApexCharts(document.getElementById('total_classes'), classChartOptions).render();
            }

            // Users Mini Chart
            var userChartOptions = {
                series: [{
                    data: [25, 28, 26, 32, 35, 33]
                }],
                chart: {
                    type: 'area',
                    height: 50,
                    sparkline: { enabled: true }
                },
                colors: ['#ffc107'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_users')) {
                new ApexCharts(document.getElementById('total_users'), userChartOptions).render();
            }

            // Unpaid Invoices Mini Chart
            var unpaidChartOptions = {
                series: [{ data: [3, 4, 3, 5, 2, 6] }],
                chart: { type: 'area', height: 50, sparkline: { enabled: true } },
                colors: ['#e91e63'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_unpaid_invoices')) {
                new ApexCharts(document.getElementById('total_unpaid_invoices'), unpaidChartOptions).render();
            }

            // Subjects Mini Chart
            var subjectChartOptions = {
                series: [{ data: [5, 6, 7, 8, 9, 10] }],
                chart: { type: 'area', height: 50, sparkline: { enabled: true } },
                colors: ['#673ab7'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_subjects')) {
                new ApexCharts(document.getElementById('total_subjects'), subjectChartOptions).render();
            }

            // Announcements Mini Chart
            var announcementChartOptions = {
                series: [{ data: [1, 2, 1, 3, 2, 4] }],
                chart: { type: 'area', height: 50, sparkline: { enabled: true } },
                colors: ['#ff5722'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_announcements')) {
                new ApexCharts(document.getElementById('total_announcements'), announcementChartOptions).render();
            }

            // Exams Mini Chart
            var examChartOptions = {
                series: [{ data: [2, 3, 4, 3, 5, 4] }],
                chart: { type: 'area', height: 50, sparkline: { enabled: true } },
                colors: ['#00bfa5'],
                stroke: { curve: 'smooth', width: 1 },
                fill: { opacity: 0.3 }
            };
            if (document.getElementById('total_exams')) {
                new ApexCharts(document.getElementById('total_exams'), examChartOptions).render();
            }

            // Enrollment Trend Chart
            var enrollmentOptions = {
                series: [{
                    name: 'Total Enrollment',
                    data: [30, 40, 35, 50, 60, 75, 85]
                }],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: { show: false }
                },
                colors: ['#0084ff'],
                stroke: { curve: 'smooth', width: 2 },
                markers: {
                    size: 4,
                    colors: ['#0084ff'],
                    strokeColor: '#fff',
                    strokeWidth: 2,
                    hover: { size: 6 }
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    title: { text: 'Months' }
                },
                yaxis: {
                    title: { text: 'Number of Students' }
                },
                grid: {
                    show: true,
                    borderColor: '#e0e0e0'
                }
            };
            if (document.getElementById('enrollment_trend')) {
                new ApexCharts(document.getElementById('enrollment_trend'), enrollmentOptions).render();
            }

            // User Distribution Pie Chart
            var userDistributionOptions = {
                series: [{{ \App\Models\User::where('role', 'student')->count() }}, {{ \App\Models\User::where('role', 'teacher')->count() }}, {{ \App\Models\User::where('role', 'admin')->count() }}],
                chart: {
                    type: 'donut',
                    height: 280
                },
                labels: ['Students', 'Teachers', 'Admin'],
                colors: ['#0084ff', '#00bfa5', '#ffc107'],
                legend: { position: 'bottom' },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) { return parseInt(val) + '%' }
                        }
                    }
                }
            };
            if (document.getElementById('user_distribution')) {
                new ApexCharts(document.getElementById('user_distribution'), userDistributionOptions).render();
            }

            // Class Statistics Bar Chart
            var classStatsOptions = {
                series: [{
                    name: 'Students Enrolled',
                    data: [65, 58, 72, 48, 55]
                }],
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: { show: false }
                },
                colors: ['#0084ff'],
                xaxis: {
                    categories: ['Class A', 'Class B', 'Class C', 'Class D', 'Class E']
                },
                grid: {
                    show: true,
                    borderColor: '#e0e0e0'
                }
            };
            if (document.getElementById('class_stats')) {
                new ApexCharts(document.getElementById('class_stats'), classStatsOptions).render();
            }

            // Attendance Overview Pie
            var attendanceOverviewOptions = {
                series: [
                    {{ \App\Models\Attendance::where('status', 'present')->count() }},
                    {{ \App\Models\Attendance::where('status', 'absent')->count() }}
                ],
                chart: {
                    type: 'pie',
                    height: 280
                },
                labels: ['Present', 'Absent'],
                colors: ['#00bfa5', '#ff5252'],
                legend: { position: 'bottom' }
            };
            if (document.getElementById('attendance_overview')) {
                new ApexCharts(document.getElementById('attendance_overview'), attendanceOverviewOptions).render();
            }
        });
    </script>
@endsection
