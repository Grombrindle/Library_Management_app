@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Teacher;

    $searchQuery = request('search');
    $sort = request('sort', 'newest');
    $teacher = Teacher::where('userName', Auth::user()->userName)->first();
    $modelToPass = $teacher
        ? $teacher->courseRequests()->latest()->paginate(10)
        : collect(); // fallback to empty if not found

    $chunkSize = 2;
    $chunkedRequests = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedRequests[$i] = [];
    }
    foreach ($modelToPass as $index => $request) {
        $chunkIndex = $index % $chunkSize;
        $chunkedRequests[$chunkIndex][] = $request;
    }
@endphp

<x-layout :objects=true object="{{ __('messages.courseRequests') }}">
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => Request::url()]" />
    <x-cardcontainer :model=$modelToPass addLink="" :showNameSort=false num="{{ $modelToPass->count() }}" :search=false>
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedRequests as $chunk)
                <div class="chunk">
                    @foreach ($chunk as $request)
                        <x-card object="CourseRequest" image="{{ asset($request->image) }}"
                            link="teacher/course-requests/{{ $request->id }}">
                            ● {{ __('messages.courseRequestName') }}: {{ $request->name }}<br>
                            ● {{ __('messages.courseRequestTeacher') }}: {{ $request->teacher->name ?? 'N/A' }}<br>
                            <br>
                            @if ($request->status === 'pending')
                                <div
                                    style="color: orange; font-weight: bold; margin-top: 1rem; font-size: 3rem; text-align:center;">
                                    {{ strtoupper(__('messages.pending')) }}</div>
                            @elseif($request->status === 'approved')
                                <div
                                    style="color: green; font-weight: bold; margin-top: 1rem; font-size: 3rem; text-align:center;">
                                    {{ strtoupper(__('messages.approved')) }}</div>
                            @else
                                <div
                                    style="color: red; font-weight: bold; margin-top: 1rem; font-size: 3rem; text-align:center;">
                                    {{ strtoupper(__('messages.rejected')) }}</div>
                            @endif
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
                'items' => __('messages.courseRequests'),
            ]) }}
        </div>
    @endif
    @if ($modelToPass->total() > 10)
        <div class="pagination">
            {{ $modelToPass->appends(['search' => $searchQuery, 'sort' => $sort])->links() }}
        </div>
    @endif
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Initial attachment of effects
        attachCircleEffect();
    });
</script>
