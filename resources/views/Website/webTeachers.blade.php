<x-web-layout>
    <div x-data="teachersPage()">
        <main>
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-br from-[#b0b0cf] to-[#d8d8e7] text-white py-24">
                <div class="container mx-auto px-6">
                    <div class="max-w-3xl text-center md:text-left">
                        <h1 class="text-4xl md:text-5xl font-bold mb-6 text-[#202025]">Meet Our Expert Teachers</h1>
                        <p class="text-xl mb-8 opacity-90 text-[#202025]">Learn from experienced professionals who are passionate about education.</p>
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
                        @foreach(App\Models\Teacher::withCount('favoritedByUsers')->get() as $teacher)
                            <x-web-teacher-card :teacher="$teacher" />
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ App\Models\Teacher::withCount('favoritedByUsers')->paginate(12)->appends(request()->query())->links() }}
                    </div>
                </div>
            </section>

            @if(isset($featuredTeacher))
            <section class="py-20 bg-white">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold mb-4">Featured Teacher of the Month</h2>
                        <p class="text-[#68687a] max-w-2xl mx-auto">Highlighting our most popular educator making a significant impact.</p>
                    </div>

                    @php
                        $featuredTeacherData = [
                            'id' => $featuredTeacher->id,
                            'name' => $featuredTeacher->name,
                            'image' => $featuredTeacher->image ? asset($featuredTeacher->image) : 'https://via.placeholder.com/300',
                            'subjects' => $featuredTeacher->subjects->pluck('name')->join(', '),
                            'universities' => $featuredTeacher->universities->pluck('name')->join(', '),
                            'courses_count' => $featuredTeacher->courses_count,
                            'favorites_count' => $featuredTeacher->favorited_by_users_count,
                            'description' => "A distinguished professor at " . ($featuredTeacher->universities->first()->name ?? 'MindSpark University') . " with expertise in multiple fields, contributing significantly to our academic community."
                        ];
                    @endphp

                    <div class="bg-[#f8f8fa] rounded-2xl max-w-4xl mx-auto md:flex items-center transition-transform duration-300 hover:shadow-xl hover:-translate-y-2">
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
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-[#b0b0cf]">{{ $featuredTeacher->favorited_by_users_count ?? 0 }}</div>
                                    <div class="text-sm text-[#68687a]">Favorites</div>
                                </div>
                                <div class="ml-auto px-6 py-3 bg-[#b0b0cf] text-white font-semibold rounded-lg hover:bg-[#8a8aac] transition-colors cursor-pointer"
                                     @click="$dispatch('open-teacher-modal', { teacher: {!! json_encode($featuredTeacherData) !!} })">
                                    View Full Profile
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endif

            <!-- CTA Section -->
            <section class="py-20 bg-gradient-to-br from-[#b0b0cf] to-[#8a8aac] text-white">
                <div class="container mx-auto px-6 text-center">
                    <h2 class="text-4xl font-bold mb-6">Join Our Teaching Community</h2>
                    <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">Share your knowledge and expertise with students around the world.</p>
                    <a href="#" class="bg-white text-[#b0b0cf] px-8 py-3 rounded-lg font-semibold hover:bg-[#f8f8fa] transition-colors inline-block">
                        Become a Teacher
                    </a>
                </div>
            </section>

            <!-- Teacher Modal -->
            <div
                x-show="showTeacherModal"
                x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                style="display: none;"
                @keydown.escape.window="closeTeacherModal()"
            >
                <div class="bg-white rounded-2xl w-full max-w-2xl p-8 relative" @click.away="closeTeacherModal()">
                    <button @click="closeTeacherModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">
                        &times;
                    </button>
                    <template x-if="currentTeacher">
                        <div>
                            <div class="flex flex-col md:flex-row gap-8 items-center">
                                <img :src="currentTeacher.image" :alt="currentTeacher.name" class="w-36 h-36 rounded-full object-cover border-4 border-[#b0b0cf]">
                                <div>
                                    <h3 class="text-3xl font-bold mb-2" x-text="currentTeacher.name"></h3>
                                    <p class="text-[#b0b0cf] font-semibold mb-2" x-text="currentTeacher.subjects"></p>
                                    <p class="text-gray-600 mb-4" x-text="currentTeacher.universities"></p>
                                    <p class="text-gray-700 mb-4" x-text="currentTeacher.description"></p>
                                    <div class="flex gap-8">
                                        <div>
                                            <div class="text-xl font-bold text-[#b0b0cf]" x-text="currentTeacher.courses_count"></div>
                                            <div class="text-sm text-[#68687a]">Courses</div>
                                        </div>
                                        <div>
                                            <div class="text-xl font-bold text-[#b0b0cf]" x-text="currentTeacher.favorites_count"></div>
                                            <div class="text-sm text-[#68687a]">Favorites</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <!-- End Teacher Modal -->

        </main>
    </div>
    <script>
    document.addEventListener('alpine:init', () => {
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
</x-web-layout>
