@props(['teachers' => null, 'num' => App\Models\Teacher::count()])

@php
    // Get the search query and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedSubjects = request('subjects', []);
    $filterNone = request('none', false);
    $subjectCounts = request('subject_count', []); // Get selected subject counts as an array

    // Normalize the search query by converting to lowercase and splitting into individual terms
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    if ($teachers !== null) {
        // Convert the $lectures collection to a query builder
        $query = App\Models\Teacher::whereIn('id', $teachers->pluck('id'));
    } else {
        // Fetch all lectures based on search and filters
        $query = App\Models\Teacher::query();
    }
    // Fetch teachers based on the search query and filters
    $modelToPass = $query
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(userName) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(CONCAT(countryCode, number)) LIKE ?', ["%{$term}%"]);
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
                            $q->groupBy('teacher_id')->havingRaw('COUNT(subjects.id) BETWEEN 2 AND 3');
                        });
                    } elseif ($count === '4-5') {
                        $q->orWhereHas('subjects', function ($q) {
                            $q->groupBy('teacher_id')->havingRaw('COUNT(subjects.id) BETWEEN 4 AND 5');
                        });
                    } elseif ($count === '6+') {
                        $q->orWhereHas('subjects', function ($q) {
                            $q->groupBy('teacher_id')->havingRaw('COUNT(subjects.id) >= 6');
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
            } elseif ($sort === 'username-a-z') {
                $query->orderByRaw('LOWER(userName) ASC'); // Sort by username A-Z (case-insensitive)
            } elseif ($sort === 'username-z-a') {
                $query->orderByRaw('LOWER(userName) DESC'); // Sort by username Z-A (case-insensitive)
            } elseif ($sort === 'newest') {
                $query->orderBy('created_at', 'desc'); // Sort by creation date (newest)
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc'); // Sort by creation date (oldest)
            }
        })
        ->paginate(10);

    // Prepare filter options
    $filterOptions = App\Models\Subject::pluck('name', 'id')->toArray();

    // Split teachers into chunks
    $chunkSize = 2;
    $chunkedTeachers = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedTeachers[$i] = [];
    }

    foreach ($modelToPass as $index => $teacher) {
        $chunkIndex = $index % $chunkSize;
        $chunkedTeachers[$chunkIndex][] = $teacher;
    }
@endphp

