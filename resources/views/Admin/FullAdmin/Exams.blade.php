@props(['exams' => null, 'num' => App\Models\Exam::count()])

@php
    // Get the search query and filter values from the request
    $searchQuery = request('search');
    $sort = request('sort', 'newest'); // Default to 'newest'
    $selectedSubjects = request('subjects', []);
    $filterNone = request('none', false);
    $yearFilter = request('year', '');
    $dateFrom = request('date_from', '');
    $dateTo = request('date_to', '');

    // Normalize the search query
    $searchTerms = $searchQuery ? array_filter(explode(' ', strtolower(trim($searchQuery)))) : [];

    // Initialize base query
    if ($exams !== null) {
        $query = App\Models\Exam::whereIn('id', $exams->pluck('id'));
        $examsCount = $query->count(); // Get count before pagination
    } else {
        $query = App\Models\Exam::query();
        $examsCount = $query->count(); // Get total count
    }

    // Apply filters
    $query
        ->when($searchQuery, function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(title) LIKE ?', ["%{$term}%"])->orWhereRaw('LOWER(description) LIKE ?', [
                        "%{$term}%",
                    ]);
                });
            }
        })
        ->when($selectedSubjects, function ($query) use ($selectedSubjects) {
            $query->whereHas('subject', function ($q) use ($selectedSubjects) {
                $q->whereIn('id', $selectedSubjects);
            });
        })
        ->when($yearFilter, function ($query) use ($yearFilter) {
            $query->whereYear('date', $yearFilter);
        })
        ->when($dateFrom, function ($query) use ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        })
        ->when($dateTo, function ($query) use ($dateTo) {
            $query->where('date', '<=', $dateTo);
        });

    // Apply sorting
    if ($sort === 'title-a-z') {
        $query->orderByRaw('LOWER(title) ASC');
    } elseif ($sort === 'title-z-a') {
        $query->orderByRaw('LOWER(title) DESC');
    } elseif ($sort === 'date-newest') {
        $query->orderBy('date', 'desc');
    } elseif ($sort === 'date-oldest') {
        $query->orderBy('date', 'asc');
    } elseif ($sort === 'pages-most') {
        $query->orderBy('pages', 'desc');
    } elseif ($sort === 'pages-least') {
        $query->orderBy('pages', 'asc');
    } elseif ($sort === 'newest') {
        $query->orderBy('created_at', 'desc');
    } elseif ($sort === 'oldest') {
        $query->orderBy('created_at', 'asc');
    }

    // Get filtered count before pagination
    $filteredCount = $query->count();
    $modelToPass = $query->paginate(10);

    // Prepare filter options
    $filterOptions = App\Models\Subject::pluck('name', 'id')->toArray();

    // Split exams into chunks
    $chunkSize = 2;
    $chunkedExams = array_fill(0, $chunkSize, []);

    foreach ($modelToPass as $index => $exam) {
        $chunkIndex = $index % $chunkSize;
        $chunkedExams[$chunkIndex][] = $exam;
    }
@endphp

<x-layout :objects=true object="{{ __('messages.exams') }}">
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.exams') => Request::url()]" />

    <x-cardcontainer :model=$modelToPass addLink="addexam" :filterOptions=$filterOptions :showSubjectCountFilter=false
        :showUsernameSort=false :showNameSort=true>
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedExams as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $exam)
                        <x-card link="exam/{{ $exam->id }}" image="{{ asset($exam->thumbnailUrl) }}" object="Exam">
                            ● {{ __('messages.examTitle') }}: {{ $exam->title }}<br>
                            ● {{ __('messages.examDescription') }}: {{ Str::limit($exam->description, 100) }}<br>
                            ● {{ __('messages.subject') }}: {{ $exam->subject->name }} ({{ $exam->subject->literaryOrScientific ? "Scientific" : "Literary" }})<br>
                            ● {{ __('messages.pages') }}: {{ $exam->pages }}<br>
                            ● {{ __('messages.examDate') }}: {{ $exam->date->format('Y-m-d') }}<br>
                            <br>
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
                'items' => __('messages.exams'),
            ]) }}
        </div>
    @endif

    @if ($modelToPass->total() > 10)
        <div class="pagination">
            {{ $modelToPass->appends([
                    'search' => $searchQuery,
                    'sort' => $sort,
                    'subjects' => $selectedSubjects,
                    'year' => $yearFilter,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
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
            'input[type="checkbox"][name^="subjects"], input[name="none"]');
        const paginationInfoContainer = document.querySelector('.pagination-info');
        const paginationContainer = document.querySelector('.pagination');

        // Function to fetch and update results
        function updateResults() {
            const query = searchBar.value;
            const selectedSort = document.querySelector('input[name="sort"]:checked')?.value || 'newest';
            const selectedSubjects = Array.from(document.querySelectorAll('input[name="subjects[]"]:checked'))
                .map(el => el.value);
            const filterNone = document.getElementById('filter-none')?.checked || false;
            const yearFilter = document.querySelector('input[name="year"]')?.value || '';
            const dateFrom = document.querySelector('input[name="date_from"]')?.value || '';
            const dateTo = document.querySelector('input[name="date_to"]')?.value || '';

            // Build query string
            const params = new URLSearchParams();
            params.set('search', query);
            params.set('sort', selectedSort);
            selectedSubjects.forEach(subject => params.append('subjects[]', subject));
            if (filterNone) params.set('none', 'true');
            if (yearFilter) params.set('year', yearFilter);
            if (dateFrom) params.set('date_from', dateFrom);
            if (dateTo) params.set('date_to', dateTo);

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
                        const countMatch = doc.body.textContent.match(/of (\d+) exams/);
                        const totalCount = countMatch ? parseInt(countMatch[1]) : 0;

                        if (totalCount > 1) {
                            // Reconstruct pagination info
                            const firstItem = 1;
                            const lastItem = Math.min(10, totalCount);
                            paginationInfoContainer.innerHTML =
                                `Showing ${firstItem} to ${lastItem} of ${totalCount} exams`;
                        } else {
                            paginationInfoContainer.innerHTML = '';
                        }
                    }

                    // Update pagination controls (show if >10 results)
                    const responsePagination = doc.querySelector('.pagination');
                    if (responsePagination) {
                        paginationContainer.innerHTML = responsePagination.innerHTML;
                    } else {
                        paginationContainer.innerHTML = '';
                    }

                    attachCircleEffect();
                    refreshAnimations();
                })
                .catch(error => {
                    console.error('Error:', error);
                    dynamicContent.innerHTML = '<div class="error-message">Failed to load exams</div>';
                    paginationInfoContainer.innerHTML = '';
                    paginationContainer.innerHTML = '';
                });
        }

        // Handle search input with debounce
        let searchTimeout;
        if (searchBar) {
            searchBar.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateResults, 0);
            });
        }

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