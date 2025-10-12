@props(['subjects' => App\Models\Teacher::findOrFail(Auth::user()->teacher_id)->subjects])
@php
    // Get the search query and sort parameter from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'

    // Normalize the search query by converting to lowercase and splitting into individual terms
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    // Fetch subjects taught by the currently signed-in teacher
    $modelToPass = App\Models\Subject::whereHas('teachers', function ($query) {
        $query->where('teachers.id', Auth::user()->teacher_id); // Filter by the current teacher
    })
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
            }
            return $query;
        })
        ->when($sort, function ($query) use ($sort) {
            if ($sort === 'name-a-z') {
                $query->orderByRaw('LOWER(name) ASC'); // Sort by name A-Z (case-insensitive)
            } elseif ($sort === 'name-z-a') {
                $query->orderByRaw('LOWER(name) DESC'); // Sort by name Z-A (case-insensitive)
            } elseif ($sort === 'newest') {
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
            }
        })
        ->paginate(10);

    // Split subjects into chunks
    $chunkSize = 2;
    $chunkedSubjects = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedSubjects[$i] = [];
    }

    foreach ($modelToPass as $index => $subject) {
        $chunkIndex = $index % $chunkSize;
        $chunkedSubjects[$chunkIndex][] = $subject;
    }
@endphp

<x-layout :objects=true object="{{ __('messages.yourSubjects') }}">
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.yourSubjects') => url('/subjects')]" />

    <x-cardcontainer :model=$modelToPass :addLink=null :showSubjectCountFilter=false filterByTeachers=false
        :showNameSort=true models="Subjects">
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedSubjects as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $subject)
                        <x-card link="subject/{{ $subject->id }}" image="{{ asset($subject->image) }}" object="Subject">
                            ● {{ __('messages.subjectName') }}: {{ $subject->name }}<br>
                            ● {{ __('messages.teachers') }}:
                            @if ($subject->teachers->count() == 0)
                                <div style="color:black; margin-right:auto;">&emsp;none</div>
                            @elseif($subject->teachers->count() == 1)
                                <br>&emsp;
                                @foreach ($subject->teachers as $teacher)
                                    {{ $teacher->name }} ({{ __('messages.you') }})
                                @endforeach
                            @else
                                <br>&emsp;
                                [
                                @foreach ($subject->teachers as $teacher)
                                    {{ $teacher->name }} @if ($teacher->userName == Auth::user()->userName)
                                        ({{ __('messages.you') }})
                                    @endif
                                    @if (!$loop->last)
                                        -
                                    @endif
                                @endforeach
                                ]
                            @endif
                            <br>
                            ● {{__('messages.coursesFromYou')}}:
                            {{ $subject->courses->where('teacher_id', auth()->user()->teacher_id)->count() }}<br>
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
            'items' => __('messages.subjects')
        ]) }}
        </div>
    @endif

    <!-- Conditionally render pagination links -->
    @if ($modelToPass->total() > 10)
        <div class="pagination">
            {{ $modelToPass->appends([
            'search' => $searchQuery,
            'sort' => $sort,
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

            // Get current sort value
            const selectedSort = document.querySelector('input[name="sort"]:checked')?.value ||
                'newest';

            // Build the query string
            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);

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
