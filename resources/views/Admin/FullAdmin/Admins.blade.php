@props(['admins' => null])

@php
    // Get the view mode from request (default to 'admins')
    $viewMode = request('view', 'admins');
    
    // Get the search query, sort parameter, and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedPrivileges = request('privileges', []); // Selected privileges for filtering (admins only)
    $selectedSubjects = request('subjects', []); // Selected subjects for filtering (teachers only)
    $filterNone = request('none', false); // Filter for teachers with no subjects
    $subjectCounts = request('subject_count', []); // Subject count filters for teachers

    // Normalize the search query by converting to lowercase and splitting into individual terms
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    if ($viewMode === 'teachers') {
        // Fetch teachers based on the search query, sort parameter, and filters
        $modelToPass = App\Models\Teacher::when($searchQuery, function ($query) use ($searchTerms) {
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
                $query->orderByRaw('LOWER(name) ASC');
            } elseif ($sort === 'name-z-a') {
                $query->orderByRaw('LOWER(name) DESC');
            } elseif ($sort === 'username-a-z') {
                $query->orderByRaw('LOWER(userName) ASC');
            } elseif ($sort === 'username-z-a') {
                $query->orderByRaw('LOWER(userName) DESC');
            } elseif ($sort === 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            }
        })
        ->paginate(10);
        
        // Prepare filter options for teachers
        $filterOptions = App\Models\Subject::pluck('name', 'id')->toArray();
    } else {
        // Fetch admins based on the search query, sort parameter, and filters
        $modelToPass = App\Models\Admin::when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(userName) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(CONCAT(countryCode, number)) LIKE ?', ["%{$term}%"]);
                });
            }
            return $query;
        })
        ->when($selectedPrivileges, function ($query) use ($selectedPrivileges) {
            $query->whereIn('privileges', $selectedPrivileges);
        })
        ->when($sort, function ($query) use ($sort) {
            if ($sort === 'name-a-z') {
                $query->orderByRaw('LOWER(name) ASC');
            } elseif ($sort === 'name-z-a') {
                $query->orderByRaw('LOWER(name) DESC');
            } elseif ($sort === 'username-a-z') {
                $query->orderByRaw('LOWER(userName) ASC');
            } elseif ($sort === 'username-z-a') {
                $query->orderByRaw('LOWER(userName) DESC');
            } elseif ($sort === 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            }
        })
        ->paginate(10);
        
        $filterOptions = [];
    }

    // Split items into chunks
    $chunkSize = 2;
    $chunkedItems = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedItems[$i] = [];
    }

    foreach ($modelToPass as $index => $item) {
        $chunkIndex = $index % $chunkSize;
        $chunkedItems[$chunkIndex][] = $item;
    }
@endphp