<x-layout :objects=true
    object="{{ /*!$teachers ?  : Str::upper(__('messages.teachersFrom')) . Str::upper(App\Models\university::findOrFail(session('university'))->name)*/ Str::upper(__('messages.teachers'))}}">
    <x-breadcrumb :links="array_merge(
        [__('messages.home') => url('/welcome')],
        [__('messages.teachers') => Request::url()],
    )" />
    <x-cardcontainer :model=$modelToPass addLink="addteacher" :filterOptions=$filterOptions :showSubjectCountFilter=true
        :showUsernameSort=true :showNameSort=true>
        <!-- Add a unique ID to the container for dynamic updates -->
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedTeachers as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $teacher)
                        <x-card link="teacher/{{ $teacher->id }}" image="{{ asset($teacher->image) }}" object="Teacher">
                            ● {{__('messages.teacherName')}}: {{ $teacher->name }}<br>
                            ● {{__('messages.teacherUserName')}}: {{ $teacher->userName }}<br>
                            ● {{__('messages.teacherNumber')}}: <span
                                style="direction: ltr; display: inline-block;">&emsp;{{ $teacher->countryCode }}
                                {{ $teacher->number }}</span>
                            ● {{__('messages.teacherDescription')}}: {{ $teacher->description }}<br>
                            ● {{__('messages.subjects')}}:
                            @if ($teacher->subjects->count() == 0)
                                <div style="color:black;">&emsp;{{ __('messages.none') }}</div>
                            @else
                                <br>
                                &emsp;
                                [
                                @foreach ($teacher->subjects as $subject)
                                    {{ $subject->name }}
                                    @if (!$loop->last)
                                        -
                                    @endif
                                @endforeach
                                ]
                            @endif
                            <br>
                            ● {{__('messages.courses')}}:
                            @if ($teacher->courses->count() == 0)
                                <div style="color:var(--text-color-inverted);">&emsp;{{ __('messages.none') }}</div>
                            @else
                                <br>
                                &emsp;
                                [
                                @foreach ($teacher->courses as $course)
                                    {{ $course->name }}
                                    @if (!$loop->last)
                                        -
                                    @endif
                                @endforeach
                                ]
                            @endif
                            <br>
                            <br>
                            <div style="display:inline-block; vertical-align:middle;">
                                @php
                                    $rating = $teacher->rating ?? 0;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($rating >= $i)
                                        {{-- Full star --}}
                                        <svg width="20" height="20" fill="gold" viewBox="0 0 20 20" style="display:inline;"><polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/></svg>
                                    @elseif ($rating >= $i - 0.5)
                                        {{-- Half star --}}
                                        <svg width="20" height="20" viewBox="0 0 20 20" style="display:inline;">
                                            <defs>
                                                <linearGradient id="half-grad-{{ $teacher->id }}-{{ $i }}">
                                                    <stop offset="50%" stop-color="gold"/>
                                                    <stop offset="50%" stop-color="lightgray"/>
                                                </linearGradient>
                                            </defs>
                                            <polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" fill="url(#half-grad-{{ $teacher->id }}-{{ $i }})"/>
                                        </svg>
                                    @else
                                        {{-- Empty star --}}
                                        <svg width="20" height="20" fill="lightgray" viewBox="0 0 20 20" style="display:inline;"><polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/></svg>
                                    @endif
                                @endfor
                                <span>({{ number_format($rating, 1) }})</span>
                                <span>({{ $teacher->ratings->count() }} reviews)</span>

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
            'items' => __('messages.teachers')
        ]) }}
        </div>
    @endif

    <!-- Conditionally render pagination links -->
    @if ($num > 10)
        <div class="pagination">
            {{ $modelToPass->appends([
            'search' => $searchQuery,
            'sort' => $sort,
            'subjects' => $selectedSubjects,
            'none' => $filterNone,
            'subject_count' => request('subject_count', []), // Include subject_count as an array
        ])->links() }}
        </div>
    @endif
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchBar = document.querySelector('.search-bar');
        const dynamicContent = document.getElementById('dynamic-content');
        const filterForm = document.querySelector('.filter-dropdown');
        const filterCheckboxes = document.querySelectorAll(
            'input[type="checkbox"][name^="subjects"], input[name="none"], input[name^="subject_count"]');
        const paginationInfoContainer = document.querySelector('.pagination-info');
        const paginationContainer = document.querySelector('.pagination');

        // Function to fetch and update results
        function updateResults() {
            const query = searchBar.value;
            const selectedSort = document.querySelector('input[name="sort"]:checked')?.value || 'newest';
            const selectedSubjects = Array.from(document.querySelectorAll('input[name="subjects[]"]:checked'))
                .map(el => el.value);
            const filterNone = document.getElementById('filter-none')?.checked || false;
            const subjectCounts = Array.from(document.querySelectorAll('input[name="subject_count[]"]:checked'))
                .map(el => el.value);

            // Build query string
            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);
            selectedSubjects.forEach(subject => params.append('subjects[]', subject));
            if (filterNone) params.set('none', 'true');
            subjectCounts.forEach(count => params.append('subject_count[]', count));

            paginationInfoContainer.innerHTML = '';
            paginationContainer.innerHTML = '';

            fetch(`{{ request()->url() }}?${params.toString()}`)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newContent = doc.getElementById('dynamic-content').innerHTML;
                    dynamicContent.innerHTML = newContent;

                    // Update pagination info (show if at least 1 result)
                    const responsePaginationInfo = doc.querySelector('.pagination-info');
                    if (responsePaginationInfo) {
                        paginationInfoContainer.innerHTML = responsePaginationInfo.innerHTML;
                    } else {
                        // Check if we should show pagination info by extracting count from response
                        const countMatch = doc.body.textContent.match(/of (\d+) teachers/);
                        const totalCount = countMatch ? parseInt(countMatch[1]) : 0;

                        if (totalCount > 0) {
                            // Reconstruct pagination info
                            const firstItem = 1;
                            const lastItem = Math.min(10, totalCount);
                            paginationInfoContainer.innerHTML =
                                `Showing ${firstItem} to ${lastItem} of ${totalCount} teachers`;
                        } else {
                            paginationInfoContainer.innerHTML = '';
                        }
                    }

                    // Update pagination controls (show if >10 results)
                    const responsePagination = doc.querySelector('.pagination');
                    if (responsePagination) {
                        paginationContainer.innerHTML = responsePagination.innerHTML;
                    } else {
                        const totalCount = doc.querySelector('.pagination-info')?.textContent.match(
                            /of (\d+) teachers/)?.[1] || 0;
                        if (totalCount > 10) {
                            // We should have pagination but it's missing from response
                            // You may need to reconstruct it here if needed
                        } else {
                            paginationContainer.innerHTML = '';
                        }
                    }

                    attachCircleEffect();
                    refreshAnimations();
                })
                .catch(error => {
                    paginationInfoContainer.innerHTML = '';
                    paginationContainer.innerHTML = '';
                });
        }

        // Handle search input with debounce
        let searchTimeout;
        searchBar.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateResults, 0);
        });

        // Handle filter changes
        if (filterForm) {
            filterForm.addEventListener('change', updateResults);
        }

        // Handle individual checkbox changes
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateResults);
        });

        // Initial attachment of effects
        attachCircleEffect();
    });
</script>
