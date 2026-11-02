@extends('layouts.public') 

@section('title', 'EAC Scholarship Programme – Cohort 4 | Jaramogi Oginga Odinga University of Science and Technology')

@section('content')
<div class="container py-5">
    {{-- Logos --}}
        <div class="row g-3 align-items-center justify-content-center">
            <img src="{{ asset('assets/img/JOOUSTLOGO1.png') }}" 
                alt="jooust logo" 
                class="img-fluid rounded shadow-sm"
                style="max-height: 100px; width: 100px; object-fit: contain;">
            <img src="{{ asset('assets/img/jumuiya.jpg') }}" 
                alt="jooust logo" 
                class="img-fluid rounded shadow-sm"
                style="max-height: 100px; width: 100px; object-fit: contain;">
            <img src="{{ asset('assets/img/interuni.jpg') }}" 
                alt="jooust logo" 
                class="img-fluid rounded shadow-sm"
                style="max-height: 100px; width: 100px; object-fit: contain;">
            <img src="{{ asset('assets/img/germancop.jpg') }}" 
                alt="jooust logo" 
                class="img-fluid rounded shadow-sm"
                style="max-height: 100px; width: 100px; object-fit: contain;">
            <img src="{{ asset('assets/img/kfw.jpg') }}" 
                alt="jooust logo" 
                class="img-fluid rounded shadow-sm"
                style="max-height: 100px; width: 100px; object-fit: contain;">
        </div>
        <div class="section-divider1"></div>

    {{-- Header / Title --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary mb-2">EAC SCHOLARSHIP PROGRAMME</h1>
        <h4 class="text-danger fw-semibold">
            CALL FOR APPLICATIONS FOR MASTER’S DEGREE PROGRAMMES<br>
            UNDER PHASE II OF THE EAST AFRICAN COMMUNITY (EAC) SCHOLARSHIP PROGRAMME
        </h4>
    </div>

    {{-- Intro + Key Info Table --}}
    <div class="row g-4 align-items-start mb-5">
        <div class="col-lg-7">
            <p class="lead">
                The Jaramogi Oginga Odinga University of Science and Technology (JOOUST) invites suitably qualified nationals of the East African Community (EAC) 
                to apply for <strong>cohort 4</strong> of the KfW-funded Scholarships under the Inter-University Council for East Africa (IUCEA) 
                to pursue Master’s studies. The applicants must be citizens of an EAC Partner State <strong>other than Kenya</strong>. 
                In addition to applying for admission to JOOUST Master’s programmes, applicants are required to apply for a scholarship through IUCEA.
            </p>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width:40%">Opening date</th>
                                <td>20<sup>th</sup> August 2026</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Closing date</th>
                                <td>10<sup>th</sup> September 2026, 17:00 hours, East African Time</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Thematic areas</th>
                                <td>Science</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Eligible applicants</th>
                                <td>
                                    EAC Nationals (aged below 35 years) from Burundi, Democratic Republic of Congo, 
                                    Tanzania, Rwanda, Somalia, South Sudan and Uganda
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Duration</th>
                                <td>Two (2) years</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 1. Background --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">1. Background</h3>
        <p>
            The EAC Scholarship Programme (EACSP) is a regional initiative of the East African Community (EAC) and the Federal Republic of Germany. 
            Implemented by the Inter-University Council for East Africa, the Programme seeks to develop future leaders who will contribute to 
            regional integration and sustainable development by supporting students to pursue Master’s studies in an EAC Partner State other than 
            their country of citizenship.
        </p>
        <p>
            The Programme was launched in 2018 and has already supported 180 Master’s students from across the East African Community through its first Phase. 
            Building on the success of Phase I, Phase II commenced in October 2025 and is expected to support at least 280 students across three cohorts 
            from all current EAC partner states: Burundi, the Democratic Republic of Congo, Kenya, Rwanda, Somalia, South Sudan, Tanzania, and Uganda.
        </p>
        <p>
            The Programme provides scholarships to academically talented but financially disadvantaged students pursuing eligible Master’s programmes 
            in Mathematics, Engineering, Informatics, Science, Technology and Business-related disciplines. In addition to financial support, 
            scholarship recipients benefit from leadership development, mentoring, networking opportunities and regional integration activities.
        </p>
        <p class="fw-semibold fst-italic">Vision of the Programme: “Leadership to Foster Regional Integration.”</p>
    </section>

    {{-- 2. Scholarship Package --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">2. Scholarship Package</h3>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">a) One return economy-class air ticket between the scholar’s home country and the host country upon commencement and completion of studies</li>
            <li class="list-group-item">b) Full tuition fees payable directly to the host university</li>
            <li class="list-group-item">c) Arrival allowance of EUR 200 upon arrival in the study country and opening a bank account</li>
            <li class="list-group-item">d) A monthly stipend of EUR 380 to cover accommodation, meals, local transport and other living expenses</li>
            <li class="list-group-item">e) A lump-sum allowance of EUR 650 for study materials</li>
            <li class="list-group-item">f) A one-time EUR 1,500 study and research allowance</li>
            <li class="list-group-item">g) Medical insurance coverage for the duration of the scholarship</li>
            <li class="list-group-item">h) Visa/Entry fee (where required)</li>
            <li class="list-group-item">i) Participation in at least one extracurricular event</li>
        </ul>
    </section>

    {{-- 3. Eligible Master's Programmes --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">3. Eligible Master’s Programmes</h3>
        <p>Programmes offered at the Jaramogi Oginga Odinga University of Science and Technology under Cohort 4:</p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th style="width:8%">S/N</th>
                        <th>Master’s Programme</th>
                        <th style="width:20%">Thematic Area</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Master of Science in Animal Nutrition</td>
                        <td>Science</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Master of Science in Food Science</td>
                        <td>Science</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Master of Science in Agricultural Extension</td>
                        <td>Science</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Master of Science in Chemistry</td>
                        <td>Science</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Master of Science in Microbiology</td>
                        <td>Science</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Master of Science in Pure Mathematics</td>
                        <td>Science</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="mt-3">
            Applicants may apply for more than one programme. <strong>Female applicants, persons with disabilities and applicants from other disadvantaged backgrounds</strong> 
            are strongly encouraged to apply. The Programme aims to achieve at least 50% female participation among scholarship beneficiaries.
        </p>
    </section>

    {{-- 4. Admission Criteria --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">4. Admission Criteria for the Programmes</h3>
        <p>An applicant seeking admission to a Master’s degree by coursework and dissertation at the Jaramogi Oginga Odinga University of Science and Technology under the EAC Scholarship must meet the following requirements:</p>
        <ul>
            <li>Hold a Bachelor’s degree with a minimum GPA of 2.7; <strong>OR</strong> a Postgraduate Diploma in a relevant discipline with a minimum GPA of 3.0 or a B grade from an accredited Higher Learning Institution; and</li>
            <li>Be under 35 years of age at the time of application.</li>
        </ul>

        <h5 class="mt-4">Specific Programme Requirements</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width:35%">Programme</th>
                        <th>Admission Requirements</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Master of Science in Biodiversity Conservation and Management</td>
                        <td>First Class Honors OR second Class Honors Upper Division degree from 
                            accredited University OR Second Class Honors, Lower division with atleast 
                            two years of work/research exprience in a relevant field after graduation.
                        </td>
                    </tr>
                    <tr>
                        <td>Master of Science in Food Science</td>
                        <td>First Class Honors OR second Class Honors Upper Division degree from 
                            accredited University OR Second Class Honors, Lower division with atleast 
                            two years of work/research exprience in a relevant field after graduation.
                        </td>
                    </tr>
                    <tr>
                        <td>Master of Science in Animal Nutrition</td>
                        <td>First Class Honors OR second Class Honors Upper Division degree from 
                            accredited University OR Second Class Honors, Lower division with atleast 
                            two years of work/research exprience in a relevant field after graduation.
                        </td>
                    </tr>
                    <tr>
                        <td>Master of Science in Chemistry</td>
                        <td>First Class Honors OR second Class Honors Upper Division degree from 
                            accredited University OR Second Class Honors, Lower division with atleast 
                            two years of work/research exprience in a relevant field after graduation.
                        </td>
                    </tr>
                    <tr>
                        <td>Master of Science in Microbiology</td>
                        <td>First Class Honors OR second Class Honors Upper Division degree from 
                            accredited University OR Second Class Honors, Lower division with atleast 
                            two years of work/research exprience in a relevant field after graduation.
                        </td>
                    </tr>
                    <tr>
                        <td>Master of Science in Pure Mathematics</td>
                        <td>First Class Honors OR second Class Honors Upper Division degree from 
                            accredited University OR Second Class Honors, Lower division with atleast 
                            two years of work/research exprience in a relevant field after graduation.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- 5. Eligibility for the Scholarship --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">5. Eligibility to Benefit from the EAC Scholarship Programme</h3>
        <p>The application for the EAC Scholarship should be submitted directly to IUCEA. Applicants must meet all of the following:</p>
        <ol type="a">
            <li>Be a citizen of any of the eight (8) EAC Partner States;</li>
            <li>Be below 35 years of age at the time of application;</li>
            <li>Apply to study in an EAC Partner State other than their country of citizenship;</li>
            <li>Possess a Bachelor’s Degree with a minimum of Second Class Upper Division, Distinction or equivalent;</li>
            <li>Have applied for admission to an eligible Master’s programme offered by The Jaramogi Oginga Odinga University of Science and Technology;</li>
            <li>Submit a complete scholarship application within the stipulated application period.</li>
        </ol>

        <h5 class="mt-4">Mandatory Supporting Documents</h5>
        <ol type="i">
            <li>National ID or Passport</li>
            <li>Passport Photograph (recent colour photo of full head and upper shoulders)</li>
            <li>Proof of University admission application (admission letter, acknowledgement email, receipt, portal screenshot, etc.)</li>
            <li>Bachelor’s Degree Certificate and Transcript</li>
            <li>Equating of foreign qualifications (if obtained outside the host country)</li>
            <li>Certified English translations of documents not in English</li>
            <li>Certified true copies of Bachelor’s degree certificate and academic transcript</li>
            <li>Curriculum Vitae (CV)</li>
            <li>Bank Statements (last six months), where applicable</li>
            <li>Academic Reference Letter</li>
            <li>Professional Reference Letter</li>
        </ol>
    </section>

    {{-- 6. Application Process --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">6. Application Process</h3>
        <ol type="a">
            <li>
                Applicants should apply through the JOOUST pOSTGRADUATE Application System  at 
                <a href="{{ route('apply') }}" target="_blank" class="fw-semibold">
                    https://postgraduate.jooust.ac.ke/apply
                </a>
            </li>
            <li>
                Applicants shall pay a non-refundable application fee of <strong>KSH 3,000</strong> through the payment instructions 
                provided. Appropriate payment options for applicants outside Kenya 
                shall be provided through the application portal.
            </li>
            <li>
                Applicants will be notified of their admission status via email by <strong>25<sup>th</sup> September 2026</strong>.
            </li>
        </ol>
    </section>

    {{-- 7. Supporting Documents for Admission --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">7. Supporting Documents for Admission at JOOUST</h3>
        <p>Along with the application, each applicant should submit the following supporting documents in English:</p>
        <ul>
            <li>Academic certificates and transcripts</li>
            <li>Evidence of Nationality of a Partner State in the East African Community (passport or national identity card)</li>
           
            <li>Evidence of application fee payment</li>
        </ul>
    </section>

    {{-- 8. Further Enquiries --}}
    <section class="mb-5">
        <h3 class="fw-bold border-bottom pb-2 mb-3">8. Further Enquiries</h3>
        <p>For further enquiries regarding admission, applicants may contact the Directorate of Postgraduate Studies at the Jaramogi Oginga Odinga University of Science and Technology:</p>
        
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card h-100 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold">Phone</h6>
                        <p class="mb-1">+255 713 797 149</p>
                        <p class="mb-0">+255 686 547 383</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-0 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold">Email & Website</h6>
                        <p class="mb-1">
                            <a href="mailto:bps@jooust.ac.ke">bps@jooust.ac.ke</a>
                        </p>
                        <p class="mb-0">
                            <a href="https://www.jooust.ac.ke" target="_blank">www.jooust.ac.ke</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer note --}}
    <div class="text-center text-muted small mt-5 pt-4 border-top">
        <p class="mb-1 fw-semibold">ISSUED BY</p>
        <p class="mb-1">OFFICE OF THE DEPUTY VICE CHANCELLOR<br>ACADEMIC, RESEARCH AND CONSULTANCY</p>
        <p class="mb-0">THE Jaramogi Oginga Odinga University of Science and Technology<br>19<sup>TH</sup> AUGUST, 2026</p>
    </div>

</div>
@endsection