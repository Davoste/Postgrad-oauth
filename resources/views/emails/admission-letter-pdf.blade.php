<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admission Letter - {{ brand('short_name') }}</title>
<style>
    @page {
        margin: 18mm 20mm;
        size: A4;
    }

    body {
        font-family: "Times New Roman", Times, serif;
        font-size: 13.5px;
        line-height: 1.55;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .logo-container {
        text-align: center;
        margin-bottom: 8px;
    }

    .logo-container img {
        width: 95px;
        height: auto;
    }

    .university-name {
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
        line-height: 1.3;
    }

    .office {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
        margin: 4px 0 12px 0;
    }

    .header-line {
        border-top: 1.5px solid #000;
        margin: 8px 0 14px 0;
    }

    .contact-table {
        width: 100%;
        font-size: 12.5px;
        margin-bottom: 18px;
    }

    .contact-table td {
        vertical-align: top;
        padding: 1px 0;
    }

    .contact-left {
        width: 55%;
    }

    .contact-right {
        width: 45%;
        text-align: right;
    }

    .ref-block {
        margin-bottom: 16px;
        font-size: 13px;
    }

    .ref-block p {
        margin: 2px 0;
    }

    .greeting {
        margin: 18px 0 12px 0;
    }

    .subject {
        font-weight: bold;
        text-decoration: underline;
        text-transform: uppercase;
        margin: 16px 0 14px 0;
        font-size: 13.5px;
    }

    .body-text {
        text-align: justify;
        margin-bottom: 12px;
    }

    .body-text p {
        margin: 0 0 11px 0;
    }

    .highlight {
        font-weight: bold;
    }

    .signature-block {
        margin-top: 45px;
    }

    .signature-name {
        margin-top: 28px;
        font-weight: bold;
    }

    .encls {
        margin-top: 8px;
        font-size: 12.5px;
    }

    /* small helper for tight spacing */
    .tight p {
        margin: 1px 0;
    }
</style>
</head>
<body>

    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ brand('logo_disk_path') }}" alt="{{ brand('short_name') }} Logo">
    </div>

    <!-- University Header -->
    <div class="university-name">
        {{ strtoupper(brand('name')) }}
    </div>
    <div class="office">
        OFFICE OF THE REGISTRAR (ACADEMIC AFFAIRS)
    </div>

    <div class="header-line"></div>

    <!-- Contact Details -->
    <table class="contact-table">
        <tr>
            <td class="contact-left">
                <div class="tight">
                    <p>Tel: {{ brand('support_phone') }}</p>
                    <p>Email: {{ brand('support_email') }}</p>
                    <p>Website: {{ brand('website') }}</p>
                </div>
            </td>
            <td class="contact-right">
                <div class="tight">
                    <p>{{ brand('address') }}</p>
                    <p>Date: {{ now()->format('j F, Y') }}</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- Reference + Student Details -->
    <div class="ref-block tight">
        <p><strong>REG. NO. {{ $application->ref_no ?? 'ED/G/11070/24' }}</strong></p>
        <p>{{ strtoupper($application->first_name.' '.$application->middle_name.' '.$application->last_name) }}</p>
        <p>{{ $application->postal_address ?? 'P.O. Box 304' }}</p>
        <p>Tel. {{ $application->phone ?? '0115115562' }}</p>
    </div>

    <!-- Greeting -->
    <div class="greeting">
        Dear {{ strtoupper($application->first_name) }},
    </div>

    <!-- Subject -->
    <div class="subject">
        RE: ADMISSION INTO POSTGRADUATE STUDIES AS A PORTAL-PLACED STUDENT 
        {{ date('Y') }}/{{ date('Y')+1 }} PLACEMENT - {{ $application->study_mode ?? 'Full Time' }}
    </div>

    <!-- Body -->
    <div class="body-text">
        <p>
            Following your placement for University Admission, I am pleased to offer you a place at 
            {{ brand('name') }} in the 
            <strong>{{ $programme->department->name ?? 'School of Education, Humanities & Social Sciences' }}</strong> 
            for a course leading to the <strong>{{ strtoupper($programme->name ?? 'BACHELOR OF EDUCATION (ARTS) WITH IT') }}</strong>.
        </p>

        <p>
            This offer is made on the basis of your qualifications and is subject to verification by the University authorities. 
            When reporting you should provide originals and copies of the following: Academic certificates, Transcripts, Admission fee 
            receipt, Medical Examination Form, National Identity Card, and two coloured passport size photographs.  
            Learning shall be conducted via blended mode of learning (Virtual and Physical where appropriate). 
            All students are required to come along with <strong>a good laptop</strong> as per the recommended specifications. 
           
        </p>

        <p>
            All fees should be paid directly to <strong>Equity Bank, Account Number 0750294239156, Bondo Branch</strong> or 
            <strong>Cooperative Bank, Account No.01129255709500, Bondo Branch</strong> or 
            <strong>KCB Bank, Account No. 1117122409, Bondo Branch</strong> or any other branches of the banks countrywide. 
            You will be required to submit original bank slip indicating the amount of money paid. 
            <strong>Cash, personal or institutional cheques are not acceptable</strong>. Please note that 
            <strong>all the semester fees must be paid on or before registration</strong>. Suitable accommodation shall be provided 
            on a first come first served basis in the university hostel. Meals are available at the University Dining Hall on a pay as you eat basis.
        </p>

        <p>
            Meanwhile, I take this opportunity to congratulate you on your admission and I look forward to welcoming you to the 
            University. Please report to the University <strong>Main Campus in Bondo Town</strong> on 
            <strong>{{ $reporting_date ?? 'August 19, 2026' }} at 8:00am</strong> for registration and orientation.
        </p>
    </div>

    <!-- Closing -->
    <div style="margin-top: 28px;">
        Yours Sincerely,
    </div>

    <div class="signature-block">
        <div style="height: 55px;">
            <img src="{{ public_path('images/admissionsign.png') }}" alt="Signature">
        </div> <!-- space for signature -->
        <div class="signature-name">
            Rev. Dr. Akuno Walter, PhD, CPM (MTI).<br>
            <strong>REGISTRAR (ACADEMIC AFFAIRS)</strong>
            For: Board of Postgraduate Studies
        </div>
        <div class="signature-name">
            <strong>For:</strong>
            Board of Postgraduate Studies
        </div>
        <div class="encls">
            Encls.
        </div>
        <div>
            <strong>Verification Code</strong><br>
            {{ strtoupper(substr(md5($admNo),0,10)) }}
        </div>
    </div>

</body>
</html>