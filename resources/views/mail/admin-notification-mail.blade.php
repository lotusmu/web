<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #18181b 0%, #52525b 100%);
            padding: 32px 40px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.025em;
        }

        .content {
            padding: 40px;
        }

        .message {
            font-size: 16px;
            margin-bottom: 32px;
            color: #374151;
        }

        .actions {
            margin: 32px 0;
        }

        .button {
            display: inline-block;
            background-color: #18181b;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: background-color 0.2s;
            margin-right: 12px;
            margin-bottom: 8px;
        }

        .button:hover {
            background-color: #52525b;
        }

        .footer {
            background-color: #f3f4f6;
            padding: 24px 40px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 24px 0;
        }

        @media (max-width: 600px) {
            .container {
                margin: 0;
                box-shadow: none;
            }

            .header, .content, .footer {
                padding-left: 24px;
                padding-right: 24px;
            }

            .button {
                display: block;
                text-align: center;
                margin-right: 0;
                margin-bottom: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="content">
        <div class="message">
            <p>{{ __($body, $bodyParameters) }}</p>
        </div>

        @if(!empty($actions))
            <div class="actions">
                @foreach($actions as $action)
                    <a href="{{ url($action['url']) }}" class="button">
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="divider"></div>

        <p style="margin-bottom: 0; color: #6b7280; font-size: 14px;">
            This is an automated notification from {{ config('app.name') }}.
            Please do not reply to this email.
        </p>
    </div>

    <div class="footer">
        <p class="footer-text">
            Best regards,<br>
            <strong>{{ config('app.name') }} Team</strong>
        </p>
    </div>
</div>
</body>
</html>