<x-layout :objects=true object="{{ $viewMode === 'teachers' ? __('messages.teachers') : __('messages.admins') }}">
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), ($viewMode === 'teachers' ? __('messages.teachers') : __('messages.admins')) => url('/admins')]" />

    <!-- Toggle Switch -->
    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <div class="toggle-container">
            <div class="toggle-switch" id="viewToggle">
                <div class="toggle-option {{ $viewMode === 'admins' ? 'active' : '' }}" data-view="admins">
                    {{ __('messages.admins') }}
                </div>
                <div class="toggle-option {{ $viewMode === 'teachers' ? 'active' : '' }}" data-view="teachers">
                    {{ __('messages.teachers') }}
                </div>
                <div class="toggle-slider {{ $viewMode === 'teachers' ? 'right' : '' }}"></div>
            </div>
        </div>
    </div>

    @if ($viewMode === 'teachers')
        <x-cardcontainer :model=$modelToPass addLink="addadmin" :filterOptions=$filterOptions :showSubjectCountFilter=true :showUsernameSort=true :showNameSort=true>
            <!-- Add a unique ID to the container for dynamic updates -->
            <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
                @foreach ($chunkedItems as $chunk)
                    <div class="chunk">
                        @foreach ($chunk as $item)
                            <x-card link="teacher/{{ $item->id }}" image="{{ asset($item->image) }}" object="Teacher">
                                ● {{__('messages.teacherName')}}: {{ $item->name }}<br>
                                ● {{__('messages.teacherUserName')}}: {{ $item->userName }}<br>
                                ● {{__('messages.teacherNumber')}}: <span style="direction: ltr; display: inline-block;">&emsp;{{ $item->countryCode }} {{ $item->number }}</span>
                                ● {{__('messages.teacherDescription')}}: {{ $item->description }}<br>
                                ● {{__('messages.subjects')}}:
                                @if ($item->subjects->count() == 0)
                                    <div style="color:black;">&emsp;{{ __('messages.none') }}</div>
                                @else
                                    <br>&emsp;[
                                    @foreach ($item->subjects as $subject)
                                        {{ $subject->name }}
                                        @if (!$loop->last) - @endif
                                    @endforeach
                                    ]
                                @endif
                                <br>
                                ● {{__('messages.courses')}}:
                                @if ($item->courses->count() == 0)
                                    <div style="color:var(--text-color-inverted);">&emsp;{{ __('messages.none') }}</div>
                                @else
                                    <br>&emsp;[
                                    @foreach ($item->courses as $course)
                                        {{ $course->name }}
                                        @if (!$loop->last) - @endif
                                    @endforeach
                                    ]
                                @endif
                                <br><br>
                                <div style="display:inline-block; vertical-align:middle;">
                                    @php $rating = $item->rating ?? 0; @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($rating >= $i)
                                            <svg width="20" height="20" fill="gold" viewBox="0 0 20 20" style="display:inline;"><polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/></svg>
                                        @elseif ($rating >= $i - 0.5)
                                            <svg width="20" height="20" viewBox="0 0 20 20" style="display:inline;">
                                                <defs>
                                                    <linearGradient id="half-grad-{{ $item->id }}-{{ $i }}">
                                                        <stop offset="50%" stop-color="gold"/>
                                                        <stop offset="50%" stop-color="lightgray"/>
                                                    </linearGradient>
                                                </defs>
                                                <polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" fill="url(#half-grad-{{ $item->id }}-{{ $i }})"/>
                                            </svg>
                                        @else
                                            <svg width="20" height="20" fill="lightgray" viewBox="0 0 20 20" style="display:inline;"><polygon points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"/></svg>
                                        @endif
                                    @endfor
                                    <span>({{ number_format($rating, 1) }})</span>
                                    <span>({{ $item->ratings->count() }} reviews)</span>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </x-cardcontainer>
    @else
        <x-cardcontainer :model=$modelToPass addLink="addadmin" :showUsernameSort=true :showNameSort=true :showPrivilegeFilter=true>
            <!-- Add a unique ID to the container for dynamic updates -->
            <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
                @foreach ($chunkedItems as $chunk)
                    <div class="chunk">
                        @foreach ($chunk as $item)
                            <x-card link="admin/{{ $item->id }}" image="{{ asset($item->image) }}" object="Admin">
                                ● {{__('messages.adminName')}}: {{ $item->name }}<br>
                                ● {{__('messages.adminUserName')}}: {{ $item->userName }}<br>
                                ● {{__('messages.adminNumber')}}: <span style="direction: ltr; display: inline-block;">&emsp;{{ $item->countryCode }} {{ $item->number }}</span>
                                ● {{__('messages.adminPrivileges')}}:
                                @if ($item->privileges == 0)
                                    {{__('messages.teacher')}}
                                @elseif ($item->privileges == 1)
                                    {{__('messages.semi-admin')}}
                                @else
                                    {{__('messages.admin')}}
                                @endif
                                <br>
                            </x-card>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </x-cardcontainer>
    @endif

    @if ($modelToPass->total() > 1)
        <div class="pagination-info" style="text-align: center; margin-bottom: 2%; font-size: 24px; color: var(--text-color);">
        {{ __('messages.showingItems', [
                'from' => $modelToPass->firstItem(),
                'to' => $modelToPass->lastItem(),
                'total' => $modelToPass->total(),
                'items' => $viewMode === 'teachers' ? __('messages.teachers') : __('messages.admins')
            ]) }}
        </div>
    @endif

    @if ($modelToPass->total() > 10)
        <div class="pagination">
            @if ($viewMode === 'teachers')
                {{ $modelToPass->appends([
                        'view' => $viewMode,
                        'search' => $searchQuery,
                        'sort' => $sort,
                        'subjects' => $selectedSubjects,
                        'none' => $filterNone,
                        'subject_count' => $subjectCounts,
                    ])->links() }}
            @else
                {{ $modelToPass->appends([
                        'view' => $viewMode,
                        'search' => $searchQuery,
                        'sort' => $sort,
                        'privileges' => $selectedPrivileges,
                    ])->links() }}
            @endif
        </div>
    @endif
</x-layout>

<style>
.toggle-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.toggle-switch {
    position: relative;
    display: flex;
    background-color: #f0f0f0;
    border-radius: 25px;
    padding: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.toggle-option {
    position: relative;
    padding: 12px 24px;
    cursor: pointer;
    font-weight: 600;
    font-size: 16px;
    transition: color 0.3s ease;
    z-index: 2;
    min-width: 100px;
    text-align: center;
}

.toggle-option.active {
    color: white;
}

.toggle-option:not(.active) {
    color: #666;
}

.toggle-slider {
    position: absolute;
    top: 4px;
    left: 4px;
    width: calc(50% - 4px);
    height: calc(100% - 8px);
    background: linear-gradient(135deg, #555184, #6b5b95);
    border-radius: 21px;
    transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    z-index: 1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-slider.right {
    transform: translateX(100%);
}

.toggle-option:hover:not(.active) {
    color: #333;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBar = document.querySelector('.search-bar');
        const dynamicContent = document.getElementById('dynamic-content');
        const filterForm = document.querySelector('.filter-dropdown');
        const paginationInfoContainer = document.querySelector('.pagination-info');
        const paginationContainer = document.querySelector('.pagination');
        const toggleOptions = document.querySelectorAll('.toggle-option');
        const toggleSlider = document.querySelector('.toggle-slider');

        // Get current view mode
        let currentView = '{{ $viewMode }}';

        // Handle toggle switch
        toggleOptions.forEach(option => {
            option.addEventListener('click', function() {
                const newView = this.getAttribute('data-view');
                if (newView !== currentView) {
                    // Update URL with new view mode
                    const url = new URL(window.location);
                    url.searchParams.set('view', newView);
                    url.searchParams.delete('search'); // Clear search when switching views
                    url.searchParams.delete('sort'); // Reset sort
                    url.searchParams.delete('privileges'); // Clear admin filters
                    url.searchParams.delete('subjects'); // Clear teacher filters
                    url.searchParams.delete('none'); // Clear teacher filters
                    url.searchParams.delete('subject_count'); // Clear teacher filters
                    
                    // Navigate to new URL
                    window.location.href = url.toString();
                }
            });
        });

        // Function to fetch and update results
        function updateResults() {
            const query = searchBar ? searchBar.value : '';
            const selectedSort = document.querySelector('input[name="sort"]:checked')?.value || 'newest';
            
            // Build query string based on current view
            const params = new URLSearchParams();
            params.set('view', currentView);
            params.set('search', query);
            params.set('sort', selectedSort);

            if (currentView === 'admins') {
                const selectedPrivileges = Array.from(document.querySelectorAll(
                    'input[name="privileges[]"]:checked')).map(el => el.value);
                selectedPrivileges.forEach(privilege => params.append('privileges[]', privilege));
            } else {
                const selectedSubjects = Array.from(document.querySelectorAll(
                    'input[name="subjects[]"]:checked')).map(el => el.value);
                const filterNone = document.getElementById('filter-none')?.checked || false;
                const subjectCounts = Array.from(document.querySelectorAll(
                    'input[name="subject_count[]"]:checked')).map(el => el.value);
                
                selectedSubjects.forEach(subject => params.append('subjects[]', subject));
                if (filterNone) params.set('none', 'true');
                subjectCounts.forEach(count => params.append('subject_count[]', count));
            }

            fetch(`{{ request()->url() }}?${params.toString()}`)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const newContent = doc.getElementById('dynamic-content').innerHTML;
                    dynamicContent.innerHTML = newContent;

                    // Update pagination info
                    const responsePaginationInfo = doc.querySelector('.pagination-info');
                    if (responsePaginationInfo) {
                        paginationInfoContainer.innerHTML = responsePaginationInfo.innerHTML;
                    } else {
                        paginationInfoContainer.innerHTML = '';
                    }

                    // Update pagination controls
                    const responsePagination = doc.querySelector('.pagination');
                    if (responsePagination) {
                        paginationContainer.innerHTML = responsePagination.innerHTML;
                    } else {
                        paginationContainer.innerHTML = '';
                    }

                    attachCircleEffect();
                    if (typeof refreshAnimations === 'function') {
                        refreshAnimations();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    dynamicContent.innerHTML = '<div class="error-message">{{ __("messages.failedToLoadResults") }}</div>';
                    paginationInfoContainer.innerHTML = '';
                    paginationContainer.innerHTML = '';
                });
        }

        // Handle search input with debounce
        let searchTimeout;
        if (searchBar) {
            searchBar.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateResults, 300);
            });
        }

        // Handle filter changes
        if (filterForm) {
            filterForm.addEventListener('change', updateResults);
        }

        // Handle individual checkbox changes for both admin and teacher filters
        document.addEventListener('change', function(e) {
            if (e.target.matches('input[type="checkbox"][name^="privileges"], input[type="checkbox"][name^="subjects"], input[name="none"], input[name^="subject_count"]')) {
                updateResults();
            }
        });
    });
</script>
