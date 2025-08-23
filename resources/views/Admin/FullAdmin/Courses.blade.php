@props(['num' => App\Models\Course::count(), 'courses' => null,'subjectID' => null])

@php
    // Get the search query and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedCourses = request('courses', []);
    $filterNone = request('none', false);
    $courseCounts = request('course_count', []);

    // Normalize the search query
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    // Initialize base query
    if ($courses !== null) {
        $query = App\Models\Course::whereIn('id', $courses->pluck('id'));
        $coursesCount = $query->count(); // Get count before pagination
    } else {
        $query = App\Models\Course::query();
        $coursesCount = $query->count(); // Get total count
    }

    // Apply filters
    $query
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])->orWhereRaw('LOWER(description) LIKE ?', [
                        "%{$term}%",
                    ]);
                });
            }
        })
        ->when($selectedCourses, function ($query) use ($selectedCourses) {
            $query->whereHas('course', function ($q) use ($selectedCourses) {
                $q->whereIn('id', $selectedCourses);
            });
        })
        ->when($filterNone, function ($query) {
            $query->whereDoesntHave('course');
        })
        ->when($courseCounts, function ($query) use ($courseCounts) {
            $query->where(function ($q) use ($courseCounts) {
                foreach ($courseCounts as $count) {
                    if ($count === '1') {
                        $q->orHas('course', '=', 1);
                    } // Add other count conditions as needed
                }
            });
        })
        ->when($sort, function ($query) use ($sort) {
            if ($sort === 'name-a-z') {
                $query->orderByRaw('LOWER(name) ASC');
            } elseif ($sort === 'name-z-a') {
                $query->orderByRaw('LOWER(name) DESC');
            } elseif ($sort === 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'rating-highest') {
                $query->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating');
            } elseif ($sort === 'rating-lowest') {
                $query->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating', 'asc');
            }
        });

    // Get filtered count before pagination
    $filteredCount = $query->count();
    $modelToPass = $query->paginate(10);

    // Prepare filter options
    $filterOptions = App\Models\Course::pluck('name', 'id')->toArray();

    // Split course into chunks
    $chunkSize = 2;
    $chunkedCourses = array_fill(0, $chunkSize, []);

    foreach ($modelToPass as $index => $lecture) {
        $chunkIndex = $index % $chunkSize;
        $chunkedCourses[$chunkIndex][] = $lecture;
    }
@endphp

