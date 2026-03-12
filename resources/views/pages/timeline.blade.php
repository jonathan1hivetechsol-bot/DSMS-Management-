@extends('layouts.vertical', ['title' => 'School Timeline', 'subTitle' => 'Pages'])

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="timeline">

            <article class="timeline-time">
                <div class="time-show d-flex align-items-center justify-content-center mt-0">
                    <h5 class="mb-0 text-uppercase fs-14 fw-semibold">Current Term</h5>
                </div>
            </article>

            <article class="timeline-item timeline-item-left">
                <div class="timeline-desk">
                    <div class="timeline-box clearfix">
                        <span class="timeline-icon"></span>
                        <div class="overflow-hidden">
                            <div class="card d-inline-block">
                                <div class="card-body">
                                    <h5 class="mt-0 fs-16">Annual Sports Day Celebration<span class="badge bg-success ms-1 align-items-center">completed</span>
                                    </h5>
                                    <p class="text-muted mb-0">Students showcased their athletic talent in various sports and competitions. The event promoted school spirit and teamwork among all classes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="timeline-item">
                <div class="timeline-desk">
                    <div class="timeline-box clearfix">
                        <span class="timeline-icon"></span>
                        <div class="overflow-hidden">
                            <div class="card d-inline-block">
                                <div class="card-body">
                                    <h5 class="mt-0 fs-16">Science & Technology Fair
                                    </h5>
                                    <p class="text-muted mb-0">Students presented innovative projects and experiments. Great participation from all grades with projects ranging from robotics to environmental solutions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="timeline-item timeline-item-left">
                <div class="timeline-desk">
                    <div class="timeline-box clearfix">
                        <span class="timeline-icon"></span>
                        <div class="overflow-hidden">
                            <div class="card d-inline-block">
                                <div class="card-body">
                                    <h5 class="mt-0 fs-16">School Foundation Day Celebration</h5>
                                    <p class="text-muted mb-0">Commemorated the school's 25 years of educational excellence. Special assembly with cultural performances and recognition of achievements.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="timeline-time">
                <div class="time-show d-flex align-items-center mt-0">
                    <h5 class="mb-0 text-uppercase fs-14 fw-semibold">Previous Term</h5>
                </div>
            </article>

            <article class="timeline-item">
                <div class="timeline-desk">
                    <div class="timeline-box clearfix">
                        <span class="timeline-icon"></span>
                        <div class="overflow-hidden">
                            <div class="card d-inline-block">
                                <div class="card-body">
                                    <h5 class="mt-0 fs-16">Annual Examinations Completed</h5>
                                    <p class="text-muted mb-0">All students successfully completed their examinations. Results will be declared on schedule. Counseling sessions available for students needing improvement support.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <article class="timeline-item timeline-item-left">
                <div class="timeline-desk">
                    <div class="timeline-box clearfix">
                        <span class="timeline-icon"></span>
                        <div class="overflow-hidden">
                            <div class="card d-inline-block">
                                <div class="card-body">
                                    <h5 class="mt-0 fs-16">Parent-Teacher Meet Sessions Held
                                    </h5>
                                    <p class="text-muted mb-0">Productive meetings conducted between parents and teachers. Constructive feedback shared regarding student performance and behavioral aspects.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection