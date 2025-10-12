@props(['users' => null, 'sub' => false])

@php
    session([
        'breadcrumb_users' => array_merge(
            ['Home' => url('/welcome')],
            $sub
                ? ['Users subscribed to  ' . App\Models\Course::findOrFail(session('course'))->name => Request::url()]
                : ['Users' => Request::url()],
        ),
    ]);

    $usersCount = $users->count();

    // Get the search query and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedCourses = request('courses', []);
    $filterNone = request('none', false);
    $courseCounts = request('course_count', []);

    // Normalize the search query by converting to lowercase and splitting into individual terms
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    // If $users is provided, use it directly; otherwise, fetch users based on search and filters
    if ($users !== null) {
        // Convert the $users collection to a query builder
        $modelToPass = App\Models\User::whereIn('id', $users->pluck('id'));
        $num = App\Models\User::count();
    } else {
        // Fetch all users based on search and filters
        $modelToPass = App\Models\User::query();
        $num = $users->count();
    }

    // Apply search, filters, and sorting
    $modelToPass = $modelToPass
        ->when(request('ban_status'), function ($query, $banStatus) {
            if ($banStatus === 'banned') {
                $query->where('isBanned', true);
            } elseif ($banStatus === 'active') {
                $query->where('isBanned', false);
            }
            return $query;
        })
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(userName) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(number) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(CONCAT(countryCode, number)) LIKE ?', ["%{$term}%"]);
                });
            }
            return $query;
        })
        ->when($selectedCourses || $filterNone, function ($query) use ($selectedCourses, $filterNone) {
            $query->where(function ($q) use ($selectedCourses, $filterNone) {
                if ($filterNone) {
                    $q->doesntHave('Courses');
                }
                if ($selectedCourses) {
                    if ($filterNone) {
                        $q->orWhereHas('courses', function ($q) use ($selectedCourses) {
                            $q->whereIn('courses.id', $selectedCourses);
                        });
                    } else {
                        $q->whereHas('courses', function ($q) use ($selectedCourses) {
                            $q->whereIn('courses.id', $selectedCourses);
                        });
                    }
                }
            });
        })
        ->when($courseCounts, function ($query) use ($courseCounts) {
            $query->where(function ($q) use ($courseCounts) {
                foreach ($courseCounts as $count) {
                    if ($count === '1') {
                        $q->orHas('courses', '=', 1);
                    } elseif ($count === '2-3') {
                        $q->orWhereHas('courses', function ($q) {
                            $q->groupBy('user_id')->havingRaw('COUNT(courses.id) BETWEEN 2 AND 3');
                        });
                    } elseif ($count === '4-5') {
                        $q->orWhereHas('courses', function ($q) {
                            $q->groupBy('user_id')->havingRaw('COUNT(courses.id) BETWEEN 4 AND 5');
                        });
                    } elseif ($count === '6+') {
                        $q->orWhereHas('courses', function ($q) {
                            $q->groupBy('user_id')->havingRaw('COUNT(courses.id) >= 6');
                        });
                    }
                }
            });
        })
        ->when($sort, function ($query) use ($sort) {
            if ($sort === 'username-a-z') {
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
    $filterOptions = [];

    foreach (App\Models\Course::all() as $course) {
        $type = $course->subject->literaryOrScientific == 0 ? __('messages.literary') : __('messages.scientific');
        $filterOptions[$course->id] = $course->name . ' (' . $course->subject->name . ' ' . $type . ')';
    }

    // Split users into chunks
    $chunkSize = 2;
    $chunkedUsers = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedUsers[$i] = [];
    }

    foreach ($modelToPass as $index => $user) {
        $chunkIndex = $index % $chunkSize;
        $chunkedUsers[$chunkIndex][] = $user;
    }
@endphp

<x-layout :objects=true
    object="{{ !$sub ? Str::upper(__('messages.users')) : Str::upper(__('messages.usersSubTo')) . Str::upper(App\Models\Course::findOrFail(session('course'))->name) }}">
    <x-breadcrumb :links="array_merge(
        [__('messages.home') => url('/welcome')],
        $sub
            ? [__('messages.usersSubTo') . App\Models\Course::findOrFail(session('course'))->name => Request::url()]
            : [__('messages.users') => Request::url()],
    )" />
    <x-cardcontainer :model=$modelToPass :addLink=null :filterOptions=$filterOptions :showSubjectCountFilter=true
        :showUsernameSort=true :showNameSort=false num="{{ $num }}" :deleteSubs=true :showBannedFilter="true" models="Users">
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">

            @foreach ($chunkedUsers as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $user)
                        <x-card link="user/{{ $user->id }}">
                            ● {{ __('messages.userName') }}: {{ $user->userName }}<br>
                            ● {{ __('messages.number') }}: {{ $user->countryCode }} {{ $user->number }}<br>
                            ● {{ __('messages.coursesSubTo') }}:
                            @if ($user->courses->count() == 0)
                                <div style="color:var(--text-color-inverted)">{{ __('messages.none') }}</div>
                            @else
                                <div>
                                    [
                                    @foreach ($user->courses as $course)
                                        {{ $course->name }} ({{ $course->subject->name }})
                                        @if (!$loop->last)
                                            -
                                        @endif
                                    @endforeach
                                    ]
                                </div>
                            @endif
                            {{-- ● {{ __('messages.lecturesSubTo') }}:
                            @if ($user->lectures->count() == 0)
                                <div style="color:var(--text-color-inverted)">{{ __('messages.none') }}</div>
                            @else
                                <div>
                                    {{ $user->lectures->count() }}

                                </div>
                            @endif --}}

                            ● {{ __('messages.sparks') }}: {{$user->sparks}}<br>
                            ● {{ __('messages.sparkies') }}: {{$user->sparkies}}<br>
                            @if ($user->isBanned)
                            <br>
                                <div style="color: red; font-weight: bold; margin-top: 1rem; font-size:60px;">{{ __('messages.banned') }}
                                </div>
                                <br>
                            @endif
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
                'items' => __('messages.users')
            ]) }}
        </div>
    @else
        <div class="pagination-info" style="display: none;"></div> {{-- Hidden container --}}
    @endif
    <div class="pagination" style="@if ($modelToPass->total() <= 10) display:none; @endif">
        {{ $modelToPass->appends([
                'search' => $searchQuery,
                'sort' => $sort,
                'courses' => $selectedCourses,
                'none' => $filterNone,
                'course_count' => request('course_count', []),
                'ban_status' => request('ban_status', 'all'),
            ])->links() }}
    </div>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBar = document.querySelector('.search-bar');
        const dynamicContent = document.getElementById('dynamic-content');

        searchBar.addEventListener('input', function() {
            const query = searchBar.value;

            // Get current filter values
            const selectedSort = document.querySelector('input[name="sort"]:checked')?.value ||
                'newest';
            const selectedCourses = Array.from(document.querySelectorAll(
                'input[name="courses[]"]:checked')).map(el => el.value);
            const filterNone = document.getElementById('filter-none')?.checked || false;
            const courseCounts = Array.from(document.querySelectorAll(
                'input[name="course_count[]"]:checked')).map(el => el.value);

            // Build the query string
            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);
            selectedCourses.forEach(course => params.append('courses[]', course));
            if (filterNone) {
                params.set('none', 'true');
            }
            courseCounts.forEach(count => params.append('course_count[]', count));

            fetch(`{{ request()->url() }}?${params.toString()}`)
                .then(response => response.text())
                .then(data => {
                    // Parse the response and extract the dynamic content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newContent = doc.getElementById('dynamic-content').innerHTML;

                    // Update the dynamic content without changing the structure
                    dynamicContent.innerHTML = newContent;

                    // Reattach the circle effect
                    attachCircleEffect();
                    refreshAnimations();

                    if (@json($usersCount) > 10) {
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
                .catch(error => console.error(__('messages.error_fetching_search_results'), error));
        });
    });
</script>
