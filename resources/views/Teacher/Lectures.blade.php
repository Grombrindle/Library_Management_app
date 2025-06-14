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
            $query->whereHas('subject', function ($q) use ($selectedSubjects) {
                $q->whereIn('id', $selectedSubjects);
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

    // Prepare filter options
    $filterOptions = $teacher->subjects->pluck('name', 'id')->toArray();

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
    <x-breadcrumb :links="array_merge([__('messages.home') => url('/welcome'), !$lec ? __('messages.yourLectures') : __('messages.lecturesFrom') . ' ' . App\Models\Subject::findOrFail(session('subject'))->name  => Request::url()])" />

    <x-cardcontainer :model=$modelToPass addLink="addlecture" :filterOptions=$filterOptions :showSubjectCountFilter=false
        :showUsernameSort=false :showNameSort=false>
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedLectures as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $lecture)
                        <x-card link="lecture/{{ $lecture->id }}" image="{{ asset($lecture->image) }}" object="Lecture">
                            ● {{ __('messages.lectureName') }}: {{ $lecture->name }}<br>
                            ● {{ __('messages.lectureDescription') }}: {{ $lecture->description }}<br>
                            ● {{__('messages.fromTeacher')}}: {{ $lecture->course->teacher->name }} <br>
                            ● {{__('messages.fromCourse')}}: {{ $lecture->course->name }} <br>
                            ● {{__('messages.fileType')}}: @if ($lecture->type)
                                {{__('messages.video')}} <br>
                                ● {{__('messages.duration')}}: {{ $lecture->getVideoLength() ?? 'N/A' }}
                                @else
                                {{__('messages.pdf')}} <br>
                                ● {{__('messages.pages')}}: {{ $lecture->getPdfPages() ?? 'N/A' }}
                            @endif
                        </x-card>
                    @endforeach
                </div>
            @endforeach
        </div>
    </x-cardcontainer>

    @if ($modelToPass->total() > 1)
        <div class="pagination-info" style="text-align: center; margin-bottom: 2%; font-size: 24px; color: var(--text-color);">
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
    document.addEventListener('DOMContentLoaded', function() {
        const searchBar = document.querySelector('.search-bar');
        const dynamicContent = document.getElementById('dynamic-content');

        searchBar.addEventListener('input', function() {
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
