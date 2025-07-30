<x-web-layout>
    <x-slot name="head">
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
    </x-slot>

    <div x-data="courseModal" @keydown.escape.window="closeModal()">
        <main>
            <section class="relative bg-gradient-to-br from-[#b0b0cf] to-[#d8d8e7] text-white py-24">
                <div class="container mx-auto px-6">
                    <div class="max-w-3xl text-center md:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold mb-6 text-[#202025]">Explore Our Course Library</h1>
                        <p class="text-xl mb-8 opacity-90 text-[#202025]">Find the perfect course to expand your
                            knowledge and skills, taught by industry experts.</p>
                    </div>
                </div>
            </section>

            <section class="py-12 bg-white">
                <div class="container mx-auto px-6">
                    <form action="{{ route('web.courses') }}" method="GET"
                        class="flex flex-col md:flex-row gap-6 justify-between items-center">
                        <div class="relative w-full md:w-1/2">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Search courses, teachers..."
                                class="w-full px-6 py-4 rounded-lg border border-gray-200 focus:outline-none focus:border-[#b0b0cf] text-[#68687a]">
                            <button type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#b0b0cf] text-white px-6 py-2 rounded-lg hover:bg-[#8a8aac] transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="flex gap-2 sm:gap-4">
                            <a href="{{ route('web.courses', ['category' => 'all', 'search' => $search]) }}"
                                class="category-filter px-4 py-3 rounded-full font-semibold {{ !$category || $category == 'all' ? 'active' : '' }}">
                                All
                            </a>
                            <a href="{{ route('web.courses', ['category' => 'scientific', 'search' => $search]) }}"
                                class="category-filter px-4 py-3 rounded-full font-semibold flex items-center {{ $category == 'scientific' ? 'active' : '' }}">
                                <i class="fas fa-flask text-[#4a86e8] mr-2"></i> Scientific
                            </a>
                            <a href="{{ route('web.courses', ['category' => 'literary', 'search' => $search]) }}"
                                class="category-filter px-4 py-3 rounded-full font-semibold flex items-center {{ $category == 'literary' ? 'active' : '' }}">
                                <i class="fas fa-book text-[#e86a4a] mr-2"></i> Literary
                            </a>
                        </div>
                    </form>
                </div>
            </section>

            @if ($featuredCourse)
                <section class="py-12 bg-[#f8f8fa]">
                    <div class="container mx-auto px-6">
                        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center">Top Rated Course</h2>

                        {{-- This PHP block prepares the featured course data for the modal --}}
                        @php
                            // We now use the calculated properties from the new query
                            $ratingValue = $featuredCourse->ratings_avg_rating ?? ($featuredCourse->rating ?? 0);
                            $ratingCount = $featuredCourse->ratings_count ?? $featuredCourse->ratings->count();

                            $featuredCourseData = [
                                'id' => $featuredCourse->id,
                                'name' => $featuredCourse->name,
                                'description' => $featuredCourse->description,
                                'image' => $featuredCourse->image
                                    ? asset($featuredCourse->image)
                                    : 'https://via.placeholder.com/800x600',
                                'teacher' => [
                                    'id' => $featuredCourse->teacher->id,
                                    'name' => $featuredCourse->teacher->name,
                                    'image' => $featuredCourse->teacher->image
                                        ? asset($featuredCourse->teacher->image)
                                        : 'https://via.placeholder.com/150',
                                ],
                                'subject' => $featuredCourse->subject
                                    ? [
                                        'id' => $featuredCourse->subject->id,
                                        'name' => $featuredCourse->subject->name,
                                    ]
                                    : null,
                                'lectures_count' => $featuredCourse->lectures->count(),
                                'subscriptions_count' => $featuredCourse->subscriptions_count,
                                'rating' => number_format($ratingValue, 1),
                            ];
                        @endphp

                        <div class="bg-white rounded-lg shadow-lg overflow-hidden lg:flex cursor-pointer"
                            @click="$dispatch('open-course-modal', { course: {{ json_encode($featuredCourseData) }} })">
                            <div class="lg:w-1/2">
                                <img class="h-64 lg:h-full w-full object-cover"
                                    src="{{ $featuredCourse->image ?? 'https://via.placeholder.com/800x600' }}"
                                    alt="Featured Course Image">
                            </div>
                            <div class="p-8 lg:w-1/2 flex flex-col justify-center">
                                <span class="featured-course-badge mb-4">Top Rated</span>
                                <h3 class="text-3xl font-bold mb-4">{{ $featuredCourse->name }}</h3>
                                <p class="text-[#68687a] mb-6">{{ Str::limit($featuredCourse->description, 150) }}</p>
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <p class="font-semibold">By {{ $featuredCourse->teacher->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $featuredCourse->subject->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if ($ratingValue > 0)
                                            <div class="flex items-center">
                                                {{-- Use the accurate rating value --}}
                                                <span
                                                    class="text-2xl font-bold text-[#b0b0cf] mr-2">{{ number_format($ratingValue, 1) }}</span>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    {{-- Use the accurate rounded rating value for stars --}}
                                                    <i
                                                        class="fas fa-star {{ $i <= round($ratingValue) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            {{-- Use the accurate rating count --}}
                                            <p class="text-sm text-gray-500">({{ $ratingCount }} ratings)</p>
                                        @else
                                            <p class="text-gray-500">Not rated yet</p>
                                        @endif
                                    </div>
                                </div>
                                <div
                                    class="bg-[#b0b0cf] text-white px-8 py-4 rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors w-full text-center">
                                    View Course Details
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <section class="py-12 bg-white">
                <div class="container mx-auto px-6">
                    <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center">All Courses</h2>
                    @if ($courses->count() > 0)
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($courses as $course)
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
        </main>

        <div x-show="showCourseModal" x-on:open-course-modal.window="openModal($event.detail.course)"
            class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showCourseModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm"
                @click="closeModal()"></div>

            <div x-show="showCourseModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col" @click.stop>
                <template x-if="currentCourse">
                    <div class="overflow-y-auto">
                        <div class="flex items-start justify-between p-5 border-b border-gray-200 rounded-t-xl">
                            <div class="flex-grow">
                                <h3 class="text-2xl font-bold text-gray-800" x-text="currentCourse.name"></h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    By <span x-text="currentCourse.teacher.name"></span> in <span class="font-semibold"
                                        x-text="currentCourse.subject ? currentCourse.subject.name : 'General'"></span>
                                </p>
                            </div>
                            <button @click="closeModal()"
                                class="p-2 text-gray-400 rounded-full hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#b0b0cf]">
                                <span class="sr-only">Close</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-6">
                            <img :src="currentCourse.image" :alt="currentCourse.name"
                                class="w-full h-56 object-cover rounded-lg">

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-[#b0b0cf] flex items-center justify-center">
                                        <i class="fas fa-star mr-2 text-yellow-400"></i>
                                        <span x-text="currentCourse.rating"></span>
                                    </div>
                                    <p class="text-sm text-gray-600">Rating</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-[#b0b0cf] flex items-center justify-center">
                                        <i class="fas fa-users mr-2"></i>
                                        <span x-text="currentCourse.subscriptions_count"></span>
                                    </div>
                                    <p class="text-sm text-gray-600">Students</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg col-span-2 sm:col-span-1">
                                    <div class="text-2xl font-bold text-[#b0b0cf] flex items-center justify-center">
                                        <i class="fas fa-video mr-2"></i>
                                        <span x-text="currentCourse.lectures_count"></span>
                                    </div>
                                    <p class="text-sm text-gray-600">Lectures</p>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-lg text-gray-800 mb-2">Description</h4>
                                <p class="text-gray-600" x-text="currentCourse.description"></p>
                            </div>
                        </div>

                        {{-- <div class="flex items-center justify-end p-5 border-t border-gray-200 rounded-b-xl">
                            <a href="#" class="bg-[#b0b0cf] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors inline-block w-full sm:w-auto text-center">
                                <i class="fas fa-book-open mr-2"></i> Start Learning
                            </a>
                        </div> --}}
                    </div>
                </template>

                <template x-if="!currentCourse">
                    <div class="p-8 text-center">
                        <p class="text-gray-500">Loading course details...</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
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
</x-web-layout>
