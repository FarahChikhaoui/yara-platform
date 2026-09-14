<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your YARA email</title>
</head>

<body style="margin:0; padding:0; background:#f5f5f3;
             font-family:Arial, Helvetica, sans-serif; color:#1f2937;">

<table width="100%" cellpadding="0" cellspacing="0"
       style="padding:40px 15px; background:#f5f5f3;">
    <tr>
        <td align="center">

            <table width="100%" cellpadding="0" cellspacing="0"
                   style="max-width:560px; background:#ffffff;
                          border-radius:16px; overflow:hidden;
                          box-shadow:0 4px 18px rgba(0,0,0,0.06);">

                {{-- Header --}}
                <tr>
                    <td style="background:#facc15; padding:24px 32px;">
                        <div style="font-size:24px;
                                    font-weight:700;
                                    color:#111827;">
                            YARA
                        </div>

                        <div style="margin-top:3px;
                                    font-size:12px;
                                    color:#4b5563;">
                            AI Readiness & Transformation
                        </div>
                    </td>
                </tr>

                {{-- Content --}}
                <tr>
                    <td style="padding:38px 32px;">

                        <h1 style="margin:0 0 14px;
                                   font-size:24px;
                                   color:#111827;">
                            Verify your email
                        </h1>

                        <p style="margin:0 0 10px;
                                  font-size:15px;
                                  line-height:1.6;">
                            Hi {{ $user->first_name ?? $user->name }},
                        </p>

                        <p style="margin:0 0 28px;
                                  font-size:15px;
                                  line-height:1.6;
                                  color:#4b5563;">
                            Enter the verification code below to complete
                            your YARA registration.
                        </p>

                        {{-- Code --}}
                        <div style="background:#fffbeb;
                                    border:1px solid #fde68a;
                                    border-radius:12px;
                                    padding:22px;
                                    text-align:center;
                                    margin-bottom:26px;">

                            <div style="font-size:12px;
                                        text-transform:uppercase;
                                        letter-spacing:1.5px;
                                        color:#6b7280;
                                        margin-bottom:8px;">
                                Verification code
                            </div>

                            <div style="font-size:32px;
                                        font-weight:700;
                                        letter-spacing:8px;
                                        color:#111827;">
                                {{ $verificationCode }}
                            </div>

                        </div>

                        <p style="margin:0;
                                  font-size:14px;
                                  line-height:1.6;
                                  color:#6b7280;">
                            This code expires in
                            <strong>10 minutes</strong>.
                            If you didn't create a YARA account,
                            you can safely ignore this email.
                        </p>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="padding:20px 32px;
                               background:#fafafa;
                               border-top:1px solid #eeeeee;
                               text-align:center;
                               font-size:12px;
                               color:#9ca3af;">
                        YARA · Assess today. Transform tomorrow.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>