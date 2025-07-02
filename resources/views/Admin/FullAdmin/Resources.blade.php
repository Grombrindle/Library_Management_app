@props(['resources' => null, 'num' => App\Models\Resource::count()])

@php
    // Get the search query and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedSubjects = request('subjects', []);
    $filterType = request('type', []); // For literary/scientific filter

    // Normalize the search query
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    if ($resources !== null) {
        $query = App\Models\Resource::whereIn('id', $resources->pluck('id'));
    } else {
        $query = App\Models\Resource::query();
    }

    // Apply filters and search
    $modelToPass = $query
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])->orWhereRaw('LOWER(author) LIKE ?', ["%{$term}%"]);
                });
            }
            return $query;
        })
        ->when($selectedSubjects, function ($query) use ($selectedSubjects) {
            $query->whereIn('subject_id', $selectedSubjects);
        })
        ->when($filterType, function ($query) use ($filterType) {
            $query->whereIn('literaryOrScientific', $filterType);
        })
        ->when($sort, function ($query) use ($sort) {
            if ($sort === 'name-a-z') {
                $query->orderByRaw('LOWER(name) ASC');
            } elseif ($sort === 'name-z-a') {
                $query->orderByRaw('LOWER(name) DESC');
            } elseif ($sort === 'author-a-z') {
                $query->orderByRaw('LOWER(author) ASC');
            } elseif ($sort === 'author-z-a') {
                $query->orderByRaw('LOWER(author) DESC');
            } elseif ($sort === 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'publish-newest') {
                $query->orderByDesc('publish date');
            } elseif ($sort === 'publish-oldest') {
                $query->orderBy('publish date', 'asc');
            } elseif ($sort === 'rating-highest') {
                $query->orderByDesc('rating');
            } elseif ($sort === 'rating-lowest') {
                $query->orderBy('rating', 'asc');
            }
        })
        ->paginate(10);

    // Prepare filter options
    $filterOptions = App\Models\Subject::pluck('name', 'id')->toArray();
    $typeOptions = [
        1 => __('messages.literary'),
        2 => __('messages.scientific'),
    ];

    // Split resources into chunks for a 2-column grid layout
    $chunkSize = 2;
    $chunkedLectures = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedLectures[$i] = [];
    }

    foreach ($modelToPass as $index => $resource) {
        $chunkIndex = $index % $chunkSize;
        $chunkedLectures[$chunkIndex][] = $resource;
    }
@endphp


