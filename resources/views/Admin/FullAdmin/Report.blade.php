@props(['report'])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.reports') => url('/reports'), __('messages.report') . ' #' . $report->id => Request::url()]" />

    <x-infocard :object="$report" objectType="Report" id="{{$report->id}}">
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
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <form method="POST" action="{{ url('/user/ban/' . $report->user_id) }}">
                @csrf
                <button type="submit" class="button" style="background: red; color: white;">{{ __('messages.banUser') }}</button>
            </form>
            <form method="POST" action="#">
                @csrf
                <button type="button" class="button" style="background: orange; color: white;" disabled>{{ __('messages.warnUser') }}</button>
            </form>
        </div>
    </x-infocard>
</x-layout>
