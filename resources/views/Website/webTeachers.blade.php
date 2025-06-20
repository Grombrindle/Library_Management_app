@extends('Components.web-layout')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#b0b0cf] to-[#68687a] text-white py-24">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold mb-6">Meet Our Expert Teachers</h1>
                <p class="text-xl mb-8 opacity-90">Learn from experienced professionals who are passionate about education.</p>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('web.teachers') }}" method="GET" class="relative">
                    <input type="text" 
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Search teachers by name, subject, or university..." 
                           class="w-full px-6 py-4 rounded-lg border border-gray-200 focus:outline-none focus:border-[#b0b0cf] text-[#68687a]">
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#b0b0cf] text-white px-6 py-2 rounded-lg hover:bg-[#68687a] transition-colors">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Teachers Grid -->
    <section class="py-12 bg-[#f8f8fa]">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($teachers as $teacher)
                    <x-web-teacher-card :teacher="$teacher" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $teachers->appends(request()->query())->links() }}
            </div>
        </div>
    </section>

    @if(isset($featuredTeacher))
    <!-- Featured Teacher -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Featured Teacher of the Month</h2>
                <p class="text-[#68687a] max-w-2xl mx-auto">Highlighting an exceptional educator making a significant impact.</p>
                                </div>
            <div class="bg-[#f8f8fa] rounded-2xl max-w-4xl mx-auto md:flex items-center">
                <div class="md:w-1/3 p-8">
                    <img src="{{ $featuredTeacher->image ?? 'https://via.placeholder.com/300' }}" alt="{{ $featuredTeacher->name }}" class="w-48 h-48 rounded-full object-cover mx-auto border-4 border-white shadow-lg">
                    </div>
                    <div class="md:w-2/3 p-8">
                    <h3 class="text-2xl font-bold mb-2">{{ $featuredTeacher->name }}</h3>
                    <p class="text-[#b0b0cf] font-semibold mb-4">{{ $featuredTeacher->subjects->pluck('name')->join(' & ') }}</p>
                    <p class="text-[#68687a] mb-6">
                        A distinguished professor with expertise in multiple fields, contributing significantly to our academic community.
                    </p>
                    <div class="flex items-center gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#b0b0cf]">{{ $featuredTeacher->courses_count }}</div>
                            <div class="text-sm text-[#68687a]">Courses</div>
                        </div>
                        <button class="ml-auto px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                                View Full Profile
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-[#b0b0cf] to-[#68687a] text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Join Our Teaching Community</h2>
            <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">Share your knowledge and expertise with students around the world.</p>
            <a href="#" class="bg-white text-[#b0b0cf] px-8 py-3 rounded-lg font-semibold hover:bg-[#f8f8fa] transition-colors inline-block">
                Become a Teacher
            </a>
        </div>
    </section>
@endsection