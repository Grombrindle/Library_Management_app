<x-web-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">My Courses</h1>
            <a href="{{ route('web.my-courses') }}" class="px-5 py-2.5 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                Browse More Courses
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)
            @php
                $courseData = [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description ?? '',
                    'image' => $course->image ? asset($course->image) : 'https://via.placeholder.com/400x300',
                    'teacher' => [
                        'id' => $course->teacher->id,
                        'name' => $course->teacher->name,
                        'image' => $course->teacher->image ? asset($course->teacher->image) : 'https://via.placeholder.com/150',
                    ],
                    'subject' => $course->subject ? [
                        'id' => $course->subject->id,
                        'name' => $course->subject->name,
                        'literaryOrScientific' => $course->subject->literaryOrScientific,
                    ] : null,
                    'lectures_count' => $course->lectures->count(),
                    'subscriptions_count' => $course->subscription_count ?? 0,
                    'rating' => $course->rating ?? 0,
                ];
            @endphp
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden transition-transform duration-300 hover:-translate-y-2 cursor-pointer"
                 @click="$dispatch('open-course-modal', { course: {{ json_encode($courseData) }} })">
                <div class="block">
                    <img src="{{ $course->image ? asset($course->image) : 'https://via.placeholder.com/400x300' }}" 
                         alt="{{ $course->name }}" class="w-full h-48 object-cover">
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-sm text-[#b0b0cf] font-semibold">{{ $course->subject->name ?? 'Category' }}</span>
                            <h3 class="text-lg font-bold mt-1 text-[#202025]">{{ $course->name }}</h3>
                        </div>
                        @if(($course->rating ?? 0) > 0)
                        <div class="flex items-center text-yellow-400">
                            <i class="fas fa-star"></i>
                            <span class="ml-1 text-[#68687a] text-sm font-bold">{{ number_format($course->rating, 1) }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <div class="flex items-center">
                            <img src="{{ $course->teacher->image ? asset($course->teacher->image) : 'https://via.placeholder.com/150' }}" 
                                 alt="{{ $course->teacher->name }}" class="w-8 h-8 rounded-full object-cover mr-3">
                            <span class="text-sm text-[#68687a]">{{ $course->teacher->name }}</span>
                        </div>
                        <div class="text-right">
                            @if($course->subject && $course->subject->literaryOrScientific)
                                <span class="scientific-badge text-xs font-bold px-3 py-1 rounded-full">SCIENTIFIC</span>
                            @else
                                <span class="literary-badge text-xs font-bold px-3 py-1 rounded-full">LITERARY</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3">
                <div class="bg-white rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 flex items-center justify-center rounded-full bg-[#d8d8e7] mx-auto mb-4">
                        <i class="fas fa-book-open text-3xl text-[#b0b0cf]"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">No Courses Yet</h3>
                    <p class="text-gray-500 mb-6">Enroll in courses to start learning</p>
                    <a href="{{ route('web.my-courses') }}" class="px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                        Browse Courses
                    </a>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($courses->hasPages())
        <div class="mt-8">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</x-web-layout>



{{-- 
<x-web-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">My Courses</h1>
            <a href="{{ route('web.my-courses') }}" class="px-5 py-2.5 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                Browse More Courses
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="relative">
                    <img src="{{ $course->image ? asset('storage/'.$course->image) : 'https://via.placeholder.com/400x200' }}" 
                         alt="{{ $course->name }}" class="w-full h-48 object-cover">
                    <div class="absolute top-4 right-4 px-3 py-1 bg-[#b0b0cf] text-white text-sm font-bold rounded-full">
                        {{ $course->pivot->is_finished ? 'Completed' : 'In Progress' }}
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold">{{ $course->name }}</h3>
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded">
                            {{ $course->lectures->count() }} lectures
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-full bg-cover bg-center" 
                             style="background-image: url('{{ $course->teacher->image ? asset('storage/'.$course->teacher->image) : 'https://via.placeholder.com/150' }}')">
                        </div>
                        <p class="text-gray-600">{{ $course->teacher->name }}</p>
                    </div>
                    
                    <div class="mb-4">
                        @php
                            $completedLectures = Auth::user()->lectures()->where('course_id', $course->id)->count();
                            $progress = $course->lectures->count() > 0 ? round(($completedLectures / $course->lectures->count()) * 100) : 0;
                        @endphp
                        <div class="flex justify-between text-sm mb-1">
                            <span>Progress</span>
                            <span>{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-[#b0b0cf] h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    
                    <a href="{{ route('web.my-courses', $course) }}" class="block w-full text-center px-4 py-3 bg-gray-100 text-[#b0b0cf] font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                        Continue Learning
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3">
                <div class="bg-white rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 flex items-center justify-center rounded-full bg-[#d8d8e7] mx-auto mb-4">
                        <i class="fas fa-book-open text-3xl text-[#b0b0cf]"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">No Courses Yet</h3>
                    <p class="text-gray-500 mb-6">Enroll in courses to start learning</p>
                    <a href="{{ route('web.my-courses') }}" class="px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                        Browse Courses
                    </a>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($courses->hasPages())
        <div class="mt-8">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</x-web-layout>
--}}
