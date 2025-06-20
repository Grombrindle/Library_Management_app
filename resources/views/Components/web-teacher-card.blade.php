@props(['teacher'])

<div class="bg-white rounded-2xl p-6 flex flex-col professor-card">
    <div class="flex items-start">
        <div class="w-24 h-24 rounded-full bg-cover bg-center flex-shrink-0 mr-6" 
             style="background-image: url('{{ $teacher->image ?? 'https://via.placeholder.com/150' }}')">
        </div>
        <div class="flex-1">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xl font-bold text-[#202025]">{{ $teacher->name }}</h3>
                <div class="flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>
            <p class="text-[#68687a] text-sm mb-4">
                {{ $teacher->subjects->pluck('name')->join(', ') }}
            </p>
        </div>
    </div>
    <div class="mt-4 border-t border-gray-100 pt-4 flex flex-col flex-grow">
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach ($teacher->universities as $university)
                <span class="bg-[#f8f8fa] text-[#b0b0cf] px-2 py-1 rounded-full text-xs">{{ $university->name }}</span>
            @endforeach
        </div>
        <div class="flex items-center justify-between text-sm mt-auto">
            <div><span class="font-bold text-[#202025]">{{ $teacher->courses_count }}</span> Courses</div>
            <button class="px-4 py-2 bg-[#b0b0cf] text-white text-sm font-semibold rounded-lg hover:bg-[#68687a] transition-colors">
                View Profile
            </button>
        </div>
    </div>
</div> 