<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback on Your Submission</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%;">

                {{-- Header --}}
                <tr>
                    <td style="background: linear-gradient(180deg, {{ brand('primary_color') }} 0%, {{ brand('accent_color') }} 100%);
                                border-radius: 16px 16px 0 0;
                                padding: 36px 40px;
                                text-align: center;">
                            <img src="{{ brand('logo_url') }}"
                                alt="{{ brand('short_name') }}" width="56" height="56"
                             style="border-radius:12px; display:block; margin: 0 auto 16px;">
                            <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700;">{{ brand('short_name') }} Postgraduate Portal</h1>
                            <p style="margin:6px 0 0; color:rgba(255,255,255,.75); font-size:13px;">{{ brand('tagline') }}</p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="background:#ffffff;
                                border: 1px solid #e2e8f0;
                                border-top: none;
                                border-radius: 0 0 16px 16px;
                                padding: 40px;">

                        @php
                            $stageName   = \App\Models\Thesis::STAGE_LABELS[$submission->stage] ?? ucfirst(str_replace('_', ' ', $submission->stage));
                            $decisionStr = ucfirst(str_replace('_', ' ', $decision));
                            $colour      = $decision === 'approved' ? '#16a34a' : ($decision === 'rejected' ? '#dc2626' : '#d97706');
                            $bgColour    = $decision === 'approved' ? '#f0fdf4' : ($decision === 'rejected' ? '#fef2f2' : '#fffbeb');
                            $borderColour= $decision === 'approved' ? '#bbf7d0' : ($decision === 'rejected' ? '#fecaca' : '#fde68a');
                        @endphp

                        <p style="margin:0 0 6px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#64748b;">Submission Feedback</p>
                        <h2 style="margin:0 0 24px; font-size:22px; color:#1a202c;">{{ $stageName }}</h2>

                        <p style="margin:0 0 16px; font-size:15px; color:#374151; line-height:1.6;">
                            Dear {{ $thesis->student->user->name }},
                        </p>
                        <p style="margin:0 0 24px; font-size:15px; color:#374151; line-height:1.6;">
                            Your <strong>{{ $stageName }}</strong> submission has been reviewed
                            @if($reviewerName) by <strong>{{ $reviewerName }}</strong>@endif.
                            Please review the decision and feedback below.
                        </p>

                        {{-- Decision badge --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background:{{ $bgColour }};
                                           border: 1px solid {{ $borderColour }};
                                           border-left: 4px solid {{ $colour }};
                                           border-radius:8px;
                                           padding:16px 20px;">
                                    <p style="margin:0 0 4px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:{{ $colour }};">Decision</p>
                                    <p style="margin:0; font-size:18px; font-weight:700; color:{{ $colour }};">{{ $decisionStr }}</p>
                                </td>
                            </tr>
                        </table>

                        {{-- Comments --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                            <tr>
                                <td style="background:#f8fafc;
                                           border: 1px solid #e2e8f0;
                                           border-radius:8px;
                                           padding:20px;">
                                    <p style="margin:0 0 10px; font-size:13px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em;">Feedback Comments</p>
                                    <p style="margin:0; font-size:14px; color:#374151; line-height:1.7; white-space:pre-wrap;">{{ $comments }}</p>
                                </td>
                            </tr>
                        </table>

                        @if($decision === 'revision_required')
                        {{-- Revision notice --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                            <tr>
                                <td style="background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #f59e0b; border-radius:0 8px 8px 0; padding:14px 16px;">
                                    <p style="margin:0; font-size:13px; color:#92400e; line-height:1.5;">
                                        <strong>Action Required:</strong> Please address the feedback and resubmit your work through the portal.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        @endif

                        {{-- CTA --}}
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ route('thesis.feedback') }}"
                                       style="display:inline-block;
                                              background:{{ brand('accent_color') }};
                                              color:#ffffff;
                                              font-size:15px;
                                              font-weight:600;
                                              text-decoration:none;
                                              padding:14px 36px;
                                              border-radius:8px;">
                                        View Full Feedback
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <p style="margin:-20px 0 32px; font-size:12px; color:#64748b; text-align:center;">
                            If the button doesn't work, visit <a href="{{ route('thesis.feedback') }}" style="color:{{ brand('primary_color') }};">your feedback page</a>.
                        </p>

                        <hr style="border:none; border-top:1px solid #e2e8f0; margin:0 0 24px;">
                        <p style="margin:0; font-size:12px; color:#94a3b8; text-align:center; line-height:1.6;">
                            This is an automated message from the {{ brand('short_name') }} Postgraduate Portal.<br>
                            {{ brand('name') }}<br>
                            &copy; {{ date('Y') }} All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
