<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MindSpark - {{ $title ?? 'Academic Platform' }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
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
                    <a href="{{ url('webHome') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->is('webHome') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Home</a>
                    <a href="{{ url('webCourses') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->is('webCourses') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Courses</a>
                    <a href="{{ url('webProfs') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->is('webProfs') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Teachers</a>
                    <a href="{{ url('webProfile') }}" class="pb-1 font-medium transition-colors hover:text-[#b0b0cf] {{ request()->is('webProfile') ? 'text-[#b0b0cf] border-b-2 border-[#b0b0cf]' : 'text-[#202025]' }}">Profile</a>
                </nav>
                
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <button class="w-10 h-10 flex items-center justify-center rounded-full bg-[#e8e8ec]">
                            <i class="fas fa-bell text-[#b0b0cf]"></i>
                        </button>
                        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">3</span>
                    </div>
                    @auth
                        <div class="w-10 h-10 rounded-full bg-cover bg-center" style="background-image: url('{{ Auth::user()->avatar ?? 'https://via.placeholder.com/150' }}')"></div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-300"></div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-[#202025] text-white pt-14 pb-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Info & Social -->
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
            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-5 tracking-tight">Quick Links</h3>
                <ul class="space-y-3">
                <li><a href="webHome" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                <li><a href="webCourses" class="text-gray-400 hover:text-white transition-colors">Courses</a></li>
                <li><a href="webProfs" class="text-gray-400 hover:text-white transition-colors">Teachers</a></li>
                <li><a href="webProfile" class="text-gray-400 hover:text-white transition-colors">Profile</a></li>
                </ul>
            </div>
            </div>
            <!-- Bottom Bar -->
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
</body>
</html> 