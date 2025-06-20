@extends('Components.web-layout')

@section('head')
<style>
    .scientific-badge {
        background: rgba(74, 134, 232, 0.1);
        color: #4a86e8;
    }
    
    .literary-badge {
        background: rgba(232, 106, 74, 0.1);
        color: #e86a4a;
    }
    
    .course-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(235, 231, 243, 0.5);
    }
    
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(119, 66, 234, 0.1);
        border-color: rgba(119, 66, 234, 0.2);
    }
    
    .category-filter {
        transition: all 0.3s ease;
    }
    
    .category-filter.active {
        background: #b0b0cf;
        color: white;
    }
    
    .category-filter:not(.active):hover {
        background: #e8e8ec;
    }
    
    .progress-bar {
        height: 6px;
        background: #e8e8ec;
        border-radius: 3px;
        overflow: hidden;
    }
    
    .progress-value {
        height: 100%;
        background: #b0b0cf;
        border-radius: 3px;
    }
    
    .featured-course-badge {
        background: #ffc107;
        color: #212529;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.9rem;
        display: inline-block;
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#b0b0cf] to-[#68687a] text-white py-24">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold mb-6">Explore Our Course Library</h1>
                <p class="text-xl mb-8 opacity-90">Find the perfect course to expand your knowledge and skills, taught by industry experts.</p>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <form action="{{ route('web.courses') }}" method="GET" class="flex flex-col md:flex-row gap-6 justify-between items-center">
                <div class="relative w-full md:w-1/2">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                           placeholder="Search courses, teachers..."
                           class="w-full px-6 py-4 rounded-lg border border-gray-200 focus:outline-none focus:border-[#b0b0cf] text-[#68687a]">
                    <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#b0b0cf] text-white px-6 py-2 rounded-lg hover:bg-[#8a8aac] transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="flex gap-2 sm:gap-4">
                    <a href="{{ route('web.courses', ['category' => 'all', 'search' => $search]) }}" class="category-filter px-4 py-3 rounded-full font-semibold {{ (!$category || $category == 'all') ? 'active' : '' }}">
                        All
                    </a>
                    <a href="{{ route('web.courses', ['category' => 'scientific', 'search' => $search]) }}" class="category-filter px-4 py-3 rounded-full font-semibold flex items-center {{ ($category == 'scientific') ? 'active' : '' }}">
                        <i class="fas fa-flask text-[#4a86e8] mr-2"></i> Scientific
                    </a>
                    <a href="{{ route('web.courses', ['category' => 'literary', 'search' => $search]) }}" class="category-filter px-4 py-3 rounded-full font-semibold flex items-center {{ ($category == 'literary') ? 'active' : '' }}">
                        <i class="fas fa-book text-[#e86a4a] mr-2"></i> Literary
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Featured Course Section -->
    @if($featuredCourse)
    <section class="py-12 bg-[#f8f8fa]">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center">Featured Course of the Month</h2>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden lg:flex">
                <div class="lg:w-1/2">
                    <img class="h-64 lg:h-full w-full object-cover" src="{{ $featuredCourse->image ?? 'https://via.placeholder.com/800x600' }}" alt="Featured Course Image">
                </div>
                <div class="p-8 lg:w-1/2">
                    <span class="featured-course-badge mb-4">Top Rated</span>
                    <h3 class="text-3xl font-bold mb-4">{{ $featuredCourse->name }}</h3>
                    <p class="text-[#68687a] mb-6">{{ Str::limit($featuredCourse->description, 150) }}</p>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="font-semibold">By {{ $featuredCourse->teacher->name }}</p>
                            <p class="text-sm text-gray-600">{{ $featuredCourse->subject->name }}</p>
                        </div>
                        <div class="text-right">
                             @if($featuredCourse->rating > 0)
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-[#b0b0cf] mr-2">{{ number_format($featuredCourse->rating, 1) }}</span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= round($featuredCourse->rating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-500">({{ $featuredCourse->ratings->count() }} ratings)</p>
                            @else
                                <p class="text-gray-500">Not rated yet</p>
                            @endif
                        </div>
                    </div>
                    <a href="#" class="bg-[#b0b0cf] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors inline-block w-full text-center">
                        View Course
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Courses Grid -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
             <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center">All Courses</h2>
            @if($courses->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                        <x-web-course-card :course="$course" />
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            @else
                 <div class="text-center py-16">
                    <i class="fas fa-search fa-3x text-gray-400 mb-4"></i>
                    <h3 class="text-2xl font-semibold mb-2">No Courses Found</h3>
                    <p class="text-gray-600">Try adjusting your search or filter criteria.</p>
                </div>
            @endif
        </div>
    </section>
@endsection