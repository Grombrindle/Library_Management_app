<head>
    <style>
        body {
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        @media(max-width:1600px) {
            .title {
                font-size: 50px;
            }
        }

        @media(max-width:800px) {
            .title {
                font-size: 40px;
            }
        }

        @media(max-width:600px) {
            .title {
                font-size: 30px;
            }
        }

        @media(max-width:400px) {
            .title {
                font-size: 20px;
            }
        }

        @media(max-width:700px) {
            .logo {
                width: 16rem;
                height: 20rem;
            }
        }

        .logo {
            width: 20rem;
            height: 25rem;
            margin: 1rem auto 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }

        .logo img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .logoContainer {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .button {
            width: 100%;
            background: var(--welcome-btn);
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2.5px solid var(--card-border);
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .button:hover,
        .button:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-color: var(--welcome-btn);
        }

        .button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .text {
            color: var(--text-color);
            text-decoration: none;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 500;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .buttonContainer {
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .buttonGrid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 1rem;
        }

        .title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--text-color);
            margin: 1rem auto;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        #circle {
            position: fixed;
            width: 250px;
            height: 250px;
            background-color: var(--card-bg);
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
            mix-blend-mode: overlay;
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 1600px) {
            .title {
                font-size: 2.5rem;
            }

            .logo {
                width: 18rem;
                height: 22rem;
            }
        }

        @media (max-width: 1200px) {
            .title {
                font-size: 2.25rem;
            }

            .logo {
                width: 16rem;
                height: 20rem;
            }

            .text {
                font-size: 1.2rem;
            }

            .buttonGrid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .title {
                font-size: 2rem;
            }

            .logo {
                width: 14rem;
                height: 18rem;
                margin-bottom: 1.5rem;
            }

            .button {
                height: 45px;
            }

            .buttonGrid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .title {
                font-size: 1.75rem;
            }

            .logo {
                width: 12rem;
                height: 16rem;
                margin-bottom: 1rem;
            }

            .text {
                font-size: 1.1rem;
            }

            .button {
                height: 40px;
            }

            .buttonGrid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .title {
                font-size: 1.5rem;
            }

            .logo {
                width: 10rem;
                height: 14rem;
                margin-bottom: 0.75rem;
            }

            .text {
                font-size: 1rem;
            }

            .button {
                height: 35px;
            }

            .buttonGrid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }

        @media (max-width: 400px) {
            html {
                overflow-x: hidden;
            }

            .title {
                font-size: 2.5rem;
            }

            .logo {
                width: 20rem;
                height: 30rem;
                margin-bottom: 0.5rem;
            }

            .text {
                font-size: 1.8rem;
            }

            .button {
                height: 30px;
                width: 90%;
                margin-left: auto;
                margin-right: auto;
                margin-top: 1rem;
            }
        }

        /* Landscape mode optimization */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 0.5rem;
            }

            .logo {
                width: 8rem;
                height: 10rem;
                margin: 0.5rem auto;
            }

            .title {
                font-size: 1.5rem;
                margin: 0.5rem auto;
            }

            .button {
                height: 35px;
            }

            .buttonGrid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.5rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) {
            .button:hover {
                transform: none;
                box-shadow: none;
            }

            .button:active {
                background-color: var(--card-bg);
                transform: scale(0.98);
            }

            #circle {
                display: none;
            }
        }

        /* High-DPI screens */
        @media (-webkit-min-device-pixel-ratio: 2),
        (min-resolution: 192dpi) {
            .button {
                border-width: 2px;
            }
        }
    </style>
</head>
<x-layout : nav=false>

    <div class="logo">
        <div class="logo">
            <img src="Images/Web/MindSpark outline.png" alt="" class="logo">
        </div>
    </div>
    <div class="title">
        {{ __('messages.welcome') }}
        {{ Auth::user()->userName }}!
    </div>
    <div class="buttonContainer">
        <div class="buttonGrid">
            <div style="display:flex; flex-direction:row; gap: 3rem;">
                <a href="/courses" class="button" id="button" style="text-decoration: none;">
                    <div class="text">
                        {{ __('messages.courses') }}
                    </div>
                </a>
                <a href="/subjects" class="button" id="button" style="text-decoration: none;">
                    <div class="text">
                        {{ __('messages.subjects') }}
                    </div>
                </a>
            </div>
            <div style="display:flex; flex-direction:row; gap: 3rem;">
                <a href="/lectures" class="button" style="text-decoration: none;">
                    <div class="text">
                        {{ __('messages.lectures') }}
                    </div>
                </a>
                <a href="/exams" class="button" style="text-decoration: none;">
                    <div class="text">
                        {{ __('messages.exams') }}
                    </div>
                </a>
                <a href="/users" class="button" style="text-decoration: none;">
                    <div class="text">
                        {{ __('messages.users') }}
                    </div>
                </a>
            </div>
            <div style="display:flex; flex-direction:row; gap: 3rem;">
                <a href="/admins" class="button" id="button" style="text-decoration: none;">
                    <div class="text">
                        {{ __('messages.admins') }}
                    </div>
                </a>
                <a href="/resources" class="button" style="text-decoration: none;">
                    <div class="text">
                        {{ __('messages.resources') }}
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="circle"id="circle"></div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.button'); // Select all buttons
            const circle = document.getElementById('circle'); // Select the circle

            buttons.forEach(button => {
                button.addEventListener('mousemove', (event) => {
                    const buttonRect = button.getBoundingClientRect();
                    const mouseX = event.clientX;
                    const mouseY = event.clientY;

                    // Check if the mouse is near or on the button
                    const isNearButton =
                        mouseX >= buttonRect.left &&
                        mouseX <= buttonRect.right &&
                        mouseY >= buttonRect.top &&
                        mouseY <= buttonRect.bottom;

                    if (isNearButton) {
                        // Position the circle at the mouse cursor
                        circle.style.left = `${mouseX}px`;
                        circle.style.top = `${mouseY}px`;
                        circle.style.opacity = '1';
                        circle.style.zIndex = '1';

                        // Calculate the clip-path based on the button's boundaries
                        const clipTop = Math.max(buttonRect.top - mouseY + 130, 0);
                        const clipRight = Math.max(mouseX - buttonRect.right + 130, 0);
                        const clipBottom = Math.max(mouseY - buttonRect.bottom + 130, 0);
                        const clipLeft = Math.max(buttonRect.left - mouseX + 130, 0);
                        circle.style.clipPath =
                            `inset(${clipTop}px ${clipRight}px ${clipBottom}px ${clipLeft}px)`;
                    }
                });

                button.addEventListener('mouseleave', () => {
                    circle.style.opacity = '0';
                });
                document.addEventListener('scroll', () => {
                    circle.style.opacity = '0';
                });
                let scrollTimer;
                document.addEventListener('scroll', () => {
                    document.body.classList.add('disable-hover');
                    clearTimeout(scrollTimer);
                    scrollTimer = setTimeout(() => {
                        document.body.classList.remove('disable-hover');
                    }, 200);
                })
            });
        });
    </script>
</x-layout>
