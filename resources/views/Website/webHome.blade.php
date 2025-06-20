@extends('Components.web-layout')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#b0b0cf] to-[#68687a] text-white py-24">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold mb-6">Discover Your Learning Path</h1>
                <p class="text-xl mb-8 opacity-90">Join our community of learners and expert teachers to enhance your knowledge and skills.</p>
                <a href="{{ route('web.courses') }}" class="bg-white text-[#b0b0cf] px-8 py-3 rounded-lg font-semibold hover:bg-[#f8f8fa] transition-colors">
                        Explore Courses
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Why Choose Us</h2>
                <p class="text-[#68687a] max-w-2xl mx-auto">We provide the best learning experience with expert teachers and comprehensive resources.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <x-web-feature-card 
                    icon="fa-graduation-cap"
                    title="Expert Teachers"
                    description="Learn from experienced professionals in your field of study."
                    link="#"
                    linkText="Meet Our Teachers"
                />
                <x-web-feature-card 
                    icon="fa-book"
                    title="Quality Content"
                    description="Access high-quality learning materials and resources."
                    link="#"
                    linkText="Browse Content"
                />
                <x-web-feature-card 
                    icon="fa-users"
                    title="Active Community"
                    description="Join a vibrant community of learners and educators."
                    link="#"
                    linkText="Join Community"
                />
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section class="py-20 bg-[#f8f8fa]">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2">
                    <h2 class="text-4xl font-bold mb-6">Experience Our Platform</h2>
                    <p class="text-[#68687a] mb-8 text-lg">
                        Our platform is designed for a seamless learning experience. See how easy it is to navigate courses, access materials, and connect with your community.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-[#b0b0cf] flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Intuitive Interface</h3>
                                <p class="text-[#68687a]">Easily find your courses and resources in one place.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                             <div class="w-8 h-8 rounded-full bg-[#b0b0cf] flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Real-Time Collaboration</h3>
                                <p class="text-[#68687a]">Work on projects with classmates without leaving the platform.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="relative rounded-2xl overflow-hidden shadow-xl aspect-w-16 aspect-h-9">
                        <iframe 
                            class="w-full h-full"
                            src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1&mute=1&loop=1&playlist=dQw4w9WgXcQ&controls=0" 
                            title="Platform Demo"
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <x-web-stat-card 
                    :number="$stats['students']"
                    title="Students"
                    description="Students actively learning on our platform"
                />
                <x-web-stat-card 
                    :number="$stats['teachers']"
                    title="Expert Teachers"
                    description="Qualified teachers sharing their knowledge"
                />
                <x-web-stat-card 
                    :number="$stats['courses']"
                    title="Course Library"
                    description="Comprehensive courses across subjects"
                />
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-[#f8f8fa]">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">What Our Students Say</h2>
                <p class="text-[#68687a] max-w-2xl mx-auto">Hear from our community of learners about their experience.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <x-web-testimonial-card 
                    name="Sarah Johnson"
                    role="Computer Science Student"
                    image="https://via.placeholder.com/150"
                    content="The quality of teaching and resources available here is exceptional. I've learned so much in such a short time."
                    rating="5"
                />
                <x-web-testimonial-card 
                    name="Michael Chen"
                    role="Business Major"
                    image="https://via.placeholder.com/150"
                    content="The platform is very user-friendly and the teachers are always ready to help. Great learning experience!"
                    rating="4.5"
                />
                <x-web-testimonial-card 
                    name="Emily Brown"
                    role="Art Student"
                    image="https://via.placeholder.com/150"
                    content="I love how interactive the classes are. The community is very supportive and encouraging."
                    rating="5"
                />
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-[#b0b0cf] to-[#68687a] text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Start Learning?</h2>
            <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">Join thousands of students who are already learning and growing with us.</p>
            <a href="#" class="bg-white text-[#b0b0cf] px-8 py-3 rounded-lg font-semibold hover:bg-[#f8f8fa] transition-colors inline-block">
                Get Started Now
            </a>
        </div>
    </section>
@endsection