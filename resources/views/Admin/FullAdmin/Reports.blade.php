@php
    $modelToPass = App\Models\Report::query()
        ->orderByRaw(
            "CASE status WHEN 'PENDING' THEN 1 WHEN 'WARNED' THEN 2 WHEN 'IGNORED' THEN 3 WHEN 'BANNED' THEN 4 ELSE 5 END",
        )
        ->orderByDesc('created_at')
        ->paginate(10);

    // Split Reports into chunks (two columns)
    $chunkSize = 2;
    $chunkedReports = [];
    for ($i = 0; $i < $chunkSize; $i++) {
        $chunkedReports[$i] = [];
    }

    foreach ($modelToPass as $index => $report) {
        $chunkIndex = $index % $chunkSize;
        $chunkedReports[$chunkIndex][] = $report;
    }
@endphp

<x-layout :objects=true object="{{ __('messages.reports') }}" :nav=false>
    {{-- <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.reports') => url('/reports')]" /> --}}

    <x-cardcontainer :model="$modelToPass" :addLink=null models="Reports" :search=false>
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedReports as $chunk)
                <div class="chunk">
                    @forelse ($chunk as $report)
                        <x-card link="/report/{{ $report->id }}" object="Report">
                            ● {{ __('messages.reportedUser') }}:
                            {{ App\Models\User::find($report->user_id)->userName ?? 'Unknown' }}<br>
                            {{-- ● {{ __('messages.type') }}: {{ ucfirst($report->type ?? 'N/A') }}<br> --}}
                            {{-- @if ($report->message) --}}
                            {{-- @endif --}}
                            @if ($report->lecture_rating_id)
                            ● {{ __('messages.comment') }}:
                                @if ($report->lecture_comment)
                                    {{ $report->lecture_comment }}
                                @else
                                    {{ __('messages.noComment') }}
                                @endif <br>
                                ● {{ __('messages.onLecture') }}:{{ App\Models\LectureRating::find($report->lecture_rating_id)->lecture->name }}
                                <br>
                            @elseif($report->course_rating_id)
                                ● {{ __('messages.comment') }}:
                                @if ($report->course_comment)
                                    {{ $report->course_comment }}
                                @else
                                    {{ __('messages.noComment') }}
                                @endif <br>
                                ● {{ __('messages.onCourse') }}:
                                {{ App\Models\CourseRating::find($report->course_rating_id)->course->name }}
                                <br>
                            @elseif($report->resource_rating_id)
                                ● {{ __('messages.comment') }}:
                                @if ($report->resource_comment)
                                    {{ $report->resource_comment }}
                                @else
                                    {{ __('messages.noComment') }}
                                @endif <br>
                                ● {{ __('messages.onResource') }}:
                                {{ App\Models\ResourceRating::find($report->resource_rating_id)->resource->name }}
                                <br>
                            @elseif($report->teacher_rating_id)
                                ● {{ __('messages.comment') }}:
                                @if ($report->teacher_comment)
                                    {{ $report->teacher_comment }}
                                @else
                                    {{ __('messages.noComment') }}
                                @endif <br>
                                ● {{ __('messages.onTeacher') }}:
                                {{ App\Models\TeacherRating::find($report->teacher_rating_id)->teacher->name }}
                                <br>
                            @else
                                ● {{ __('messages.comment') }}: {{ __('messages.noComment') }}<br>
                            @endif
                            <br>
                            ● {{ __('messages.reportedBy') }}: {{ $report->user->userName ?? 'Unknown' }}<br>

                            ● {{ __('messages.reasons') }}:
                            {{ is_array($report->reasons) ? implode(', ', $report->reasons) : $report->reasons }}<br>

                            ● {{ __('messages.message') }}: @if ($report->message)
                                {{ $report->message }}
                            @else
                                {{ __('messages.noMessage') }}
                            @endif
                            <br>
                            <br>
                            ● {{ __('messages.timesWarned') }}:
                            {{ App\Models\User::find($report->user_id)->counter }}<br>

                            <br>

                            @if ($report->status == 'IGNORED')
                                <div style="color: dodgerblue; font-weight: bold; margin-top: 1rem; font-size:60px;">
                                    {{ __('messages.ignored') }}</div>
                            @elseif ($report->status == 'WARNED')
                                <div style="color: orange; font-weight: bold; margin-top: 1rem; font-size:60px;">
                                    {{ __('messages.warned') }}</div>
                            @elseif ($report->status == 'BANNED')
                                <div style="color: red; font-weight: bold; margin-top: 1rem; font-size:60px;">
                                    {{ __('messages.banned') }}</div>
                            @endif
                        </x-card>
                    @empty
                    @endforelse
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
                'items' => __('messages.reports'),
            ]) }}
        </div>
    @else
        <div class="pagination-info" style="display: none;"></div>
    @endif

    <div class="pagination" style="@if ($modelToPass->total() <= 10) display:none; @endif">
        {{ $modelToPass->links() }}
    </div>
</x-layout>
