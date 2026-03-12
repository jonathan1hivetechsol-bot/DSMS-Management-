@extends('layouts.vertical', ['title' => 'FAQs', 'subTitle' => 'Pages'])

@section('content')

<div class="row g-xl-4">
    <div class="col-xl-6">

        <h4 class="mb-3 fw-semibold fs-16">Admissions</h4>
        <div class="accordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                        What is the admission process for new students?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faq1">
                    <div class="accordion-body">
                        New students need to fill out an admission form during the registration period. Please visit our admissions office or contact us for detailed information about the admission requirements and timeline.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                        What documents are required for admission?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2">
                    <div class="accordion-body">
                        Students need to provide birth certificate, previous school records, health examination report, and proof of immunization. All documents should be original or attested copies.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                        What is the minimum age for admission to kindergarten?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        Students should be at least 3 years old for admission to kindergarten. Please check the specific age requirements for each class level.
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3 mt-4 fw-semibold fs-16">Fees & Payments</h4>
        <!-- FAQs -->
        <div class="accordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="true" aria-controls="faq4">
                        When are school fees due?
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse show" aria-labelledby="faq4">
                    <div class="accordion-body">
                        School fees are due at the beginning of each term. Payment plans are available for parents who need them. Please contact the accounts office for more details.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                        What payment methods are accepted?
                    </button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse" aria-labelledby="faq5">
                    <div class="accordion-body">
                        We accept bank transfers, online payments, and cash payments at the office. All transactions are recorded and a receipt will be issued.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                        Is there a scholarship available?
                    </button>
                </h2>
                <div id="faq6" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        Yes, scholarships are available for deserving students based on academic performance and financial need. Please contact the admissions office for application details.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-xl-6">

        <h4 class="mb-3 fw-semibold fs-16">Academics</h4>
        <div class="accordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="true" aria-controls="faq7">
                        What is the academic calendar for this year?
                    </button>
                </h2>
                <div id="faq7" class="accordion-collapse collapse show" aria-labelledby="faq7">
                    <div class="accordion-body">
                        The school year runs from January to December with three terms. Term dates and holidays are published in the school calendar which is available from the office or online.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                        How can parents track student progress?
                    </button>
                </h2>
                <div id="faq8" class="accordion-collapse collapse" aria-labelledby="faq8">
                    <div class="accordion-body">
                        Parents can access the student portal using their login credentials to view grades, attendance, and teachers' comments. Progress reports are issued at the end of each term.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="false" aria-controls="faq9">
                        What is the homework policy?
                    </button>
                </h2>
                <div id="faq9" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        Homework is assigned regularly to reinforce classroom learning. The amount varies by class level. Parents are encouraged to support their children's learning at home.
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3 mt-4 fw-semibold fs-16">General Information</h4>
        <div class="accordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq10" aria-expanded="true" aria-controls="faq10">
                        What are the school working hours?
                    </button>
                </h2>
                <div id="faq10" class="accordion-collapse collapse show" aria-labelledby="faq10">
                    <div class="accordion-body">
                        School starts at 8:00 AM and ends at 3:00 PM. After-school activities and clubs run until 4:30 PM. Extended care is available.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq11" aria-expanded="false" aria-controls="faq11">
                        What is the school's absence policy?
                    </button>
                </h2>
                <div id="faq11" class="accordion-collapse collapse" aria-labelledby="faq11">
                    <div class="accordion-body">
                        Parents must notify the school of any absence by 8:00 AM. A medical certificate is required for absences exceeding two consecutive days.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12" aria-expanded="false" aria-controls="faq12">
                        Does the school provide transportation?
                    </button>
                </h2>
                <div id="faq12" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        Yes, school-operated buses are available for designated routes. Transportation fees apply separately and are included in school services.
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row my-5">
    <div class="col-12 text-center">
        <h4>Have more questions?</h4>
        <button type="button" class="btn btn-success mt-2"><i class="ri-mail-line me-1"></i> Contact Admissions</button>
        <button type="button" class="btn btn-info mt-2 ms-1"><i class="ri-phone-line me-1"></i> Call the Office</button>
    </div>
</div>

@endsection