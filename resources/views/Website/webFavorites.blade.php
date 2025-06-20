<x-web-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-8">My Favorites</h1>
        
        <!-- Favorite Courses -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Favorite Courses</h2>
            </div>
            
            @if($favoriteCourses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favoriteCourses as $course)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <img src="{{ $course->image ? asset('storage/'.$course->image) : 'https://via.placeholder.com/400x200' }}" 
                         alt="{{ $course->name }}" class="w-full h-40 object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold">{{ $course->name }}</h3>
                            <button class="text-red-500 hover:text-red-700 favorite-toggle" data-id="{{ $course->id }}" data-type="course">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">{{ $course->teacher->name }}</p>
                        <a href="{{ route('web.my-courses', $course) }}" class="block w-full text-center px-4 py-2.5 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                            View Course
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-2xl p-8 text-center">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-[#d8d8e7] mx-auto mb-4">
                    <i class="fas fa-star text-2xl text-[#b0b0cf]"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">No Favorite Courses</h3>
                <p class="text-gray-500 mb-4">Add courses to your favorites</p>
                <a href="{{ route('web.courses') }}" class="px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                    Browse Courses
                </a>
            </div>
            @endif
        </div>
        
        <!-- Favorite Teachers -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Favorite Teachers</h2>
            </div>
            
            @if($favoriteTeachers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($favoriteTeachers as $teacher)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden text-center p-6">
                    <div class="w-24 h-24 rounded-full bg-cover bg-center mx-auto mb-4 border-4 border-[#b0b0cf]"
                         style="background-image: url('{{ $teacher->image ? asset('storage/'.$teacher->image) : 'https://via.placeholder.com/150' }}')">
                    </div>
                    <h3 class="text-lg font-bold">{{ $teacher->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">
                        {{ $teacher->first_subject_name }}
                    </p>
                    <p class="text-gray-600 text-sm mb-4">{{ $teacher->subject->name ?? 'General' }}</p>
                    <div class="flex justify-center gap-3">
                        <button class="text-red-500 hover:text-red-700 favorite-toggle" data-id="{{ $teacher->id }}" data-type="teacher">
                            <i class="fas fa-heart"></i>
                        </button>
                        <a href="{{ route('web.teachers', $teacher) }}" class="text-[#b0b0cf] hover:text-[#8a8aac]">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-2xl p-8 text-center">
                <div class="w-16 h-16 flex items-center justify-center rounded-full bg-[#d8d8e7] mx-auto mb-4">
                    <i class="fas fa-chalkboard-teacher text-2xl text-[#b0b0cf]"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">No Favorite Teachers</h3>
                <p class="text-gray-500 mb-4">Add teachers to your favorites</p>
                <a href="{{ route('web.teachers') }}" class="px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors">
                    Browse Teachers
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <script>
        document.querySelectorAll('.favorite-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const itemType = this.dataset.type;
                const route = `/favorites/toggle/${itemType}/${itemId}`;
                
                fetch(route, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        this.closest('.grid > div, .flex > div').remove();
                    }
                });
            });
        });
    </script>
</x-web-layout>