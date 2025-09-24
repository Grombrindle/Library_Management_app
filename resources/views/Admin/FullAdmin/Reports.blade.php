@php
    $modelToPass = App\Models\Report::query()->orderByDesc('created_at')->paginate(10);

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

<x-layout :objects=true object="{{ __('messages.reports') }}">
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.reports') => url('/reports')]" />

    <x-cardcontainer :model="$modelToPass" :addLink=null models="Reports">
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row;gap:10px;">
            @foreach ($chunkedReports as $chunk)
                <div class="chunk">
                    @forelse ($chunk as $report)
                        <x-card link="/report/{{ $report->id }}" object="Report">
                            ● {{ __('messages.reportedBy') }}: {{ $report->user->userName ?? 'Unknown' }}<br>
                            ● {{ __('messages.type') }}: {{ ucfirst($report->type ?? 'N/A') }}<br>
                            ● {{ __('messages.reasons') }}:
                            {{ is_array($report->reasons) ? implode(', ', $report->reasons) : $report->reasons ?? __('messages.none') }}<br>
                            @if ($report->reason)
                                ● {{ __('messages.reason') }}: {{ $report->reason }}<br>
                            @endif
                            @if ($report->lecture_comment)
                                ● {{ __('messages.lectureComment') }}: {{ $report->lecture_comment }}<br>
                            @endif
                            @if ($report->course_comment)
                                ● {{ __('messages.courseComment') }}: {{ $report->course_comment }}<br>
                            @endif
                            @if ($report->resource_comment)
                                ● {{ __('messages.resourceComment') }}: {{ $report->resource_comment }}<br>
                            @endif
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