<x-layout :objects=true object="{{ __('messages.resources') }}">
    <x-breadcrumb :links="array_merge(
        [__('messages.home') => url('/welcome')],
        [
            __('messages.resources') => Request::url(),
        ],
    )" />

    <x-cardcontainer :model=$modelToPass addLink="addresource" :filterOptions=$filterOptions
        :showUsernameSort=false :showNameSort=true>
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedLectures as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $resource)
                        <x-card link="resource/{{ $resource->id }}" image="{{ asset($resource->image) }}"
                            object="Resource">
                            ● {{ __('messages.resourceName') }}: {{ $resource->name }}<br>
                            ● {{ __('messages.resourceAuthor') }}: {{ $resource->author }}<br>
                            ● {{ __('messages.resourceDescription') }}: {{ $resource->description }}<br>
                            ● {{ __('messages.resourceSubject') }}:
                            {{ $resource->subject->name }} ({{ $resource->literaryOrScientific == 1 ? __('messages.literary') : __('messages.scientific') }})<br>
                            ● {{ __('messages.resourcePublishDate') }}: {{ $resource['publish date'] }}<br>
                            <br>
                            <div style="display:inline-block; vertical-align:middle;">
                                @php
                                    $rating = $resource->rating ?? 0;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($rating >= $i)
                                        {{-- Full star --}}
                                        <svg width="20" height="20" fill="gold" viewBox="0 0 20 20"
                                            style="display:inline;">
                                            <polygon
                                                points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" />
                                        </svg>
                                    @elseif ($rating >= $i - 0.5)
                                        {{-- Half star --}}
                                        <svg width="20" height="20" viewBox="0 0 20 20" style="display:inline;">
                                            <defs>
                                                <linearGradient
                                                    id="half-grad-{{ $resource->id }}-{{ $i }}">
                                                    <stop offset="50%" stop-color="gold" />
                                                    <stop offset="50%" stop-color="lightgray" />
                                                </linearGradient>
                                            </defs>
                                            <polygon
                                                points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"
                                                fill="url(#half-grad-{{ $resource->id }}-{{ $i }})" />
                                        </svg>
                                    @else
                                        {{-- Empty star --}}
                                        <svg width="20" height="20" fill="lightgray" viewBox="0 0 20 20"
                                            style="display:inline;">
                                            <polygon
                                                points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" />
                                        </svg>
                                    @endif
                                @endfor
                                <span>({{ number_format($rating, 1) }})</span>
                                <span>({{ $resource->ratings->count() }} reviews)</span>

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
                'items' => __('messages.lectures'),
            ]) }}
        </div>
    @endif

    @if ($modelToPass->total() > 10)
        <div class="pagination">
            {{ $modelToPass->appends([
                    'search' => $searchQuery,
                    'sort' => $sort,
                    'subjects' => $selectedSubjects,
                    'type' => $filterType,
                ])->links() }}
        </div>
    @endif
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBar = document.querySelector('.search-bar');
        const dynamicContent = document.getElementById('dynamic-content');
        const filterForm = document.querySelector('.filter-dropdown');
        const filterCheckboxes = document.querySelectorAll(
            'input[type="checkbox"][name^="subjects"], input[name^="type"]');
        const paginationInfoContainer = document.querySelector('.pagination-info');
        const paginationContainer = document.querySelector('.pagination');

        function updateResults() {
            const query = searchBar.value;
            const selectedSort = document.querySelector('input[name="sort"]:checked')?.value || 'newest';
            const selectedSubjects = Array.from(document.querySelectorAll('input[name="subjects[]"]:checked'))
                .map(el => el.value);
            const selectedTypes = Array.from(document.querySelectorAll('input[name="type[]"]:checked')).map(
                el => el.value);

            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);
            selectedSubjects.forEach(subject => params.append('subjects[]', subject));
            selectedTypes.forEach(type => params.append('type[]', type));

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
                    if (paginationInfoContainer && responsePaginationInfo) {
                        paginationInfoContainer.innerHTML = responsePaginationInfo.innerHTML;
                    } else if (paginationInfoContainer) {
                        // Check if we should show pagination info by extracting count from response
                        const countMatch = doc.body.textContent.match(/of (\d+) resources/);
                        const totalCount = countMatch ? parseInt(countMatch[1]) : 0;

                        if (totalCount > 0) {
                            // Reconstruct pagination info
                            const firstItem = 1;
                            const lastItem = Math.min(10, totalCount);
                            paginationInfoContainer.innerHTML =
                                `Showing ${firstItem} to ${lastItem} of ${totalCount} resources`;
                        } else {
                            paginationInfoContainer.innerHTML = '';
                        }
                    }

                    // Update pagination controls (show if >10 results)
                    const responsePagination = doc.querySelector('.pagination');
                    if (paginationContainer && responsePagination) {
                        paginationContainer.innerHTML = responsePagination.innerHTML;
                    } else if (paginationContainer) {
                        paginationContainer.innerHTML = '';
                    }

                    attachCircleEffect();
                    refreshAnimations && refreshAnimations();
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (dynamicContent) dynamicContent.innerHTML = '<div class="error-message">Failed to load results</div>';
                    if (paginationInfoContainer) paginationInfoContainer.innerHTML = '';
                    if (paginationContainer) paginationContainer.innerHTML = '';
                });
        }

        // Handle search input with debounce
        let searchTimeout;
        searchBar.addEventListener('input', function() {
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
    });
</script>