<x-layout :objects=true object="{{ $courses ? __('messages.coursesFor').' '.App\Models\Subject::findOrFail($subjectID)->name : __('messages.courses')}}">
    <x-breadcrumb :links="array_merge([__('messages.home') => url('/welcome')], [__('messages.courses') => Request::url()])" />

    <x-cardcontainer :model=$modelToPass addLink="addcourse" :showNameSort=true num="{{ App\Models\Course::count() }}">
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedCourses as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $course)
                        <x-card link="course/{{ $course->id }}" image="{{ asset($course->image) }}" object="Course">
                            <!-- Price Display -->
                            <div style="position: absolute; top: 10px; {{ app()->getLocale() === 'ar' ? 'left: 10px;' : 'right: 10px;' }} background: rgba(0, 0, 0, 0.7); color: white; padding: 5px 10px; border-radius: 15px; font-weight: bold; font-size: 14px; z-index: 10;">
                                ${{ number_format($course->price ?? 0, 2) }}
                            </div>

                            ● {{ __('messages.courseName') }}: {{ $course->name }}<br>
                            ● {{ __('messages.forSubject') }}: {{ $course->subject->name }}<br>
                            ● {{ __('messages.description') }}: {{ $course->description }}<br>
                            ● {{ __('messages.fromTeacher') }}: {{ $course->teacher->name }}<br>
                            ● {{ __('messages.lecturesNum') }}: {{ $course->lectures->count() }}<br>
                            ● {{ __('messages.usersSubTo') }}: {{ $course->users->count() }}<br>
                            ● {{ __('messages.isPurchasaeble') }}: {{ $course->sparkies ? 'Yes' : 'No' }}<br>
                            ● {{ __('messages.requirements') }}:
                            [{{ $course->requirements ?: 'No requirements specified' }}]
                            <br>
                            <div style="display:inline-block; vertical-align:middle;">
                                @php
                                    $rating = $course->rating ?? 0;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($rating >= $i)
                                        {{-- Full star --}}
                                        <svg width="20" height="20" fill="gold" viewBox="0 0 20 20" style="display:inline;"><polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/></svg>
                                    @elseif ($rating >= $i - 0.5)
                                        {{-- Half star --}}
                                        <svg width="20" height="20" viewBox="0 0 20 20" style="display:inline;">
                                            <defs>
                                                <linearGradient id="half-grad-{{ $course->id }}-{{ $i }}">
                                                    <stop offset="50%" stop-color="gold"/>
                                                    <stop offset="50%" stop-color="lightgray"/>
                                                </linearGradient>
                                            </defs>
                                            <polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" fill="url(#half-grad-{{ $course->id }}-{{ $i }})"/>
                                        </svg>
                                    @else
                                        {{-- Empty star --}}
                                        <svg width="20" height="20" fill="lightgray" viewBox="0 0 20 20" style="display:inline;"><polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/></svg>
                                    @endif
                                @endfor
                                <span>({{ number_format($rating, 1) }})</span>
                                <span>({{ $course->ratings->count() }} reviews)</span>

                            </div>
                        </x-card>
                    @endforeach
                </div>
            @endforeach
        </div>
    </x-cardcontainer>


    @if ($modelToPass->total() > 1)
        {{-- Only show if more than 1 result --}}
        <div class="pagination-info"
            style="text-align: center; margin-bottom: 2%; font-size: 24px; color: var(--text-color);">
            {{ __('messages.showingItems', [
            'from' => $modelToPass->firstItem(),
            'to' => $modelToPass->lastItem(),
            'total' => $modelToPass->total(),
            'items' => __('messages.courses')
        ]) }}
        </div>
    @else
        <div class="pagination-info" style="display: none;"></div> {{-- Hidden container --}}
    @endif

    <div class="pagination" style="@if ($num <= 10) display:none; @endif">
        {{ $modelToPass->appends([
    'search' => $searchQuery,
    'sort' => $sort,
    //'subjects' => $selectedSubjects,
    'none' => $filterNone,
    'subject_count' => request('subject_count', []),
])->links() }}
    </div>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchBar = document.querySelector('.search-bar');
        const dynamicContent = document.getElementById('dynamic-content');

        searchBar.addEventListener('input', function () {
            const query = searchBar.value;

            // Get current filter values
            const selectedSort = document.querySelector('input[name="sort"]:checked')?.value ||
                'newest';
            const selectedSubjects = Array.from(document.querySelectorAll(
                'input[name="subjects[]"]:checked')).map(el => el.value);
            const filterNone = document.getElementById('filter-none')?.checked || false;
            const subjectCounts = Array.from(document.querySelectorAll(
                'input[name="subject_count[]"]:checked')).map(el => el.value);

            // Build the query string
            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);
            selectedSubjects.forEach(subject => params.append('subjects[]', subject));
            if (filterNone) {
                params.set('none', 'true');
            }
            subjectCounts.forEach(count => params.append('subject_count[]', count));

            // Fetch results via AJAX
            fetch(`{{ request()->url() }}?${params.toString()}`)
                .then(response => response.text())
                .then(data => {
                    // Parse the response and extract the dynamic content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newContent = doc.getElementById('dynamic-content').innerHTML;

                    // Update the dynamic content without changing the structure
                    dynamicContent.innerHTML = newContent;

                    attachCircleEffect();
                    refreshAnimations();

                    if (@json($modelToPass->total()) > 1) {
                        const paginationInfo = doc.querySelector('.pagination-info');
                        const paginationInfoContainer = document.querySelector('.pagination-info');
                        if (paginationInfo) {
                            paginationInfoContainer.innerHTML = paginationInfo.innerHTML;
                        } else {
                            paginationInfoContainer.innerHTML = '';
                        }
                    }

                    // Update pagination links conditionally
                    const pagination = doc.querySelector('.pagination');
                    const paginationContainer = document.querySelector('.pagination');
                    if (pagination) {
                        paginationContainer.innerHTML = pagination.innerHTML;
                    } else {
                        paginationContainer.innerHTML = '';
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
        });
    });
</script>
