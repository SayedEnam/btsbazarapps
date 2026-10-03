<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable - {{ config('app.name') }}</title>
    <style>
        :root {
            --primary: {{ \App\Models\Setting::theme('theme_primary_color') ?? '#22183a' }};
            --primary-dark: {{ \App\Models\Setting::theme('theme_primary_dark_color') ?? '#0f3d3e' }};
            --primary-light: {{ \App\Models\Setting::theme('theme_primary_light_color') ?? '#1b8a6b' }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--primary);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.05) 0%, transparent 50%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1) rotate(0deg);
            }
            50% {
                transform: scale(1.1) rotate(180deg);
            }
        }

        .container {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 680px;
            width: 100%;
            text-align: center;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto 40px;
            position: relative;
        }

        .gear {
            width: 100%;
            height: 100%;
            position: relative;
            animation: rotate 20s linear infinite;
        }

        .gear::before,
        .gear::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 6px solid var(--primary);
        }

        .gear::before {
            width: 80px;
            height: 80px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-top-color: var(--primary-light);
            border-right-color: var(--primary-light);
        }

        .gear::after {
            width: 40px;
            height: 40px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--primary);
            border: none;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .code {
            font-size: 120px;
            font-weight: 900;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 20px;
            letter-spacing: -4px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(34, 24, 58, 0.2);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.3;
            }
        }

        .contact {
            margin-top: 25px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 16px;
            border-left: 4px solid var(--primary);
            text-align: left;
        }

        .contact-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .contact-text {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.6;
        }

        .contact-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-text a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 13px;
        }

        @media (max-width: 640px) {
            body {
                padding: 15px;
            }

            .container {
                padding: 40px 25px;
                border-radius: 20px;
            }

            .code {
                font-size: 80px;
            }

            h1 {
                font-size: 28px;
            }

            p {
                font-size: 16px;
            }

            .icon-wrapper {
                width: 80px;
                height: 80px;
                margin-bottom: 30px;
            }

            .gear::before {
                width: 64px;
                height: 64px;
            }

            .gear::after {
                width: 32px;
                height: 32px;
            }
        }

        @media (max-width: 380px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 30px 20px;
            }

            .code {
                font-size: 64px;
            }

            h1 {
                font-size: 24px;
            }

            p {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrapper">
            <div class="gear"></div>
        </div>

        <div class="code">503</div>

        <h1>Service Unavailable</h1>

        <p>
            We're currently performing scheduled maintenance to improve your experience.<br>
            We'll be back online shortly. Thank you for your patience.
        </p>

        <div class="status">
            <span class="status-dot"></span>
            <span>Under Maintenance</span>
        </div>

        <div class="contact">
            <div class="contact-title">Need Immediate Assistance?</div>
            <div class="contact-text">
                Please contact us at <a href="mailto:support@btsbazar.com">support@btsbazar.com</a><br>
                or call us at <strong>{{ \App\Models\Setting::theme('phone') ?? '+880 01953059064' }}</strong>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
