@extends('layouts.vertical', ['title' => 'Fee Receipt', 'subTitle' => 'Pages'])

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Logo & title -->
                <div class="clearfix">
                    <div class="float-sm-end">
                        <div class="auth-logo">
                            <img class="logo-dark me-1" src="/images/logo-dark.png" alt="logo-dark" height="24" />
                            <img class="logo-light me-1" src="/images/logo-light.png" alt="logo-dark" height="24" />
                        </div>
                        <address class="mt-3">
                            Digizaro School,<br>
                            123 Education Street,<br>
                            Learning City, ST 54321 <br>
                            <abbr title="Phone">P:</abbr> (555) 123-4567
                        </address>
                    </div>
                    <div class="float-sm-start">
                        <h5 class="card-title mb-2">Fee Receipt: #FEE-2024-001</h5>
                        <p>15 January, 2024</p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="fw-normal text-muted">Student Information</h6>
                        <h6 class="fs-16">Ahmed Hassan Khan</h6>
                        <address>
                            Roll No: STU-2024-015<br>
                            Class: Grade 10-A<br>
                            Parent Contact: (555) 987-6543
                        </address>
                    </div> <!-- end col -->
                    <div class="col-md-6">
                        <h6 class="fw-normal text-muted">Academic Details</h6>
                        <p><strong>Term:</strong> Spring 2024</p>
                        <p><strong>Year:</strong> 2023-2024</p>
                        <p><strong>Payment Status:</strong> <span class="badge bg-success">Paid</span></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive table-borderless text-nowrap mt-3 table-centered">
                            <table class="table mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="border-0 py-2">Fee Description</th>
                                        <th class="border-0 py-2">Rate</th>
                                        <th class="border-0 py-2">Quantity</th>
                                        <th class="text-end border-0 py-2">Amount</th>
                                    </tr>
                                </thead> <!-- end thead -->
                                <tbody>
                                    <tr>
                                        <td>Tuition Fee</td>
                                        <td>$500.00</td>
                                        <td>1</td>
                                        <td class="text-end">$500.00</td>
                                    </tr>
                                    <tr>
                                        <td>Science Lab & Sports Fee</td>
                                        <td>$75.00</td>
                                        <td>1</td>
                                        <td class="text-end">$75.00</td>
                                    </tr>
                                    <tr>
                                        <td>Technology & Computer Lab</td>
                                        <td>$60.00</td>
                                        <td>1</td>
                                        <td class="text-end">$60.00</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td>Examination & Annual Fees</td>
                                        <td>$85.00</td>
                                        <td>1</td>
                                        <td class="text-end">$85.00</td>
                                    </tr>
                                </tbody> <!-- end tbody -->
                            </table> <!-- end table -->
                        </div> <!-- end table responsive -->
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="row mt-3">
                    <div class="col-sm-7">
                        <div class="clearfix pt-xl-3 pt-0">
                            <h6 class="text-muted">Payment Terms:</h6>

                            <small class="text-muted">
                                This fee receipt is valid for the current academic year (2023-2024).
                                Fees are due at the beginning of each term. Late payment may incur
                                a penalty of 2% per month. For payment plans or scholarship queries,
                                please contact the Finance Office. Refunds are issued only in case
                                of withdrawal before the completion of the academic term.
                            </small>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="float-end">
                            <p><span class="fw-medium">Sub-total :</span>
                                <span class="float-end">$720.00</span>
                            </p>
                            <p><span class="fw-medium">Scholarship Discount (5%) :</span>
                                <span class="float-end"> &nbsp;&nbsp;&nbsp; -$36.00</span>
                            </p>
                            <h3>$684.00 USD</h3>
                            <p class="small text-success mt-2">✓ Paid on 15-Jan-2024</p>
                        </div>
                        <div class="clearfix"></div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="mt-5 mb-1">
                    <div class="text-center d-print-none">
                        <a href="javascript:window.print()" class="btn btn-danger width-md">Print</a>
                        <a href="javascript:void(0);" class="btn btn-outline-primary width-md">Send</a>
                    </div>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection