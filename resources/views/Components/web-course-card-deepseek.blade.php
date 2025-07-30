@props(['course'])

<div class="course-card bg-white rounded-2xl overflow-hidden flex flex-col">
    <div class="relative">
        <img class="aspect-video w-full object-cover" src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}">
        
        <div class="absolute top-4 right-4">
            @if($course->subject->literaryOrScientific)
                <span class="scientific-badge text-xs font-bold px-3 py-1 rounded-full">SCIENTIFIC</span>
            @else
                <span class="literary-badge text-xs font-bold px-3 py-1 rounded-full">LITERARY</span>
            @endif
        </div>

        @if($course->rating > 0)
        <div class="absolute bottom-4 left-4 bg-white/80 backdrop-blur-sm rounded-lg px-3 py-1 flex items-center text-gray-800">
             <i class="fas fa-star text-yellow-400"></i>
             <span class="ml-1 font-bold text-sm">{{ number_format($course->rating, 1) }}</span>
        </div>
        @endif
    </div>
    <div class="p-6 flex flex-col flex-grow">
        <h3 class="text-xl font-bold text-[#120e1b] mb-3">{{ $course->name }}</h3>
        <p class="text-gray-600 mb-4 text-sm">{{ Str::limit($course->description, 100) }}</p>

        <div class="mt-auto">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <img class="w-8 h-8 rounded-full object-cover mr-2" src="{{ asset('storage/' . $course->teacher->image) }}" alt="{{$course->teacher->name}}">
                    <span class="text-sm font-medium">{{ $course->teacher->name ?? 'N/A' }}</span>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="far fa-user mr-1"></i> {{ $course->subscriptions }}
                </div>
            </div>

            <a href="#" class="w-full mt-4 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors flex items-center justify-center">
                View Course
            </a>
        </div>
    </div>
</div> 