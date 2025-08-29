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

        /* Notification styles */
        .notif {
            display: none;
            position: fixed;
            top: 0;
            transform: translateY(-120%);
            background: rgba(30, 30, 30, 0.35);
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px);
            color: var(--text-color);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 12px;
            padding: 14px 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.25);
            z-index: 9999;
            max-width: 720px;
            width: 30rem;
            opacity: 0;
        }
        .notif.show { display: block; animation: slideDownFade 300ms ease-out forwards; }
        /* Keep .show during hide to prevent instant jump; combine with .hide for exit */
        .notif.show.hide { animation: slideUpFade 350ms ease-in forwards; }
        .notif-actions { margin-top: 10px; display: flex; gap: 10px; justify-content: flex-end; }
        .notif-btn { padding: 8px 12px; border: 1px solid var(--card-border); background: rgba(255,255,255,0); color: var(--text-color); border-radius: 8px; cursor: pointer; }
        .notif-btn.primary { background: rgba(0,0,0,0); color: #fff; border-color: rgba(0,0,0,0)}

        @keyframes slideDownFade { from { transform: translateY(-120%); opacity: 0; } to { transform: translateY(20px); opacity: 1; } }
        @keyframes slideUpFade { from { transform: translateY(20px); opacity: 1; } to { transform: translateY(-120%); opacity: 0; } }

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

    @php
        $pendingCourseRequests = \App\Models\CourseRequest::where('status', 'pending')->count();
        $adminId = Auth::user() ? Auth::user()->id : null;
    @endphp

    @if(Auth::user() && Auth::user()->privileges === 2)
    <div class="notif" id="course-requests-notif" data-pending="{{ $pendingCourseRequests }}" data-admin="{{ $adminId }}">
        <div id="cr-message">
            @if($pendingCourseRequests === 0)
                {{ __('messages.noNewCourseRequests') ?? 'No new course requests' }}
            @elseif($pendingCourseRequests === 1)
                {{ __('messages.oneNewCourseRequest') ?? 'There is 1 new course request' }}
            @else
                {{ __('messages.newCourseRequests') ?? 'There are' }} <strong id="cr-count">{{ $pendingCourseRequests }}</strong> {{ __('messages.newCourseRequestsTail') ?? 'new course requests' }}
            @endif
        </div>
        <div class="notif-actions">
            <a href="{{ url('/admin/course-requests/show') }}" target="_blank" class="notif-btn primary">{{ __('messages.view') ?? 'View' }}</a>
            <button type="button" class="notif-btn" id="cr-dismiss">{{ __('messages.dismiss') ?? 'Dismiss' }}</button>
        </div>
    </div>
    @endif

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

            // Course Requests Notification (Full Admin)
            const notifEl = document.getElementById('course-requests-notif');
            const triggerBtn = document.getElementById('trigger-course-requests');
            if (notifEl && triggerBtn) {
                const adminId = notifEl.getAttribute('data-admin');
                const pending = parseInt(notifEl.getAttribute('data-pending')) || 0;
                const cookieKey = `cr_last_seen_count_${adminId}`;

                const getCookie = (name) => {
                    const value = `; ${document.cookie}`;
                    const parts = value.split(`; ${name}=`);
                    if (parts.length === 2) return parts.pop().split(';').shift();
                    return null;
                };
                const setCookie = (name, value, days = 7) => {
                    const d = new Date();
                    d.setTime(d.getTime() + (days*24*60*60*1000));
                    const expires = `expires=${d.toUTCString()}`;
                    document.cookie = `${name}=${value}; ${expires}; path=/`;
                };

                const lastSeen = parseInt(getCookie(cookieKey) || '0');
                const crCountEl = document.getElementById('cr-count');
                if (crCountEl) crCountEl.textContent = pending;

                const showNotif = () => {
                    notifEl.classList.remove('hide');
                    notifEl.classList.add('show');
                    // auto-hide after 6s
                    clearTimeout(window.__crHideTimer);
                    window.__crHideTimer = setTimeout(() => {
                        hideNotif();
                    }, 6000);
                };
                const hideNotif = () => {
                    // Keep 'show' to allow exit animation; just add 'hide'
                    notifEl.classList.add('hide');
                };
                // Keep element present; let CSS handle slide up fade
                notifEl.addEventListener('animationend', (e) => {
                    if (notifEl.classList.contains('hide')) {
                        // After exit animation completes, reset classes so it can be shown again later
                        notifEl.classList.remove('show');
                        notifEl.classList.remove('hide');
                        // Do not set display:none; let it remain off-screen due to transform
                    }
                });

                // Show if there are new pending beyond last seen
                if (pending > lastSeen) {
                    showNotif();
                    // mark current count as seen so it won't reappear until count increases
                    setCookie(cookieKey, pending.toString(), 30);
                }

                // Trigger button shows notif regardless
                triggerBtn.addEventListener('click', () => {
                    showNotif();
                });

                // Dismiss without marking seen
                const dismissBtn = document.getElementById('cr-dismiss');
                if (dismissBtn) {
                    dismissBtn.addEventListener('click', () => {
                        hideNotif();
                    });
                }


            }
        });
    </script>
</x-layout>
