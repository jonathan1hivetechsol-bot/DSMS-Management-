@extends('layouts.vertical', ['title' => 'Fee Structure', 'subTitle' => 'Pages'])

@section('content')

<div class="row justify-content-center">
    <div class="col-xxl-11">
        <div class="text-center my-4">
            <h3>School Fee Structure 2024-2025</h3>
            <p class="text-muted text-center">
                Annual fee breakdown for all classes. Fees are inclusive of tuition, facilities, and activities. Special discounts available for siblings.
            </p>
        </div>
        <div class="row justify-content-center pt-3">
            <div class="col-lg-3">
                <div class="card card-pricing">
                    <div class="card-body rounded-top text-center bg-gradient bg-primary">
                        <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold text-white">Grades K-2</h5>
                        <h2 class="mt-0 mb-0 fw-bold text-white">$450 <span class="fs-14 fw-medium text-white text-opacity-50">/ Year</span></h2>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="card-pricing-features text-muted ps-0 list-unstyled">
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Tuition Fee</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Play-based Learning</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Sports Activities</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Art & Craft Classes</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Annual Excursion</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>School Events</li>
                        </ul>

                        <div class="mt-4 text-center">
                            <button class="btn btn-primary px-sm-4 w-100">Enroll Now</button>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
            <div class="col-lg-3">
                <div class="card card-pricing">
                    <div class="card-body rounded-top text-center bg-gradient bg-primary">
                        <div class="pricing-ribbon pricing-ribbon-primary float-end">Popular</div>
                        <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold text-white">Grades 3-5</h5>
                        <h2 class="mt-0 mb-0 fw-bold text-white">$550 <span class="fs-14 fw-medium text-white text-opacity-50">/ Year</span></h2>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="card-pricing-features text-muted ps-0 list-unstyled">
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Tuition Fee</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Science Lab Access</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Sports Programs</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Computing Classes</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Field Trips</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Co-curricular</li>
                        </ul>

                        <div class="mt-4 text-center">
                            <button class="btn btn-primary px-sm-4 disabled w-100">Current Plan</button>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
            <div class="col-lg-3">
                <div class="card card-pricing">
                    <div class="card-body rounded-top text-center bg-gradient bg-primary">
                        <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold text-white">Grades 6-8</h5>
                        <h2 class="mt-0 mb-0 fw-bold text-white">$650 <span class="fs-14 fw-medium text-white text-opacity-50">/ Year</span></h2>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="card-pricing-features text-muted ps-0 list-unstyled">
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Tuition Fee</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Advanced Science Lab</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Sports & Fitness</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Computer Lab</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Interest Classes</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Study Materials</li>
                        </ul>

                        <div class="mt-4 text-center">
                            <button class="btn btn-primary px-sm-4 w-100">Enroll Now</button>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
            <div class="col-lg-3">
                <div class="card card-pricing">
                    <div class="card-body rounded-top text-center bg-gradient bg-primary">
                        <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold text-white">Grades 9-12</h5>
                        <h2 class="mt-0 mb-0 fw-bold text-white">$800 <span class="fs-14 fw-medium text-white text-opacity-50">/ Year</span></h2>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="card-pricing-features text-muted ps-0 list-unstyled">
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Tuition Fee</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Advanced Labs</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Sports Excellence</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Computer Science</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Exam Preparation</li>
                            <li class="text-dark"><iconify-icon icon="solar:check-circle-bold-duotone" class="text-primary align-middle fs-20 me-2"></iconify-icon>Career Guidance</li>
                        </ul>

                        <div class="mt-4 text-center">
                            <button class="btn btn-primary px-sm-4 w-100">Enroll Now</button>
                        </div>
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection