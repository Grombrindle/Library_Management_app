<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindSpark - {{ $title ?? 'Academic Platform' }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --primary: #b0b0cf;
            --primary-light: #d8d8e7;
            --primary-dark: #8a8aac;
            --secondary: #f8f8fa;
            --dark: #202025;
            --light: #ffffff;
            --accent: #b0b0cf;
            --gray: #e8e8ec;
            --bg-gradient-start: #f9f8fc;
            --bg-gradient-end: #e8e8ec;
            --text-color: #202025;
            --text-color-inverted: #ffffff;
            --card-bg: #ffffff;
            --card-border: #e8e8ec;
            --dropdown-bg: #f8f8fa;
            --select-bg: #b0b0cf;
            --select-hover: #8a8aac;
            --welcome-btn: #b0b0cf;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--secondary);
            color: var(--dark);
            scroll-behavior: smooth;
        }
    </style>
    {{ $head ?? '' }}
</head>
<body class="bg-[#f9f8fc]">
    <header class="sticky top-0 z-50 bg-white shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#b0b0cf]">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-[#202025]">MindSpark</h1>
                    </div>
                </div>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('home') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->is('home') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Home</a>
                    <a href="{{ url('webcourses') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->is('webcourses') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Courses</a>
                    <a href="{{ url('webteachers') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->is('webteachers') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Teachers</a>
                    <a href="{{ route('web.profile') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->routeIs('web.profile') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Profile</a>
                </nav>
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#e8e8ec]">
                            <i class="fas fa-globe text-[#b0b0cf]"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" onclick="changeLanguage('en')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
                            <a href="#" onclick="changeLanguage('fr')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Français</a>
                            <a href="#" onclick="changeLanguage('de')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Deutsch</a>
                            <a href="#" onclick="changeLanguage('tr')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Türkçe</a>
                            <a href="#" onclick="changeLanguage('es')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Español</a>
                            <a href="#" onclick="changeLanguage('ar')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">العربية</a>
                        </div>
                    </div>
                    @auth
                    <div class="relative">
                        <button class="w-10 h-10 flex items-center justify-center rounded-full bg-[#e8e8ec]">
                            <i class="fas fa-bell text-[#b0b0cf]"></i>
                        </button>
                        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">3</span>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-10 h-10 rounded-full bg-cover bg-center p-0 border-0" style="background:none;">
                            <img src="{{ Auth::user()->avatar ?? 'https://picsum.photos/seed/' . fake()->unique()->word . '/800/600' }}" alt="avatar" class="w-10 h-10 rounded-full object-cover" />
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('web.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Notifications</a>
                            <form action="/logout" method="POST" class="mobile-logout-form">
                                @csrf
                                <button type="submit" class="mobile-logout" onclick="return confirmLogout()">
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-[#b0b0cf] rounded-md hover:bg-[#8a8aac]">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-[#202025] bg-gray-200 rounded-md hover:bg-gray-300">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    <main>
        {{ $slot }}
    </main>
    <div x-data="{ showCourseModal: false, currentCourse: null }"
         x-show="showCourseModal"
         class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
         style="display: none;"
         @keydown.escape.window="showCourseModal = false">
        <div class="bg-white rounded-2xl w-full max-w-3xl p-8" @click.away="showCourseModal = false">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-3xl font-bold" x-text="currentCourse ? currentCourse.name : ''"></h2>
                <button @click="showCourseModal = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <template x-if="currentCourse">
                <div>
                    <img :src="currentCourse.image" :alt="currentCourse.name" class="w-full h-64 object-cover rounded-lg mb-6">
                    <div class="flex items-center mb-6">
                        <img :src="currentCourse.teacher.image" :alt="currentCourse.teacher.name"
                             class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <p class="font-semibold" x-text="currentCourse.teacher.name"></p>
                            <p class="text-gray-600 text-sm" x-text="currentCourse.subject ? currentCourse.subject.name : 'General'"></p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <p class="text-gray-700" x-text="currentCourse.description"></p>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold" x-text="currentCourse.lectures_count"></p>
                            <p class="text-gray-600">Lectures</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold" x-text="currentCourse.subscriptions_count"></p>
                            <p class="text-gray-600">Students</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold" x-text="currentCourse.rating ? currentCourse.rating.toFixed(1) : '0.0'"></p>
                            <p class="text-gray-600">Rating</p>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a :href="'#'" class="bg-[#b0b0cf] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors">
                            View Course
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <div x-data="{ showTeacherModal: false, currentTeacher: null }"
     x-show="showTeacherModal"
     @keydown.escape.window="showTeacherModal = false"
     class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl"
         @click.away="showTeacherModal = false"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h2 class="text-2xl font-bold text-[#202025]">Teacher Profile</h2>
            <button @click="showTeacherModal = false"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-full">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6" x-show="currentTeacher">
            <template x-if="currentTeacher">
                <div>
                    <!-- Teacher Image and Basic Info -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                        <img :src="currentTeacher.image"
                             :alt="currentTeacher.name"
                             class="w-32 h-32 rounded-full object-cover border-4 border-[#b0b0cf] shadow-lg">

                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-2xl font-bold text-[#202025] mb-2" x-text="currentTeacher.name"></h3>
                            <p class="text-[#b0b0cf] font-semibold mb-3" x-text="currentTeacher.subjects"></p>
                            <p class="text-gray-600 leading-relaxed" x-text="currentTeacher.description"></p>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-4 bg-gradient-to-br from-[#b0b0cf] to-[#8a8aac] text-white rounded-xl">
                            <div class="text-3xl font-bold mb-1" x-text="currentTeacher.courses_count"></div>
                            <p class="text-sm opacity-90">Courses</p>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-pink-400 to-pink-500 text-white rounded-xl">
                            <div class="text-3xl font-bold mb-1" x-text="currentTeacher.favorites_count"></div>
                            <p class="text-sm opacity-90">Favorites</p>
                        </div>
                    </div>

                    <!-- Universities -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-university text-[#b0b0cf] mr-2"></i>
                            Universities
                        </h4>
                        <p class="text-gray-600 text-sm" x-text="currentTeacher.universities || 'Not specified'"></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 justify-end">
                        <button @click="showTeacherModal = false"
                                class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                        <button class="px-6 py-2 bg-[#b0b0cf] text-white rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors">
                            <i class="fas fa-heart mr-2"></i>Add to Favorites
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
    <footer class="bg-[#202025] text-white pt-14 pb-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div class="md:col-span-2 flex flex-col justify-between">
                <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#b0b0cf]">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z" fill="currentColor"></path>
                    </svg>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight">MindSpark</h1>
                </div>
                <p class="text-gray-400 mb-6 max-w-xl leading-relaxed">
                    Your all-in-one academic companion for course management, professor connections, and library resources.
                </p>
                </div>
                <div class="flex gap-3 mt-4">
                <a href="#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-[#36363a] flex items-center justify-center hover:bg-[#b0b0cf] transition-colors" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" aria-label="Twitter" class="w-10 h-10 rounded-full bg-[#36363a] flex items-center justify-center hover:bg-[#b0b0cf] transition-colors" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" aria-label="LinkedIn" class="w-10 h-10 rounded-full bg-[#36363a] flex items-center justify-center hover:bg-[#b0b0cf] transition-colors" title="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-[#36363a] flex items-center justify-center hover:bg-[#b0b0cf] transition-colors" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-5 tracking-tight">Quick Links</h3>
                <ul class="space-y-3">
                <li><a href="{{ url('/home') }}" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                <li><a href="{{ url('/webcourses') }}" class="text-gray-400 hover:text-white transition-colors">Courses</a></li>
                <li><a href="{{ url('/webteachers') }}" class="text-gray-400 hover:text-white transition-colors">Teachers</a></li>
                <li><a href="{{ route('web.profile') }}" class="text-gray-400 hover:text-white transition-colors">Profile</a></li>
                </ul>
            </div>
            </div>
            <div class="border-t border-[#36363a] pt-7 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-center">
            <p class="text-gray-500 text-sm">&copy; 2024 MindSpark. All rights reserved.</p>
            <div class="flex flex-wrap justify-center md:justify-end gap-6">
                <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Privacy Policy</a>
                <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Terms of Service</a>
                <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Cookie Policy</a>
            </div>
            </div>
        </div>
    </footer>
    <x-slot name="scripts"></x-slot>
    <script>
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
        document.addEventListener('alpine:init', () => {
            // Remove the teacherModal component definition
            // Keep only the courseModal if needed elsewhere
            Alpine.data('courseModal', () => ({
                showCourseModal: false,
                currentCourse: null,
                openModal(course) {
                    this.currentCourse = course;
                    this.showCourseModal = true;
                    document.body.classList.add('overflow-hidden');
                },
                closeModal() {
                    this.showCourseModal = false;
                    document.body.classList.remove('overflow-hidden');
                }
            }));
        });
        </script>
        <script>
document.addEventListener('alpine:init', () => {
    // Make sure the modal data is available at the page level
    Alpine.data('teachersPage', () => ({
        showTeacherModal: false,
        currentTeacher: null,

        openTeacherModal(teacher) {
            this.currentTeacher = teacher;
            this.showTeacherModal = true;
            document.body.classList.add('overflow-hidden');
        },

        closeTeacherModal() {
            this.showTeacherModal = false;
            this.currentTeacher = null;
            document.body.classList.remove('overflow-hidden');
        }
    }));
});
</script>

</body>
</html>
