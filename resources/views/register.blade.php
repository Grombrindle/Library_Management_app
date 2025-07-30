@props(['nav' => false])

<x-layout :nav="$nav">
    <style>
        body {
            overflow: hidden;
        }
        .form {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: auto;
            min-height: 80vh;
            width: 90%;
            max-width: 400px;
            margin: 5% auto;
            border: 3px solid var(--border-color);
            border-radius: 9px;
            padding: 30px;
            box-sizing: border-box;
            background-color: var(--form-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 80%;
            max-width: 300px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .logo img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10%;
            object-fit: contain;
        }

        .name {
            text-align: center;
            font-size: 2.5rem;
            color: var(--text-color);
            margin-top: -5%;
            margin-bottom: 10%;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: font-size 0.3s ease;
        }

        .textfieldContainer {
            width: 100%;
            margin: 10px 0;
            position: relative;
        }

        .textfield {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            font-size: 1rem;
            box-sizing: border-box;
            margin-top: 15px;
            transition: all 0.3s ease;
            background-color: var(--input-bg);
            color: var(--text-color);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .textfield:focus {
            outline: none;
            background-color: var(--input-focus-bg);
            border: 3px solid var(--primary-color);
            box-shadow: 0 0 0 3px rgba(153, 151, 188, 0.2);
        }

        .button {
            position: relative;
            width: 100%;
            padding: 15px;
            font-size: 1.25rem;
            background: var(--primary-color);
            color: var(--button-text);
            border: 3px solid transparent;
            cursor: pointer;
            margin-top: 60px;
            transition: all 0.3s ease;
            z-index: 1;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            -webkit-tap-highlight-color: transparent;
        }

        .button:hover, .button:focus {
            background-color: var(--primary-hover);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            cursor: pointer;
            animation: rotateBorder 2s linear infinite;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .error {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 5px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            padding: 5px;
            border-radius: 4px;
            background-color: rgba(255, 0, 0, 0.1);
        }

        @keyframes rotateBorder {
            0% {
                border-image-source: linear-gradient(90deg, transparent, transparent, transparent, var(--border-color));
            }

            25% {
                border-image-source: linear-gradient(90deg, var(--border-color), transparent, transparent, transparent);
            }

            50% {
                border-image-source: linear-gradient(90deg, transparent, var(--border-color), transparent, transparent);
            }

            75% {
                border-image-source: linear-gradient(90deg, transparent, transparent, var(--border-color), transparent);
            }

            100% {
                border-image-source: linear-gradient(90deg, transparent, transparent, transparent, var(--border-color));
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
        @media (max-width: 768px) {
            .form {
                width: 95%;
                margin: 10% auto;
                padding: 25px;
                min-height: 70vh;
            }

            .name {
                font-size: 2rem;
                margin-bottom: 8%;
            }

            .button {
                font-size: 1.1rem;
                padding: 12px;
                margin-top: 40px;
            }

            .textfield {
                font-size: 1rem;
                padding: 10px;
            }

            .logo {
                width: 70%;
            }
        }

        @media (max-width: 480px) {
            .form {
                width: 80%;
                margin: 0;
                padding: 20px;
                margin-top:6rem;
                border-radius: 30px;
                /* border-left: none;
                border-right: none; */
            }

            .name {
                font-size: 2.75rem;
                margin-bottom: 6%;
            }

            .button {
                font-size: 1rem;
                padding: 10px;
                margin-top: 30px;
            }

            .textfield {
                font-size: 1.9375rem;
                padding: 8px;
            }

            .logo {
                width: 70%;
                margin-bottom: 5%;
            }

            .error {
                font-size: 0.8125rem;
            }
        }

        /* Landscape mode optimization */
        @media (max-height: 600px) and (orientation: landscape) {
            .form {
                min-height: 100vh;
                margin: 0;
                padding: 15px;
            }

            .logo {
                width: 50%;
                margin-bottom: 2%;
            }

            .name {
                margin: 0 0 3% 0;
            }

            .button {
                margin-top: 20px;
            }
        }

        /* High-DPI screens */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .form {
                border-width: 2px;
            }

            .textfield {
                border-width: 1px;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) {
            .button:hover {
                transform: none;
                box-shadow: none;
            }

            .button:active {
                background-color: var(--primary-hover);
                transform: scale(0.98);
            }

            .textfield:focus {
                box-shadow: none;
            }
        }

        /* Dark mode variables */
        [data-theme="dark"] {
            --form-bg: rgba(0, 0, 0, 0.2);
            --border-color: #9997BC;
            --text-color: #ffffff;
            --input-bg: rgba(255, 255, 255, 0.1);
            --input-focus-bg: rgba(255, 255, 255, 0.2);
            --primary-color: #9997BC;
            --primary-hover: rgba(46, 48, 97, 0.7);
            --button-text: #ffffff;
            --error-color: #ff6b6b;
        }

        /* Light mode variables */
        [data-theme="light"] {
            --form-bg: rgba(255, 255, 255, 0.2);
            --border-color: #555184;
            --text-color: #000000;
            --input-bg: rgba(255, 255, 255, 0.8);
            --input-focus-bg: ghostwhite;
            --primary-color: #9997BC;
            --primary-hover: rgba(46, 48, 97, 0.7);
            --button-text: #ffffff;
            --error-color: #ff0000;
        }

        .register-container {
            width: 100%;
            max-width: clamp(150px, 80vw, 800px);
            margin: clamp(1%, 2vw, 2%) auto;
            padding: clamp(2%, 3vw, 4%);
            background: var(--card-bg);
            border: var(--card-border) clamp(2px, 0.5vw, 4px) solid;
            border-radius: clamp(2px, 0.5vw, 3px);
            color: var(--text-color);
        }

        .register-title {
            font-size: clamp(16px, 2vw + 10px, 24px);
            font-weight: bold;
            margin-bottom: clamp(1%, 2vw, 2%);
            text-align: center;
        }

        .register-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: clamp(1%, 2vw, 2%);
        }

        .register-input {
            width: 100%;
            padding: clamp(0.5%, 1vw, 1%);
            font-size: clamp(14px, 1.5vw + 8px, 20px);
            border: var(--card-border) clamp(1px, 0.3vw, 2px) solid;
            border-radius: clamp(2px, 0.5vw, 3px);
            background: var(--input-bg);
            color: var(--text-color);
        }

        .register-button {
            padding: clamp(0.5%, 1vw, 1%) clamp(1%, 2vw, 2%);
            font-size: clamp(14px, 1.5vw + 8px, 20px);
            background: var(--button-bg);
            color: var(--button-text);
            border: var(--card-border) clamp(1px, 0.3vw, 2px) solid;
            border-radius: clamp(2px, 0.5vw, 3px);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .register-button:hover {
            background: var(--button-hover-bg);
            color: var(--button-hover-text);
        }

        .register-error {
            color: var(--error-color);
            font-size: clamp(12px, 1.3vw + 6px, 18px);
            margin-top: clamp(0.5%, 1vw, 1%);
        }

        /* Remove all media queries and replace with clamp-based scaling */
        @media (max-width: 768px) {
            .register-container {
                padding: clamp(1%, 2vw, 3%);
            }

            .register-title {
                font-size: clamp(14px, 1.8vw + 8px, 20px);
            }

            .register-input {
                padding: clamp(0.3%, 0.8vw, 0.8%);
            }

            .register-button {
                padding: clamp(0.3%, 0.8vw, 0.8%) clamp(0.8%, 1.5vw, 1.5%);
            }

            .register-error {
                font-size: clamp(10px, 1.1vw + 4px, 16px);
            }
        }
    </style>

    <div class="form">
        <div class="logo">
            <img src="{{ asset('Images/Web/MindSpark outline.png') }}" alt="Mind Spark Logo" class="logo">
        </div>
        <div class="name">{{ __('messages.appName') }}</div>
        <form class="container" method="POST" action="/weblogin">
            @csrf
            <div class="textfieldContainer">
                <input class="textfield" id="userName" type="text" name="userName" placeholder="{{ __('messages.usernamePlaceholder') }}"
                    style="text-align:center;" autocomplete="off" title="" value="{{ old('userName') }}" required>
            </div>
            @error('userName')
                <div class="error">{{ $message }}</div>
            @enderror
            <div class="textfieldContainer">
                <input class="textfield" type="password" name="password" placeholder="{{ __('messages.passwordPlaceholder') }}"
                    style="text-align:center;" autocomplete="off" title="" value="{{ old('password') }}" required>
            </div>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
            <div>
                <button class="button">{{ __('messages.loginButton') }}</button>
            </div>
        </form>

        <br>


    <div style="margin-top:15px;text-align:center">
        <a href="{{ route('web.login') }}" style="color:var(--text-color)">{{__('messages.areUser')}}</a>
    </div>
    </div>

    <script>
        // Initialize theme from cookie
        document.addEventListener('DOMContentLoaded', () => {
            const cookieTheme = document.cookie
                .split('; ')
                .find(row => row.startsWith('theme='))
                ?.split('=')[1];

            const savedTheme = cookieTheme || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });
    </script>
</x-layout>
