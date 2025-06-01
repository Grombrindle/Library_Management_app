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
            margin: 1rem auto 5rem;
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
            width: 90%;
            max-width: 400px;
            margin: 1rem auto;
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

        .button:hover, .button:focus {
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
            max-width: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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
        }

        @media (max-width: 992px) {
            .title {
                font-size: 2rem;
            }

            .logo {
                width: 14rem;
                height: 18rem;
                margin-bottom: 3rem;
            }

            .button {
                height: 45px;
            }
        }

        @media (max-width: 768px) {
            .title {
                font-size: 1.75rem;
            }

            .logo {
                width: 12rem;
                height: 16rem;
                margin-bottom: 2rem;
            }

            .text {
                font-size: 1.1rem;
            }

            .button {
                height: 40px;
            }
        }

        @media (max-width: 576px) {
            .title {
                font-size: 1.5rem;
            }

            .logo {
                width: 10rem;
                height: 14rem;
                margin-bottom: 1.5rem;
            }

            .text {
                font-size: 1rem;
            }

            .button {
                height: 35px;
                margin: 0.75rem auto;
            }
        }

        @media (max-width: 400px) {
            .title {
                font-size: 2.25rem;
            }

            .logo {
                width: 24rem;
                height: 30rem;
                margin-bottom: 5rem;
            }

            .text {
                font-size: 1.9rem;
            }

            .button {
                height: 30px;
                margin: 0.5rem auto;
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
                margin: 0.5rem auto 1rem;
            }

            .title {
                font-size: 1.5rem;
                margin: 0.5rem auto;
            }

            .button {
                height: 35px;
                margin: 0.5rem auto;
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
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
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
        {{ __('messages.welcome') }} {{ Auth::user()->userName }}!
    </div>
    <div class="buttonContainer">
        <div style="width:50%; margin-right:auto; margin-left:auto; margin-top:auto;margin-bottom:auto; gap:5px">

            <a href="/subjects" class="button" style="text-decoration: none;">
                <div class="text">{{ __('messages.yourSubjects') }}</div>
            </a>
            <a href="/courses" class="button" style="text-decoration: none;">
                <div class="text">{{ __('messages.yourCourses') }}</div>
            </a>
            <a href="/lectures" class="button" style="text-decoration: none;">
                <div class="text">{{ __('messages.yourLectures') }}</div>
            </a>
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
