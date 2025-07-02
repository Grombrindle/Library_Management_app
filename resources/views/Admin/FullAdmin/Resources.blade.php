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
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(author) LIKE ?', ["%{$term}%"]);
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
            }
        })
        ->paginate(10);

    // Prepare filter options
    $filterOptions = App\Models\Subject::pluck('name', 'id')->toArray();
    $typeOptions = [
        1 => __('messages.literary'),
        2 => __('messages.scientific')
    ];

    // Split resources into chunks for a 2-column grid layout
    $chunkSize = 2;
    $chunkedResources = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedResources[$i] = [];
    }

    foreach ($modelToPass as $index => $resource) {
        $chunkIndex = $index % $chunkSize;
        $chunkedResources[$chunkIndex][] = $resource;
    }
@endphp

<x-layout :objects=true object="{{ Str::upper(__('messages.resources')) }}">
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.resources') => Request::url()]" />
    <x-cardcontainer :model=$modelToPass addLink="addresource" :filterOptions=$filterOptions :typeOptions=$typeOptions
        :showNameSort=true :showAuthorSort=true>
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedResources as $chunk)
                <div class="chunk" style="flex: 1; min-width: 48%;">
                    @foreach ($chunk as $resource)
                        <x-card
                            link="resource/{{ $resource->id }}"
                            :editLink="'resource/edit/' . $resource->id"
                            deleteLink="deleteresource/{{ $resource->id }}"
                            :object="$resource"
                            objectType="Resource"
                            image="{{ asset($resource->image) }}"
                            name="{{ $resource->name }}"
                        >
                            {{-- THIS IS THE IMPORTANT CHANGE: All the detailed info goes here --}}
                            <div style="line-height: 1.6;">
                                ● {{__('messages.resourceName')}}: {{ $resource->name }}<br>
                                ● {{__('messages.resourceAuthor')}}: {{ $resource->author }}<br>
                                ● {{__('messages.resourceType')}}: {{ $resource->literaryOrScientific == 1 ? __('messages.literary') : __('messages.scientific') }}<br>
                                ● {{__('messages.resourceSubject')}}: <a href="/subject/{{ $resource->subject_id }}" style="color:var(--text-color);">{{ $resource->subject->name }}</a><br>
                                ● {{__('messages.resourcePublishDate')}}: {{ $resource['publish date'] }}<br>
                                ● {{__('messages.resourcePdfFile')}}: <a href="{{ asset($resource->pdf_file) }}" target="_blank" style="color:var(--text-color);">{{ __('messages.viewPdf') }}</a><br>
                                @if($resource->audio_file)
                                    ● {{__('messages.resourceAudioFile')}}: <a href="{{ asset($resource->audio_file) }}" target="_blank" style="color:var(--text-color);">{{ __('messages.listenAudio') }}</a><br>
                                @endif
                            </div>
                        </x-card>
                    @endforeach
                </div>
            @endforeach
        </div>
    </x-cardcontainer>

    @if ($modelToPass->total() > 0)
        <div class="pagination-info" style="text-align: center; margin-bottom: 2%; font-size: 24px; color: var(--text-color);">
            {{ __('messages.showingItems', [
                'from' => $modelToPass->firstItem(),
                'to' => $modelToPass->lastItem(),
                'total' => $modelToPass->total(),
                'items' => __('messages.resources')
            ]) }}
        </div>
    @endif

    @if ($num > 10)
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
    document.addEventListener('DOMContentLoaded', function () {
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
            const selectedTypes = Array.from(document.querySelectorAll('input[name="type[]"]:checked'))
                .map(el => el.value);

            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);
            selectedSubjects.forEach(subject => params.append('subjects[]', subject));
            selectedTypes.forEach(type => params.append('type[]', type));

            // Clear existing content while fetching
            dynamicContent.innerHTML = '';
            paginationInfoContainer.innerHTML = '';
            paginationContainer.innerHTML = '';


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
                        // If no pagination info in response, determine if it should be shown
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data; // Parse the full HTML response
                        const totalCountElement = tempDiv.querySelector('.pagination-info');
                        if (totalCountElement) {
                            paginationInfoContainer.innerHTML = totalCountElement.innerHTML;
                        } else {
                            // Fallback if pagination-info is not present but items exist
                            const cardElements = doc.querySelectorAll('.chunk x-card').length;
                            if (cardElements > 0) {
                                // This is a heuristic, ideally the server would send the total count
                                const firstItem = 1;
                                const lastItem = Math.min(10, cardElements); // Assuming 10 items per page
                                paginationInfoContainer.innerHTML = `Showing ${firstItem} to ${lastItem} of ${cardElements} resources`;
                            }
                        }
                    }

                    // Update pagination controls
                    const responsePagination = doc.querySelector('.pagination');
                    if (responsePagination) {
                        paginationContainer.innerHTML = responsePagination.innerHTML;
                    } else {
                           // Check if we should have pagination but it's missing from response
                           const totalCountMatch = paginationInfoContainer.textContent.match(/of (\d+) resources/);
                           const totalCount = totalCountMatch ? parseInt(totalCountMatch[1]) : 0;
                           if (totalCount > 10) {
                               // Reconstruct pagination if needed or handle this edge case
                               // For now, it will just remain empty if missing from server response
                           }
                    }

                    attachCircleEffect();
                    refreshAnimations();
                })
                .catch(error => {
                    console.error('Error fetching results:', error);
                    paginationInfoContainer.innerHTML = '';
                    paginationContainer.innerHTML = '';
                });
        }

        let searchTimeout;
        searchBar.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateResults, 0);
        });

        if (filterForm) {
            filterForm.addEventListener('change', updateResults);
        }

        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateResults);
        });

        attachCircleEffect();
    });
</script>
