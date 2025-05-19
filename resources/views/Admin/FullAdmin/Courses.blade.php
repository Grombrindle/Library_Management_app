@props(['num' => App\Models\Course::count()])

@php
    session([
        'breadcrumb_courses' => array_merge(['Home' => url('/welcome')], [__('messages.courses') => Request::url()]),
    ]);

    // Get the search query and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedSubjects = request('subjects', []);
    $filterNone = request('none', false);
    $subjectCounts = request('subject_count', []);

    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    $modelToPass = App\Models\Course::query();

    // Apply search, filters, and sorting
    $modelToPass = $modelToPass
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
                });
            }
            return $query;
        })
        ->when($selectedSubjects || $filterNone, function ($query) use ($selectedSubjects, $filterNone) {
            $query->where(function ($q) use ($selectedSubjects, $filterNone) {
                if ($filterNone) {
                    $q->doesntHave('subjects');
                }
                if ($selectedSubjects) {
                    if ($filterNone) {
                        $q->orWhereHas('subjects', function ($q) use ($selectedSubjects) {
                            $q->whereIn('subjects.id', $selectedSubjects);
                        });
                    } else {
                        $q->whereHas('subjects', function ($q) use ($selectedSubjects) {
                            $q->whereIn('subjects.id', $selectedSubjects);
                        });
                    }
                }
            });
        })
        ->when($subjectCounts, function ($query) use ($subjectCounts) {
            $query->where(function ($q) use ($subjectCounts) {
                foreach ($subjectCounts as $count) {
                    if ($count === '1') {
                        $q->orHas('subjects', '=', 1);
                    } elseif ($count === '2-3') {
                        $q->orWhereHas('subjects', function ($q) {
                            $q->groupBy('course_id')->havingRaw('COUNT(subjects.id) BETWEEN 2 AND 3');
                        });
                    } elseif ($count === '4-5') {
                        $q->orWhereHas('subjects', function ($q) {
                            $q->groupBy('course_id')->havingRaw('COUNT(subjects.id) BETWEEN 4 AND 5');
                        });
                    } elseif ($count === '6+') {
                        $q->orWhereHas('subjects', function ($q) {
                            $q->groupBy('course_id')->havingRaw('COUNT(subjects.id) >= 6');
                        });
                    }
                }
            });
        })
        ->when($sort, function ($query) use ($sort) {
            if ($sort === 'name-a-z') {
                $query->orderByRaw('LOWER(name) ASC'); // Sort by name A-Z (case-insensitive)
            } elseif ($sort === 'name-z-a') {
                $query->orderByRaw('LOWER(name) DESC'); // Sort by name Z-A (case-insensitive)
            } elseif ($sort === 'newest') {
                $query->orderBy('created_at', 'desc'); // Sort by creation date (newest)
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc'); // Sort by creation date (oldest)
            }
        })
        ->paginate(10);

    $chunkSize = 2;
    $chunkedUniversities = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedUniversities[$i] = [];
    }

    foreach ($modelToPass as $index => $course) {
        $chunkIndex = $index % $chunkSize;
        $chunkedUniversities[$chunkIndex][] = $course;
    }
@endphp

<x-layout :objects=true object="{{ __('messages.courses') }}">
    <x-breadcrumb :links="array_merge([__('messages.home') => url('/welcome')], [__('messages.courses') => Request::url()])" />

    <x-cardcontainer :model=$modelToPass addLink="addcourse" :showNameSort=true
        num="{{ App\Models\Course::count() }}">
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row">
            @foreach ($chunkedUniversities as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $course)
                        <x-card link="course/{{ $course->id }}" image="{{ asset($course->image) }}"
                            object="Course">
                            ● {{ __('messages.courseName') }}: {{ $course->name }}<br>
                            ● {{ __('messages.forSubject') }}: {{ $course->subject->name }}<br>
                            ● {{ __('messages.fromTeacher') }}: {{ $course->teacher->name }}<br>
                            ● {{ __('messages.lecturesNum') }}: {{ $course->lectures->count() }}<br>
                            ● {{ __('messages.usersSubTo') }}: {{ $course->users->count() }}<br>
                        </x-card>
                    @endforeach
                </div>
            @endforeach
        </div>
    </x-cardcontainer>


    @if ($modelToPass->total() > 1)
        {{-- Only show if more than 1 result --}}
        <div class="pagination-info" style="text-align: center; margin-bottom: 2%; font-size: 24px; color: var(--text-color);">
            {{ __('messages.showingItems', [
                'from' => $modelToPass->firstItem(),
                'to' => $modelToPass->lastItem(),
                'total' => $modelToPass->total(),
                'items' => __('messages.universities')
            ]) }}
        </div>
    @else
        <div class="pagination-info" style="display: none;"></div> {{-- Hidden container --}}
    @endif

    <div class="pagination" style="@if ($num <= 10) display:none; @endif">
        {{ $modelToPass->appends([
    'search' => $searchQuery,
    'sort' => $sort,
    'subjects' => $selectedSubjects,
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