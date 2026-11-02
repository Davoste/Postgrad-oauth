<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Progress Report Due</title></head>
<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(180deg, {{ brand('primary_color') }} 0%, {{ brand('accent_color') }} 100%); color: white; padding: 20px; border-radius: 6px 6px 0 0; text-align:center;">
        <img src="{{ brand('logo_url') }}" alt="{{ brand('short_name') }}" width="56" height="56" style="border-radius:12px; display:block; margin:0 auto 12px;">
        <h2 style="margin:0;">{{ brand('short_name') }} Postgraduate Portal</h2>
        <p style="margin:4px 0 0;">Progress Report Due</p>
    </div>
    <div style="border: 1px solid #ddd; border-top:none; padding: 24px; border-radius: 0 0 6px 6px;">
        <p>Dear {{ $thesis->supervisor->user->name }},</p>
        @php $typeLabel = $reportType === 'quarterly' ? 'Quarterly' : 'Bi-Annual'; @endphp
        <p>A <strong>{{ $typeLabel }} Progress Report</strong> is now due for the following student:</p>
        <table style="width:100%; border-collapse:collapse; margin: 16px 0;">
            <tr><td style="padding:8px; border:1px solid #eee; background:#f8f9fa; font-weight:bold;">Student</td>
                <td style="padding:8px; border:1px solid #eee;">{{ $thesis->student->user->name }} ({{ $thesis->student->reg_no }})</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee; background:#f8f9fa; font-weight:bold;">Programme</td>
                <td style="padding:8px; border:1px solid #eee;">{{ $thesis->student->program }}</td></tr>
            <tr><td style="padding:8px; border:1px solid #eee; background:#f8f9fa; font-weight:bold;">Current Stage</td>
                <td style="padding:8px; border:1px solid #eee;">{{ $thesis->stage_label }}</td></tr>
        </table>
        <p>Please log in to the portal to submit the progress report.</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0 16px;">
            <tr>
                <td align="center">
                    <a href="{{ route('login') }}" style="display:inline-block; background:{{ brand('accent_color') }}; color:#ffffff; font-size:15px; font-weight:600; text-decoration:none; padding:14px 36px; border-radius:8px;">
                        Sign In to the Portal
                    </a>
                </td>
            </tr>
        </table>
        <p style="margin:0 0 24px; font-size:12px; color:#64748b; text-align:center;">
            Or visit <a href="{{ route('login') }}" style="color:{{ brand('primary_color') }};">the portal sign-in page</a>.
        </p>
        <p style="margin-top: 32px; font-size: 12px; color: #888; text-align:center;">
            This is an automated message from the {{ brand('short_name') }} Postgraduate Portal.<br>
            {{ brand('name') }}<br>
            &copy; {{ date('Y') }} All rights reserved.
        </p>
    </div>
</body>
</html>
