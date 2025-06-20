@props(['course'])

@php
    $courseData = [
        'id' => $course->id,
        'name' => $course->name,
        'description' => $course->description,
        'image' => $course->image ? asset($course->image) : 'https://via.placeholder.com/400x300',
        'teacher' => [
            'id' => $course->teacher->id,
            'name' => $course->teacher->name,
            'image' => $course->teacher->image ? asset( $course->teacher->image) : 'https://via.placeholder.com/150',
        ],
        'subject' => $course->subject ? [
            'id' => $course->subject->id,
            'name' => $course->subject->name,
            'literaryOrScientific' => $course->subject->literaryOrScientific,
        ] : null,
        'lectures_count' => $course->lectures->count(),
        'subscriptions_count' => $course->subscription_count,
        'rating' => $course->rating,
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
                <a class="text-sm text-[#b0b0cf] font-semibold">{{ $course->subject->name ?? 'Category' }}</a>
                <h3 class="text-lg font-bold mt-1 text-[#202025]">{{ $course->name }}</h3>
            </div>
            @if($course->rating > 0)
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
