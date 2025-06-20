@props(['course'])

<div class="bg-white rounded-2xl shadow-sm overflow-hidden transition-transform duration-300 hover:-translate-y-2">
    <a href="#" class="block">
        <img src="{{ $course->image }}" alt="{{ $course->name }}" class="w-full h-48 object-cover">
    </a>
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <a href="#" class="text-sm text-[#b0b0cf] font-semibold">{{ $course->subject->name ?? 'Category' }}</a>
                <h3 class="text-lg font-bold mt-1">
                    <a href="#" class="text-[#202025] hover:text-[#b0b0cf]">{{ $course->name }}</a>
                </h3>
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
                <img src="{{ $course->teacher->image }}" alt="{{ $course->teacher->name ?? 'Teacher' }}" class="w-8 h-8 rounded-full object-cover mr-3">
                <span class="text-sm text-[#68687a]">{{ $course->teacher->name ?? 'N/A' }}</span>
            </div>
            <div class="text-right">
                @if($course->subject->literaryOrScientific)
                    <span class="scientific-badge text-xs font-bold px-3 py-1 rounded-full">SCIENTIFIC</span>
                @else
                    <span class="literary-badge text-xs font-bold px-3 py-1 rounded-full">LITERARY</span>
                @endif
            </div>
        </div>
    </div>
</div> 