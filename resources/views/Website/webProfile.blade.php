<!-- resources/views/web/profile.blade.php -->
<x-web-layout>
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#b0b0cf] to-[#8a8aac] pt-16 pb-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="relative">
                    <div class="w-32 h-32 rounded-full bg-cover bg-center border-4 border-white" 
                         style="background-image: url('{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : 'https://picsum.photos/seed/' . fake()->unique()->word . '/800/600' }}')"></div>
                    <div class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-green-500 border-2 border-white flex items-center justify-center">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-3">
                        <h1 class="text-3xl font-bold text-white">{{ Auth::user()->userName }}</h1>
                        <span class="px-3 py-1 bg-white bg-opacity-20 text-white text-xs font-bold rounded-full">Student</span>
                    </div>
                    <p class="text-white text-opacity-80 mt-2">
                        {{ Auth::user()->countryCode }} {{ Auth::user()->number }}
                    </p>
                </div>
                <div class="ml-auto flex gap-2">
                    <a href="{{ route('web.profile.edit') }}" class="px-5 py-2.5 bg-white bg-opacity-20 text-white font-semibold rounded-lg hover:bg-opacity-30 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-white text-[#b0b0cf] font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="grid grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="col-span-12 lg:col-span-3">
                <div class="bg-white rounded-2xl p-4 shadow-sm">
                    <nav class="space-y-2">
                        <a href="{{ route('web.profile') }}" class="flex items-center gap-4 px-4 py-3 text-white bg-[#b0b0cf] rounded-lg">
                            <i class="fas fa-tachometer-alt w-5 text-center"></i>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                        <a href="{{ route('web.my-courses') }}" class="flex items-center gap-4 px-4 py-3 text-[#202025] rounded-lg hover:bg-[#b0b0cf] hover:text-white transition-colors group">
                            <i class="fas fa-book w-5 text-center text-[#b0b0cf] group-hover:text-white"></i>
                            <span class="font-semibold">My Courses</span>
                        </a>
                        <a href="{{ route('web.favorites') }}" class="flex items-center gap-4 px-4 py-3 text-[#202025] rounded-lg hover:bg-[#b0b0cf] hover:text-white transition-colors group">
                            <i class="fas fa-star w-5 text-center text-[#b0b0cf] group-hover:text-white"></i>
                            <span class="font-semibold">Favorites</span>
                        </a>
                        {{-- <a href="{{ route('web.quizzes') }}" class="flex items-center gap-4 px-4 py-3 text-[#202025] rounded-lg hover:bg-[#b0b0cf] hover:text-white transition-colors group">
                            <i class="fas fa-tasks w-5 text-center text-[#b0b0cf] group-hover:text-white"></i>
                            <span class="font-semibold">My Quizzes</span>
                        </a> --}}
                        {{-- <a href="{{ route('web.settings') }}" class="flex items-center gap-4 px-4 py-3 text-[#202025] rounded-lg hover:bg-[#b0b0cf] hover:text-white transition-colors group">
                            <i class="fas fa-cog w-5 text-center text-[#b0b0cf] group-hover:text-white"></i>
                            <span class="font-semibold">Settings</span>
                        </a> --}}
                    </nav>
                </div>
            </aside>

            <!-- Main Panel -->
            <main class="col-span-12 lg:col-span-9">
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#d8d8e7]">
                            <i class="fas fa-book-open text-2xl text-[#b0b0cf]"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">{{ $totalCourses }}</div>
                            <p class="text-gray-500">Courses</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#d8d8e7]">
                            <i class="fas fa-check-circle text-2xl text-[#b0b0cf]"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">{{ $completedCourses }}</div>
                            <p class="text-gray-500">Completed</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#d8d8e7]">
                            <i class="fas fa-layer-group text-2xl text-[#b0b0cf]"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">5</div>
                            <p class="text-gray-500">Certificates</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 flex items-center gap-5 shadow-sm">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-[#d8d8e7]">
                            <i class="fas fa-trophy text-2xl text-[#b0b0cf]"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold">A+</div>
                            <p class="text-gray-500">Avg. Grade</p>
                        </div>
                    </div>
                </div>

                <!-- Continue Learning -->
                <div class="bg-white rounded-2xl p-8 shadow-sm mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Continue Learning</h2>
                        <a href="{{ route('web.my-courses') }}" class="text-[#b0b0cf] font-semibold">View All</a>
                    </div>
                    
                    @if($continueLearning->count() > 0)
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($continueLearning as $course)
                        <div class="flex items-center gap-5 p-4 rounded-xl border border-gray-100">
                            <img src="{{ $course->image ? asset('storage/'.$course->image) : 'https://via.placeholder.com/100' }}" 
                                 alt="{{ $course->name }}" class="w-20 h-20 rounded-lg object-cover">
                            <div class="flex-grow">
                                <h3 class="font-bold">{{ $course->name }}</h3>
                                <p class="text-sm text-gray-500 mb-2">{{ $course->teacher->name }}</p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-[#b0b0cf] h-2.5 rounded-full" style="width: {{ $course->progress }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-[#d8d8e7] mx-auto mb-4">
                            <i class="fas fa-book-open text-2xl text-[#b0b0cf]"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">No Active Courses</h3>
                        <p class="text-gray-500 mb-4">Enroll in courses to start learning.</p>
                        <a href="{{ route('web.courses') }}" class="px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                            Browse Courses
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Achievements Section -->
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <div class="text-center py-10">
                        <div class="w-20 h-20 flex items-center justify-center rounded-full bg-[#d8d8e7] mx-auto mb-4">
                            <i class="fas fa-box-open text-4xl text-[#b0b0cf]"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">No Achievements Yet</h3>
                        <p class="text-gray-500 mb-4">Complete courses to earn new achievements.</p>
                        <a href="{{ route('web.courses') }}" class="px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                            Explore Courses
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-web-layout>