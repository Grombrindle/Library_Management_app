<style>
    .NavBar {
        width: 100%;
        background-color: #101010;
        height: 65px;
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-bottom: 2.5%;
        font-family: 'Pridi';
        padding: 0 10px;
        position: relative;
    }

    .NavBarElement {
        height: 100%;
        width: 50%;
        display: flex;
        flex-direction: row;
        align-items: center;
        margin-left: 5%;
    }

    .NavBarElement:last-child {
        width: 50%;
        justify-content: flex-end;
        display: flex;
        flex-direction: row;
    }

    .NavBarText {
        width: 100%;
        height: 100%;
        font-size: 1.4rem;
        padding: 0 1rem;
        text-align: center;
        text-decoration: none;
        color: var(--nav-text);
        background-color: transparent;
        transition: 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .NavBarLogout {
        font-size: 1.5rem;
        padding: 0.5rem 1rem;
        width: 150%;
        max-width: 120px;
        text-align: center;
        text-decoration: none;
        color: white;
        background-color: #555184;
        transition: 0.3s ease;
        display: flex;
        height: inherit;
        align-items: center;
        font-family: 'Pridi';
        cursor: pointer;
        border: none;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .NavBarText:hover {
        background-color: #9997BC;
        color: black;
    }

    .NavBarLogout:hover {
        background-color: #9997BC;
        color: black;
    }

    .NavBarText.active {
        background-color: #9997BC;
        color: black;
    }

    .nav-count {
        margin-left: 5px;
        background-color: #555184;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 5px;
        right: 5px;
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 1.7rem;
        cursor: pointer;
        padding: 0 1rem;
        font-family: 'Pridi';
        margin-left: auto;
    }

    /* Mobile Menu */
    .mobile-menu {
        display: none;
        position: absolute;
        top: 65px;
        background-color: #101010;
        z-index: 1000;
        flex-direction: column;
        padding: 10px 0;
        min-width: 200px;
        width:100%;
        border-radius: 0 0 5px 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    html[lang="ar"] .mobile-menu {
        left: 0;
        right: auto;
    }

    html:not([lang="ar"]) .mobile-menu {
        right: 0;
        left: auto;
    }

    .mobile-menu a,
    .mobile-menu .mobile-logout {
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        transition: 0.3s ease;
        white-space: nowrap;
        width: 100%;
        text-align: center;
    }

    .mobile-menu a:hover,
    .mobile-menu .mobile-logout:hover {
        background-color: #9997BC;
        color: black;
    }

    .mobile-menu .nav-count {
        position: relative;
        display: inline-flex;
        top: 0;
        right: 0;
        margin-left: 7px;
        margin-right: 7px;
        right: auto;
        font-size: 1.55rem;
        width:2.5rem;
        height:2.5rem;
    }

    .mobile-logout {
        background-color: #555184;
        border: none;
        text-align: left;
        font-family: 'Pridi';
        cursor: pointer;
    }

    @media (max-width: 1200px) {
        .NavBarText {
            font-size: 1.5rem;
        }

        .NavBarLogout {
            font-size: 1rem;
        }

        .nav-count {
            font-size: 0.7rem;
            width: 1rem;
            height: 1rem;
        }
    }

    @media (max-width: 992px) {
        .NavBarElement:last-child {
            display: none;
        }

        .mobile-menu-btn {
            display: block;
        }
    }

    @media (max-width: 768px) {
        .NavBarText {
            font-size: 1.2rem;
            padding: 0 0.8rem;
        }

        .NavBarLogout {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }

        .nav-count {
            font-size: 0.6rem;
            width: 0.9rem;
            height: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .NavBarText {
            font-size: 1rem;
            padding: 0 0.6rem;
        }

        .NavBarLogout {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }

        .nav-count {
            font-size: 0.5rem;
            width: 0.8rem;
            height: 0.8rem;
        }

        .NavBarElement {
            margin-left: 2%;
        }

        .NavBarElement:last-child {
            width: 40%;
        }
    }


    .language-dropdown {
        position: relative;
        display: inline-block;
        border-radius: 40px;
        /* margin-left: auto;
        margin-right: auto; */
    }

    /* Button style */
    .dropdown-lang-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }

    /* Flag icon size in the dropdown */
    .flag-icon {
        width: 20px;
        height: 14px;
        margin-right: 8px;
    }

    /* Dropdown content (hidden by default) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #101010;
        min-width: 160px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 4px;
        right: 0;
        top: 100%;
        padding: 0;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px 16px;
        font-size: 1.5rem;
        width: 100%;
        transition: 0.3s ease;
        white-space: nowrap;
        box-sizing: border-box;
    }

    /* On hover, change link color */
    .dropdown-content a:hover {
        background-color: #9997BC;
        color: black;
    }

    /* Show the dropdown content when hovering over the button */
    /* .language-dropdown:hover .dropdown-content {
            display: block;
        } */

    /* Style the button when hovered */
    .language-dropdown:hover .dropdown-btn {
        background-color: #3e8e41;
    }

    [dir="rtl"] .NavBarElement {
        margin-right: 5%;
        margin-left: 0;
    }

    [dir="rtl"] .NavBarElement:last-child {
        margin-right: 0;
        margin-left: 5%;
    }

    [dir="rtl"] .NavBarText {
        text-align: right;
    }

    @media (max-width: 400px) .mobile-menu.nav-count {
    }

    [dir="rtl"] .language-dropdown {
        margin-right: 5%;
        margin-left: 0;
    }

    [dir="rtl"] .dropdown-content {
        left: 0;
        right: auto;
    }

    [dir="rtl"] .dropdown-content a {
        text-align: right;
    }

    [dir="rtl"] .flag-icon {
        margin-right: 0;
        margin-left: 8px;
    }

    [dir="rtl"] .mobile-menu-btn {
        margin-right: auto;
        margin-left: 1rem;
    }

    [dir="rtl"] .mobile-menu {
        right: 0;
        left: auto;
    }

    [dir="rtl"] .mobile-menu a {
        text-align: right;
    }

    .theme-toggle,
    .language-toggle {
        position: relative;
        display: inline-block;
        background: transparent;
        border: none;
        cursor: pointer;
        color: white;
        font-size: 1.7rem;
        padding: 10px 16px;
        border-radius: 4px;
    }

    .button-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    [dir="rtl"] .button-container {
        margin-right: 5%;
        margin-left: 0;
    }

    [dir="ltr"] .button-container {
        margin-left: 5%;
        margin-right: 0;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #101010;
        min-width: 160px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 4px;
        right: 0;
        top: 100%;
    }

    [dir="rtl"] .dropdown-content {
        left: 0;
        right: auto;
    }

    .dropdown-content a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px 16px;
        font-size: 1.5rem;
        width: 100%;
        transition: 0.3s ease;
        white-space: nowrap;
    }

    .dropdown-content a:hover {
        background-color: #9997BC;
        color: black;
        width: 100%;
    }

    [dir="rtl"] .dropdown-content a {
        text-align: right;
    }

    [dir="rtl"] .flag-icon {
        margin-right: 0;
        margin-left: 8px;
    }
</style>

<nav class="NavBar">
    <div class="NavBarElement">
        <div style="width: 20%">
            <a href="/welcome"
                style="display: flex; flex-direction: row; text-decoration: none; color: white; font-size: 1.5rem; align-items: center; width:250px">
                <img src="{{ asset('Images/Web/MindSpark.png') }}" alt="ICON"
                    style="height: 20%; width:15%; margin-left:5%;"> <span
                    style="margin-left:5%; font-size:130%;">{{__('messages.appName')}}</span>
            </a>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">{{__('messages.menu')}}</button>

    <!-- Mobile Menu (hidden by default) -->
    <div class="mobile-menu" id="mobileMenu">
        @if (Auth::user()->privileges == 2)
            <a href="/courses" class="NavBarText">
                {{ __('messages.courses') }}
                <span class="nav-count">{{ App\Models\Course::count() }}</span>
            </a>
            <a href="/subjects" class="NavBarText">
                {{ __('messages.subjects') }}
                <span class="nav-count">{{ App\Models\Subject::count() }}</span>
            </a>
            <a href="/lectures" class="NavBarText">
                {{ __('messages.lectures') }}
                <span class="nav-count">{{ App\Models\Lecture::count() }}</span>
            </a>
            <a href="/teachers" class="NavBarText">
                {{ __('messages.teachers') }}
                <span class="nav-count">{{ App\Models\Teacher::count() }}</span>
            </a>
            <a href="/users" class="NavBarText">
                {{ __('messages.users') }}
                <span class="nav-count">{{ App\Models\User::count() }}</span>
            </a>
            <a href="/admins" class="NavBarText">
                {{ __('messages.admins') }}
                <span class="nav-count">{{ App\Models\Admin::count() }}</span>
            </a>
        @elseif (Auth::user()->privileges == 1)
            <a href="/users" class="NavBarText">
                {{ __('messages.users') }}
                <span class="nav-count">{{ App\Models\User::count() }}</span>
            </a>
        @elseif (Auth::user()->privileges == 0)
                @php
                    $teacher = App\Models\Teacher::findOrFail(Auth::user()->teacher_id);
                    $lecCount = 0;
                    foreach ($teacher->courses as $course) {
                        $lecCount += $course->lectures->count();
                    }
                @endphp
                <a href="/subjects" class="NavBarText" id="subjectsLink" style="width:100%;">
                    {{__('messages.yourSubjects')}}
                    <span class="nav-count">{{ $teacher->subjects->count() }}</span>
                </a>
                <a href="/courses" class="NavBarText" id="Link" style="width:100%;">
                    {{__('messages.yourCourses')}}
                    <span class="nav-count">{{ $teacher->courses->count() }}</span>
                </a>
                <a href="/lectures" class="NavBarText" id="Link" style="width:100%;">
                    {{__('messages.yourLectures')}}
                    <span class="nav-count">{{ $lecCount }}</span>
                </a>
        @endif

        <!-- Mobile Logout Button -->
        <form action="/logout" method="POST" class="mobile-logout-form">
            @csrf
            <button type="submit" class="mobile-logout" onclick="return confirmLogout()">
                {{ __('messages.logout') }}
            </button>
        </form>

        <div class="button-container">
            <div class="language-dropdown">
                <button class="language-toggle" onclick="toggleLanguageDropdown(event, 'mobileLanguageDropdown')">
                    <span class="material-symbols-outlined">language</span>
                </button>
                <div class="dropdown-content" id="mobileLanguageDropdown">
                    <a href="#" onclick="changeLanguage('en')">
                        <span class="flag-icon flag-icon-us"></span> English
                    </a>
                    <a href="#" onclick="changeLanguage('fr')">
                        <span class="flag-icon flag-icon-fr"></span> Français
                    </a>
                    <a href="#" onclick="changeLanguage('de')">
                        <span class="flag-icon flag-icon-de"></span> Deutsch
                    </a>
                    <a href="#" onclick="changeLanguage('tr')">
                        <span class="flag-icon flag-icon-tr"></span> Türkçe
                    </a>
                    <a href="#" onclick="changeLanguage('es')">
                        <span class="flag-icon flag-icon-es"></span> Español
                    </a>
                    <a href="#" onclick="changeLanguage('ar')">
                        <span class="flag-icon flag-icon-sa"></span> عربي
                    </a>
                </div>
            </div>
            <button class="theme-toggle" onclick="toggleTheme()">
                <span class="material-symbols-outlined">light_mode</span>
            </button>
        </div>
    </div>

    <!-- Original Desktop Navigation -->
    @if (Auth::user()->privileges == 2)
        <div class="NavBarElement" style="margin-right: 5%;">
            <a href="/courses" class="NavBarText" id="coursesLink">
                {{ __('messages.courses') }}
                <span class="nav-count">{{ App\Models\Course::count() }}</span>
            </a>
            <a href="/subjects" class="NavBarText" id="subjectsLink">
                {{ __('messages.subjects') }}
                <span class="nav-count">{{ App\Models\Subject::count() }}</span>
            </a>
            <a href="/lectures" class="NavBarText" id="lecturesLink">
                {{ __('messages.lectures') }}
                <span class="nav-count">{{ App\Models\Lecture::count() }}</span>
            </a>
            <a href="/teachers" class="NavBarText" id="teachersLink">
                {{ __('messages.teachers') }}
                <span class="nav-count">{{ App\Models\Teacher::count() }}</span>
            </a>
            <a href="/users" class="NavBarText" id="usersLink">
                {{ __('messages.users') }}
                <span class="nav-count">{{ App\Models\User::count() }}</span>
            </a>
            <a href="/admins" class="NavBarText" id="adminsLink">
                {{ __('messages.admins') }}
                <span class="nav-count">{{ App\Models\Admin::count() }}</span>
            </a>
    @elseif (Auth::user()->privileges == 1)
        <div class="NavBarElement" style="margin-right: 5%;">
            <a href="/users" class="NavBarText" id="usersLink" style="width:7%;">
                {{ __('messages.users') }}
                <span class="nav-count">{{ App\Models\User::count() }}</span>
            </a>
    @elseif (Auth::user()->privileges == 0)
                @php
                    $teacher = App\Models\Teacher::findOrFail(Auth::user()->teacher_id);
                    $lecCount = 0;
                    foreach ($teacher->courses as $course) {
                        $lecCount += $course->lectures->count();
                    }
                @endphp
                <div class="NavBarElement" style="margin-right: 5%;">
                    <a href="/subjects" class="NavBarText" id="subjectsLink" style="width:8%;">
                        {{__('messages.yourSubjects')}}
                        <span class="nav-count">{{ $teacher->subjects->count() }}</span>
                    </a>
                    <a href="/courses" class="NavBarText" id="Link" style="width:8%;">
                        {{__('messages.yourCourses')}}
                        <span class="nav-count">{{ $teacher->courses->count() }}</span>
                    </a>
                    <a href="/lectures" class="NavBarText" id="Link" style="width:8%;">
                        {{__('messages.yourLectures')}}
                        <span class="nav-count">{{ $lecCount }}</span>
                    </a>
    @endif
                <form action="/logout" method="POST"
                    style="cursor: pointer; padding: 0 0; height: 100%; margin-left: 10%; margin-right:5%;"
                    onsubmit="return confirmLogout()">
                    @csrf
                    <button type="submit" id="adminsLink" class="NavBarLogout" style="">
                        <div style="text-align:center">{{ __('messages.logout') }}</div>
                    </button>
                </form>
                <div class="language-dropdown">
                    <button class="language-toggle" onclick="toggleLanguageDropdown(event, 'desktopLanguageDropdown')">
                        <span class="material-symbols-outlined">language</span>
                    </button>
                    <div class="dropdown-content" id="desktopLanguageDropdown">
                        <a href="#" onclick="changeLanguage('en')">
                            <span class="flag-icon flag-icon-us"></span> English
                        </a>
                        <a href="#" onclick="changeLanguage('fr')">
                            <span class="flag-icon flag-icon-fr"></span> Français
                        </a>
                        <a href="#" onclick="changeLanguage('de')">
                            <span class="flag-icon flag-icon-de"></span> Deutsch
                        </a>
                        <a href="#" onclick="changeLanguage('tr')">
                            <span class="flag-icon flag-icon-tr"></span> Türkçe
                        </a>
                        <a href="#" onclick="changeLanguage('es')">
                            <span class="flag-icon flag-icon-es"></span> Español
                        </a>
                        <a href="#" onclick="changeLanguage('ar')">
                            <span class="flag-icon flag-icon-sa"></span> عربي
                        </a>
                    </div>
                </div>
                <button class="theme-toggle" onclick="toggleTheme()">
                    <span class="material-symbols-outlined">light_mode</span>
                </button>
            </div>

</nav>

<script>
    function confirmLogout() {
        return confirm('{{ __("messages.confirmLogout") }}');
    }

    // Function to highlight the current page link
    function setActiveLink() {
        const currentPage = window.location.pathname;
        const links = document.querySelectorAll('.NavBarText');

        links.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    // Mobile menu toggle functionality
    document.getElementById('mobileMenuBtn').addEventListener('click', function () {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.style.display = mobileMenu.style.display === 'flex' ? 'none' : 'flex';
        this.textContent = mobileMenu.style.display === 'flex' ? '{{__('messages.close')}}' : '{{__('messages.menu')}}';
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function (event) {
        const mobileMenu = document.getElementById('mobileMenu');
        const menuBtn = document.getElementById('mobileMenuBtn');

        if (!mobileMenu.contains(event.target) && event.target !== menuBtn) {
            mobileMenu.style.display = 'none';
            menuBtn.textContent = '{{__('messages.menu')}}';
        }
    });

    // Call the function when the page loads
    window.onload = setActiveLink;
</script>

<script>
    function toggleLanguageDropdown(event, dropdownId) {
        event.stopPropagation();
        const dropdown = document.getElementById(dropdownId);
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const mobileDropdown = document.getElementById('mobileLanguageDropdown');
        const desktopDropdown = document.getElementById('desktopLanguageDropdown');
        const languageToggles = document.querySelectorAll('.language-toggle');
        
        let clickedOnToggle = false;
        languageToggles.forEach(toggle => {
            if (toggle.contains(event.target)) {
                clickedOnToggle = true;
            }
        });

        if (!clickedOnToggle) {
            if (mobileDropdown) mobileDropdown.style.display = 'none';
            if (desktopDropdown) desktopDropdown.style.display = 'none';
        }
    });

    function changeLanguage(lang) {
        // Validate the locale code
        const validLocales = ['en', 'fr', 'de', 'tr', 'es', 'ar'];
        if (!validLocales.includes(lang)) {
            console.error('Invalid locale code:', lang);
            return;
        }

        document.cookie = `locale=${lang};path=/;max-age=31536000`; // Cookie expires in 1 year
        window.location.reload();
    }
</script>

<script>
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);

        // Set cookie with 1 year expiration
        const date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
        document.cookie = `theme=${newTheme};expires=${date.toUTCString()};path=/`;

        // Update icon
        const icons = document.querySelectorAll('.theme-toggle .material-symbols-outlined');
        icons.forEach(icon => {
            icon.textContent = newTheme === 'dark' ? 'dark_mode' : 'light_mode';
        });
    }

    // Initialize theme
    document.addEventListener('DOMContentLoaded', () => {
        const theme = getCookie('theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);

        // Set initial icon
        const icons = document.querySelectorAll('.theme-toggle .material-symbols-outlined');
        icons.forEach(icon => {
            icon.textContent = theme === 'dark' ? 'dark_mode' : 'light_mode';
        });
    });

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
</script>