<!-- Fixed Teacher Card Component -->
@props(['teacher'])

@php
    $teacherData = [
        'id' => $teacher->id,
        'name' => $teacher->name,
        'image' => $teacher->image ? asset($teacher->image) : 'https://via.placeholder.com/300',
        'subjects' => $teacher->subjects->pluck('name')->join(', '),
        'universities' => $teacher->universities->pluck('name')->join(', '),
        'courses_count' => $teacher->courses_count,
        'favorites_count' => $teacher->favorited_by_users_count ?? 0,
        'description' => "A distinguished professor at " . ($teacher->universities->first()->name ?? 'MindSpark University') . " with expertise in multiple fields, contributing significantly to our academic community."
    ];
@endphp

<div class="bg-white rounded-2xl p-6 flex flex-col professor-card transition-transform duration-300 hover:-translate-y-2 cursor-pointer hover:shadow-lg"
     @click="
        currentTeacher = {{ json_encode($teacherData) }};
        showTeacherModal = true;
     ">
    <div class="flex items-start">
        <div class="w-24 h-24 rounded-full bg-cover bg-center flex-shrink-0 mr-6 border-2 border-gray-100"
             style="background-image: url('{{ $teacher->image ? asset($teacher->image) : 'https://via.placeholder.com/150' }}')">
        </div>
        <div class="flex-1">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xl font-bold text-[#202025]">{{ $teacher->name }}</h3>
                <div class="flex items-center text-sm text-pink-500">
                    <i class="fas fa-heart mr-1"></i>
                    <span class="font-bold">{{ $teacher->favorited_by_users_count ?? 0 }}</span>
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
            <div class="px-4 py-2 bg-[#b0b0cf] text-white text-sm font-semibold rounded-lg hover:bg-[#68687a] transition-colors pointer-events-none">
                View Profile
            </div>
        </div>
    </div>
</div>

<!-- Updated Layout Template with Fixed Modal -->
<!-- Replace the teacher modal section in your layout with this: -->

<div x-data="{ showTeacherModal: false, currentTeacher: null }"
     x-show="showTeacherModal"
     @keydown.escape.window="showTeacherModal = false"
     class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl"
         @click.away="showTeacherModal = false"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h2 class="text-2xl font-bold text-[#202025]">Teacher Profile</h2>
            <button @click="showTeacherModal = false" 
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-full">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6" x-show="currentTeacher">
            <template x-if="currentTeacher">
                <div>
                    <!-- Teacher Image and Basic Info -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                        <img :src="currentTeacher.image" 
                             :alt="currentTeacher.name" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-[#b0b0cf] shadow-lg">
                        
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-2xl font-bold text-[#202025] mb-2" x-text="currentTeacher.name"></h3>
                            <p class="text-[#b0b0cf] font-semibold mb-3" x-text="currentTeacher.subjects"></p>
                            <p class="text-gray-600 leading-relaxed" x-text="currentTeacher.description"></p>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-4 bg-gradient-to-br from-[#b0b0cf] to-[#8a8aac] text-white rounded-xl">
                            <div class="text-3xl font-bold mb-1" x-text="currentTeacher.courses_count"></div>
                            <p class="text-sm opacity-90">Courses</p>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-pink-400 to-pink-500 text-white rounded-xl">
                            <div class="text-3xl font-bold mb-1" x-text="currentTeacher.favorites_count"></div>
                            <p class="text-sm opacity-90">Favorites</p>
                        </div>
                    </div>

                    <!-- Universities -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <h4 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-university text-[#b0b0cf] mr-2"></i>
                            Universities
                        </h4>
                        <p class="text-gray-600 text-sm" x-text="currentTeacher.universities || 'Not specified'"></p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 justify-end">
                        <button @click="showTeacherModal = false" 
                                class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                        <button class="px-6 py-2 bg-[#b0b0cf] text-white rounded-lg font-semibold hover:bg-[#8a8aac] transition-colors">
                            <i class="fas fa-heart mr-2"></i>Add to Favorites
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- Updated page-level Alpine.js data for teachers page -->
<script>
document.addEventListener('alpine:init', () => {
    // Make sure the modal data is available at the page level
    Alpine.data('teachersPage', () => ({
        showTeacherModal: false,
        currentTeacher: null,
        
        openTeacherModal(teacher) {
            this.currentTeacher = teacher;
            this.showTeacherModal = true;
            document.body.classList.add('overflow-hidden');
        },
        
        closeTeacherModal() {
            this.showTeacherModal = false;
            this.currentTeacher = null;
            document.body.classList.remove('overflow-hidden');
        }
    }));
});
</script>

<!-- Updated Teachers Page Wrapper -->
<!-- Wrap your teachers page content with this: -->
{{-- <div x-data="teachersPage()">
    <!-- Your existing hero section, search, etc. -->
    
    <!-- Teachers Grid Section -->
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
    
    <!-- Teacher Modal (place this at the end of the page, before closing div) -->
    <!-- Include the modal HTML from above here -->
</div> --}}