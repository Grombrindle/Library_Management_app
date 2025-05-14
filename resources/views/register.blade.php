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
        }

        .logo {
            width: 80%;
            margin: 0 auto;
            display: flex;
            justify-content: center;
        }

        .logo img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10%;
        }

        .name {
            text-align: center;
            font-size: 2.5rem;
            color: var(--text-color);
            margin-top: -5%;
            margin-bottom: 10%;
        }

        .textfieldContainer {
            width: 100%;
            margin: 10px 0;
        }

        .textfield {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            font-size: 1rem;
            box-sizing: border-box;
            margin-top: 15px;
            transition: 0.1s ease;
            background-color: var(--input-bg);
            color: var(--text-color);
        }

        .textfield:focus {
            outline: none;
            background-color: var(--input-focus-bg);
            border: 3px solid var(--primary-color);
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
            transition: 0.5s ease;
            z-index: 1;
        }

        .button {
            border-image: linear-gradient(45deg, transparent, transparent, transparent, transparent);
            border-image-slice: 1;
            border-image-width: 2px;
            border-image-outset: 1px;
        }

        .button:hover {
            background-color: var(--primary-hover);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            cursor: pointer;
            animation: rotateBorder 2s linear infinite;
            animation-duration: forwards;
        }

        .error {
            color: var(--error-color);
            font-size: 1rem;
            margin-top: 5px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
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
                padding: 20px;
            }

            .name {
                font-size: 2rem;
            }

            .button {
                font-size: 1rem;
                padding: 10px;
            }

            .textfield {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .form {
                width: 100%;
                margin: 5% auto;
                padding: 15px;
            }

            .name {
                font-size: 1.75rem;
            }

            .button {
                font-size: 0.875rem;
                padding: 8px;
            }

            .textfield {
                font-size: 0.75rem;
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
