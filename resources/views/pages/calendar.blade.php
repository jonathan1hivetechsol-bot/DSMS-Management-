@extends('layouts.vertical', ['title' => 'Academic Calendar', 'subTitle' => 'Pages'])

@section('css')
@vite(['node_modules/fullcalendar/main.min.css'])
@endsection

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" id="btn-new-event">
                                <i class="ri-add-line fs-18 me-2"></i> Add School Event
                            </button>
                        </div>
                        <div id="external-events">
                            <br>
                            <p class="text-muted">School Events & Important Dates</p>
                            <div class="external-event bg-soft-primary text-primary" data-class="bg-primary">
                                <i class="ri-circle-fill me-2 vertical-middle"></i>Examination Period
                            </div>
                            <div class="external-event bg-soft-info text-info" data-class="bg-info">
                                <i class="ri-circle-fill me-2 vertical-middle"></i>Parent-Teacher Meeting
                            </div>
                            <div class="external-event bg-soft-success text-success" data-class="bg-success">
                                <i class="ri-circle-fill me-2 vertical-middle"></i>School Holiday
                            </div>
                            <div class="external-event bg-soft-danger text-danger" data-class="bg-danger">
                                <i class="ri-circle-fill me-2 vertical-middle"></i>Sports Day
                            </div>
                            <div class="external-event bg-soft-warning text-warning" data-class="bg-warning">
                                <i class="ri-circle-fill me-2 vertical-middle"></i>Annual Function
                            </div>
                        </div>
                    </div> <!-- end col-->

                    <div class="col-xl-9">
                        <div class="mt-4 mt-lg-0">
                            <div id="calendar"></div>
                        </div>
                    </div> <!-- end col -->

                </div> <!-- end row -->
            </div> <!-- end card body-->
        </div> <!-- end card -->

        <!-- Add New Event MODAL -->
        <div class="modal fade" id="event-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="needs-validation" name="event-form" id="forms-event" novalidate>
                        <div class="modal-header p-3 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">School Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-3 pb-3 pt-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="control-label form-label">Event Name</label>
                                        <input class="form-control" placeholder="e.g., Midterm Examinations" type="text" name="title" id="event-title" required />
                                        <div class="invalid-feedback">Please provide a valid event name</div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="control-label form-label">Event Type</label>
                                        <select class="form-select" name="category" id="event-category" required>
                                            <option value="bg-primary">Examination</option>
                                            <option value="bg-success">Holiday</option>
                                            <option value="bg-info">Meeting</option>
                                            <option value="bg-warning">Activity</option>
                                            <option value="bg-danger">Important Date</option>
                                            <option value="bg-dark">Other</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a valid event type</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger" id="btn-delete-event">Delete</button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="btn-save-event">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div> <!-- end modal-content-->
            </div> <!-- end modal dialog-->
        </div> <!-- end modal-->
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script-bottom')
@vite(['resources/js/pages/app-calendar.js'])
@endsection