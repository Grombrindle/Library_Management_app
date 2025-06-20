<x-web-layout>
    <x-slot name="head">
        <style>
            .stat-card {
                transition: all 0.3s ease;
                border: 1px solid transparent;
            }
            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(119, 66, 234, 0.1);
                border-color: rgba(119, 66, 234, 0.2);
            }
        </style>
    </x-slot>
    <main>
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-[#b0b0cf] to-[#d8d8e7] text-white py-24 overflow-hidden">
            <div class="container mx-auto px-6 relative z-10">
                <div class="max-w-3xl text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 text-[#202025]">Discover Your Learning Path</h1>
                    <p class="text-xl mb-8 text-[#202025] opacity-90">Join our community of learners and expert teachers to enhance your knowledge and skills.</p>
                    <a href="{{ route('web.courses') }}" class="bg-[#202025] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#3a3a40] transition-colors shadow-lg">
                        Explore Courses
                    </a>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-3 gap-8 text-center">
                    <x-web-stat-card 
                        :number="$stats['students']"
                        title="Active Students"
                        description="Engaged and learning on our platform"
                        icon="fa-users"
                    />
                    <x-web-stat-card 
                        :number="$stats['teachers']"
                        title="Expert Teachers"
                        description="Sharing their knowledge and passion"
                        icon="fa-chalkboard-teacher"
                    />
                    <x-web-stat-card 
                        :number="$stats['courses']"
                        title="Course Library"
                        description="Covering a wide range of subjects"
                        icon="fa-book-open"
                    />
                </div>
            </div>
        </section>

        <!-- Featured Courses -->
        <section class="py-20 bg-[#f8f8fa]">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center text-[#202025]">Featured Courses</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                        <x-web-course-card :course="$course" />
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Top Teachers -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center text-[#202025]">Meet Our Top Teachers</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($teachers as $teacher)
                        <x-web-teacher-card :teacher="$teacher" />
                    @endforeach
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-br from-[#b0b0cf] to-[#8a8aac] text-white">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold mb-6">Ready to Start Learning?</h2>
                <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">Join thousands of students who are already learning and growing with us.</p>
                <a href="{{ route('register') }}" class="bg-white text-[#b0b0cf] px-8 py-3 rounded-lg font-semibold hover:bg-[#f8f8fa] transition-colors inline-block shadow-lg">
                    Get Started Now
                </a>
            </div>
        </section>
    </main>
</x-web-layout>