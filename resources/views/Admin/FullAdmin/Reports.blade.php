@php
    // Placeholder: In real use, pass $reports from the controller
    $reports = $reports ?? collect();
    $types = ['all' => 'All', 'lecture' => 'Lecture', 'course' => 'Course', 'book' => 'Book/Resource'];
    $selectedType = request('type', 'all');
@endphp

<x-layout :objects=true object="{{ __('messages.reports') }}">
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.reports') => url('/reports')]" />

    <div style="margin-bottom: 1rem;">
        <form method="GET" action="/reports">
            <label for="type">{{ __('messages.filterByType') }}:</label>
            <select name="type" id="type" onchange="this.form.submit()">
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" @if ($selectedType == $key) selected @endif>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <x-cardcontainer :model="$reports">
        <div id="dynamic-content" style="width:100%; display:flex; flex-direction:row; flex-wrap:wrap; gap:1rem;">
            @forelse ($reports as $report)
                <x-card link="/report/{{ $report->id }}" object="Report">
                    • {{ __('messages.reportedBy') }}: {{ $report->user->userName ?? 'Unknown' }}<br>
                    • {{ __('messages.type') }}: {{ ucfirst($report->type ?? 'N/A') }}<br>
                    • {{ __('messages.reasons') }}: {{ is_array($report->reasons) ? implode(', ', $report->reasons) : $report->reasons }}<br>
                    @if ($report->reason)
                        • {{ __('messages.reason') }}: {{ $report->reason }}<br>
                    @endif
                    @if ($report->lecture_comment)
                        • {{ __('messages.lectureComment') }}: {{ $report->lecture_comment }}<br>
                    @endif
                    @if ($report->course_comment)
                        • {{ __('messages.courseComment') }}: {{ $report->course_comment }}<br>
                    @endif
                    @if ($report->book_comment)
                        • {{ __('messages.bookComment') }}: {{ $report->book_comment }}<br>
                    @endif
                </x-card>
            @empty
                <div>{{ __('messages.noReports') }}</div>
            @endforelse
        </div>
    </x-cardcontainer>
</x-layout>
