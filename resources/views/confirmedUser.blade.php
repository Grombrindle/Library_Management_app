<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ __('messages.updateConfirmationTitle') }}</title>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0;
            background: linear-gradient(45deg, var(--bg-gradient-start) 0%, var(--bg-gradient-start) 30%, var(--bg-gradient-end) 60%, var(--bg-gradient-end) 70%, var(--bg-gradient-end) 100%);
            background-attachment: fixed;
            background-size: 175% 175%;
            animation: gradientShift 5s infinite;
        }

        .logo {
            width: 100%;
            max-width: 300px;
            height: auto;
            margin: 0 auto;
            display: block;
            transition: all 0.3s ease;
        }

        .logoContainer {
            width: 100%;
            max-width: 400px;
            margin: 2rem auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .message-container {
            text-align: center;
            margin: 2rem auto;
            padding: 0 1rem;
            max-width: 800px;
            width: 100%;
            animation: fadeIn 0.5s ease;
        }

        .message-container h1 {
            color: var(--text-color);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .message-container p {
            color: var(--text-color);
            font-size: 1.25rem;
            margin: 0;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 1200px) {
            .message-container h1 {
                font-size: 2.25rem;
            }

            .message-container p {
                font-size: 1.2rem;
            }

            .logo {
                max-width: 250px;
            }
        }

        @media (max-width: 992px) {
            .message-container h1 {
                font-size: 2rem;
            }

            .message-container p {
                font-size: 1.1rem;
            }

            .logo {
                max-width: 200px;
            }
        }

        @media (max-width: 768px) {
            .message-container h1 {
                font-size: 1.75rem;
            }

            .message-container p {
                font-size: 1rem;
            }

            .logo {
                max-width: 180px;
            }

            .logoContainer {
                margin: 1.5rem auto;
            }
        }

        @media (max-width: 576px) {
            .message-container h1 {
                font-size: 1.5rem;
            }

            .message-container p {
                font-size: 0.9rem;
            }

            .logo {
                max-width: 150px;
            }

            .logoContainer {
                margin: 1rem auto;
            }
        }

        @media (max-width: 400px) {
            .message-container h1 {
                font-size: 1.25rem;
            }

            .message-container p {
                font-size: 0.875rem;
            }

            .logo {
                max-width: 120px;
            }

            .logoContainer {
                margin: 0.75rem auto;
            }
        }

        /* Landscape mode optimization */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 0.5rem;
            }

            .logo {
                max-width: 100px;
            }

            .logoContainer {
                margin: 0.5rem auto;
            }

            .message-container {
                margin: 1rem auto;
            }

            .message-container h1 {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .message-container p {
                font-size: 0.875rem;
            }
        }

        /* High-DPI screens */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .message-container h1 {
                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            }
        }
    </style>
    <script>
        const link = @json(session('link', '/welcome'));
        console.log(link);
        // Redirect after 3 seconds
        setTimeout(() => {
            window.location.href = link;
        }, 3000);
    </script>
</head>

<x-layout :nav=false>
    <div class="logoContainer">
        <img src="{{ asset('Images/Web/MindSpark outline.png') }}" alt="" class="logo">
    </div>
    <div class="message-container">
        @if (session('user_info'))
            @php
                $info = session('user_info');
            @endphp
            @if ($info['message'] == 'ignored')
                <h1>
                    {{ __('messages.ignoredReport') }}
                </h1>
            @elseif($info['message'] == 'warned')
                <h1>
                    {{ __('messages.warnedUser', ['name' => $info['name']]) }}
                </h1>
                <br>
                <h1>
                    {{ __('messages.commentRemoved')}}
                </h1>
                <br>
            @elseif($info['message'] == 'banned')
                <h1>
                    {{ __('messages.bannedUser', ['name' => $info['name']]) }}
                </h1>
                <br>
                <h1>
                    {{ __('messages.commentRemoved')}}
                </h1>
                <br>
            @endif
        @else
            <h1>{{ __('messages.nothingToConfirm') }}</h1>
        @endif
        <p>{{ __('messages.redirecting') }}</p>
    </div>
</x-layout>

</html>
