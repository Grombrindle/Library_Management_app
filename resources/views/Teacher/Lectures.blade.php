@props(['lectures' => null, 'lec' => false, 'user' => false, 'teacher' => App\Models\Teacher::findOrFail(Auth::user()->teacher_id)])

@php
    // Get the search query and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedSubjects = request('subjects', []);

    // Normalize the search query by converting to lowercase and splitting into individual terms
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    // If $lectures is provided, use it directly; otherwise, fetch lectures based on search and filters
    if ($lectures !== null) {
        // Convert the $lectures collection to a query builder
        $query = App\Models\Lecture::whereIn('id', $lectures->pluck('id'));
    } else {
        // Fetch all lectures based on search and filters
        $query = App\Models\Lecture::whereHas('course.teacher', function ($query) {
            $query->where('teachers.id', Auth::user()->teacher_id); // Filter by the current teacher
        });
    }

    // Apply search, filters, and sorting
    $modelToPass = $query
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])->orWhereRaw('LOWER(description) LIKE ?', [
                        "%{$term}%",
                    ]);
                });
            }
            return $query;
        })
        ->when($selectedSubjects, function ($query) use ($selectedSubjects) {
            $query->whereHas('course', function ($q) use ($selectedSubjects) {
                $q->whereIn('subject_id', $selectedSubjects);
            });
        })
        ->when($sort, function ($query) use ($sort) {
            if ($sort === 'name-a-z') {
                $query->orderByRaw('LOWER(name) ASC'); // Sort by name A-Z (case-insensitive)
            } elseif ($sort === 'name-z-a') {
                $query->orderByRaw('LOWER(name) DESC'); // Sort by name Z-A (case-insensitive)
            } elseif ($sort === 'newest') {
                // Prefer created_at, fall back to id when timestamps are missing
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
            } elseif ($sort === 'rating-highest') {
                $query->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating');
            } elseif ($sort === 'rating-lowest') {
                $query->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating', 'asc');
            }
        })
        ->paginate(10);

    // Prepare filter options
    $filterOptions = [];
    foreach ($teacher->subjects as $subject) {
        $type = $subject->literaryOrScientific == 0 ? __('messages.literary') : __('messages.scientific');
        $filterOptions[$subject->id] = $subject->name . ' (' . $type . ')';
    }

    // Split lectures into chunks
    $chunkSize = 2;
    $chunkedLectures = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedLectures[$i] = [];
    }

    foreach ($modelToPass as $index => $lecture) {
        $chunkIndex = $index % $chunkSize;
        $chunkedLectures[$chunkIndex][] = $lecture;
    }

@endphp

<x-layout :objects=true
    object="{{!$lec ? __('messages.yourLectures') : __('messages.lecturesFrom') . ' ' . Str::upper(App\Models\Subject::findOrFail(session('subject'))->name) }}">
    <x-breadcrumb :links="array_merge([__('messages.home') => url('/welcome'), !$lec ? __('messages.yourLectures') : __('messages.lecturesFrom') . ' ' . App\Models\Subject::findOrFail(session('subject'))->name => Request::url()])" />

    <x-cardcontainer :model=$modelToPass addLink="addlecture" :filterOptions=$filterOptions
        :showSubjectCountFilter=false :showUsernameSort=false :showNameSort=false models="Lectures" :showRatingFilter=true>
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedLectures as $chunk)
                    <div class="chunk">
                        @foreach ($chunk as $lecture)
                                    <x-card link="lecture/{{ $lecture->id }}" image="{{ asset($lecture->image) }}" object="Lecture">
                                        ● {{ __('messages.lectureName') }}: {{ $lecture->name }}<br>
                                        ● {{ __('messages.lectureDescription') }}: {{ $lecture->description }}<br>
                                        ● {{__('messages.fromCourse')}}: {{ $lecture->course->name }} <br>
                                        ● {{__('messages.fileType')}}: @if ($lecture->type)
                                            {{__('messages.video')}} <br>
                                            ● {{__('messages.duration')}}: {{ $lecture->getVideoLength() ?? 'N/A' }}
                                        @else
                                            {{__('messages.pdf')}} <br>
                                            ● {{__('messages.pages')}}: {{ $lecture->getPdfPages() ?? 'N/A' }}
                                        @endif
                                        <br>
                                        <br>
                                        <div style="display:inline-block; vertical-align:middle;">
                                            @php
                                                $rating = $lecture->rating ?? 0;
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($rating >= $i)
                                                    {{-- Full star --}}
                                                    <svg width="20" height="20" fill="gold" viewBox="0 0 20 20" style="display:inline;">
                                                        <polygon
                                                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" />
                                                    </svg>
                                                @elseif ($rating >= $i - 0.5)
                                                    {{-- Half star --}}
                                                    <svg width="20" height="20" viewBox="0 0 20 20" style="display:inline;">
                                                        <defs>
                                                            <linearGradient id="half-grad-{{ $lecture->id }}-{{ $i }}">
                                                                <stop offset="50%" stop-color="gold" />
                                                                <stop offset="50%" stop-color="lightgray" />
                                                            </linearGradient>
                                                        </defs>
                                                        <polygon
                                                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"
                                                            fill="url(#half-grad-{{ $lecture->id }}-{{ $i }})" />
                                                    </svg>
                                                @else
                                                    {{-- Empty star --}}
                                                    <svg width="20" height="20" fill="lightgray" viewBox="0 0 20 20" style="display:inline;">
                                                        <polygon
                                                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" />
                                                    </svg>
                                                @endif
                                            @endfor
                                            <span>({{ number_format($rating, 1) }})</span>
                                            <span>({{ $lecture->ratings->count() }} reviews)</span>
                                        </div>
                                    </x-card>
                        @endforeach
                    </div>
            @endforeach
        </div>
    </x-cardcontainer>

    @if ($modelToPass->total() > 1)
        <div class="pagination-info"
            style="text-align: center; margin-bottom: 2%; font-size: 24px; color: var(--text-color);">
            {{ __('messages.showingItems', [
            'from' => $modelToPass->firstItem(),
            'to' => $modelToPass->lastItem(),
            'total' => $modelToPass->total(),
            'items' => __('messages.lectures')
        ]) }}
        </div>
    @endif

    @if ($modelToPass->total() > 10)
        <div class="pagination">
            {{ $modelToPass->appends([
            'search' => $searchQuery,
            'sort' => $sort,
            'subjects' => $selectedSubjects,
        ])->links() }}
        </div>
    @endif
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

            // Build the query string
            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);
            selectedSubjects.forEach(subject => params.append('subjects[]', subject));

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

                    if (@json($modelToPass->count()) > 10) {

                        // Update pagination info text
                        const paginationInfo = doc.querySelector('.pagination-info');
                        const paginationInfoContainer = document.querySelector('.pagination-info');
                        if (paginationInfo) {
                            paginationInfoContainer.innerHTML = paginationInfo.innerHTML;
                        } else {
                            paginationInfoContainer.innerHTML = '';
                        }

                        // Update pagination links conditionally
                        const pagination = doc.querySelector('.pagination');
                        const paginationContainer = document.querySelector('.pagination');
                        if (pagination) {
                            paginationContainer.innerHTML = pagination.innerHTML;
                        } else {
                            paginationContainer.innerHTML = '';
                        }
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
        });
    });
</script>
