<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meeting Logged — Signature RequiredSupervisor Allocated</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family: Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding: 40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%;">

                {{-- Header --}}
                <tr>
                    <td style="background: linear-gradient(180deg, #1a3c6e 0%, #0000FF 100%);
                                border-radius: 16px 16px 0 0;
                                padding: 36px 40px;
                                text-align: center;">
                        <img src="{{ url('assets/img/JOOUSTLOGO1.png') }}"
                             alt="JOOUST" width="56" height="56"
                             style="border-radius:12px; display:block; margin: 0 auto 16px;">
                        <h1 style="margin:0; color:#ffffff; font-size:20px; font-weight:700;">JOOUST Postgraduate Portal</h1>
                        <p style="margin:6px 0 0; color:rgba(255,255,255,.75); font-size:13px;">Thesis Supervision &amp; Management System</p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="background:#ffffff;
                                border: 1px solid #e2e8f0;
                                border-top: none;
                                border-radius: 0 0 16px 16px;
                                padding: 40px;">

                       <div style="background: #1a3c6e; color: white; padding: 20px; border-radius: 6px 6px 0 0;">
                            <h2 style="margin:0;">JOOUST Postgraduate Portal</h2>
                            <p style="margin:4px 0 0;">Supervision Meeting — Action Required</p>
                        </div>
                        <div style="border: 1px solid #ddd; border-top:none; padding: 24px; border-radius: 0 0 6px 6px;">
                            <p>Dear {{ $meeting->student->user->name }},</p>
                            <p>Your supervisor <strong>{{ $meeting->supervisor->user->name }}</strong> has logged a supervision meeting that requires your sign-off.</p>
                            <table style="width:100%; border-collapse:collapse; margin: 16px 0;">
                                <tr><td style="padding:8px; border:1px solid #eee; background:#f8f9fa; font-weight:bold;">Date</td>
                                    <td style="padding:8px; border:1px solid #eee;">{{ $meeting->meeting_date->format('d M Y') }}</td></tr>
                                <tr><td style="padding:8px; border:1px solid #eee; background:#f8f9fa; font-weight:bold;">Agenda</td>
                                    <td style="padding:8px; border:1px solid #eee;">{{ $meeting->agenda }}</td></tr>
                            </table>
                        
                        </div>

                        <hr style="border:none; border-top:1px solid #e2e8f0; margin:0 0 24px;">
                        <p style="margin:0; font-size:12px; color:#94a3b8; text-align:center; line-height:1.6;">
                            This is an automated message from the JOOUST Postgraduate Portal.<br>
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
