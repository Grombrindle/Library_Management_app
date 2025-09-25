@props(['report' => App\Models\Report::findOrFail(session('report'))])

<x-layout :nav=false>
    {{-- <x-breadcrumb :links="[
        __('messages.home') => url('/welcome'),
        __('messages.reports') => url('/reports'),
        __('messages.report') => Request::url(),
    ]" /> --}}

    <x-infocard :object="$report" objectType="Report" id="{{ $report->id }}" :request=true>
        • {{ __('messages.reportedUser') }}: {{ App\Models\User::find($report->user_id)->userName ?? 'Unknown' }}<br>
        {{-- • {{ __('messages.type') }}: {{ ucfirst($report->type ?? 'N/A') }}<br> --}}
        {{-- @if ($report->message) --}}
        {{-- @endif --}}

        @if ($report->lecture_rating_id)
            • {{ __('messages.comment') }}:
            @if ($report->lecture_comment)
                {{ $report->lecture_comment }}
            @else
                {{ __('messages.noComment') }}
            @endif <br>
            {{ __('messages.onLecture') }}: <a
                href="/lecture/{{ App\Models\LectureRating::find($report->lecture_rating_id)->lecture->id }}">{{ App\Models\LectureRating::find($report->lecture_rating_id)->lecture->name }}</a>
            <br>
        @elseif($report->course_rating_id)
            • {{ __('messages.comment') }}:
            @if ($report->course_comment)
                {{ $report->course_comment }}
            @else
                {{ __('messages.noComment') }}
            @endif <br>
            • {{ __('messages.onCourse') }}: <a
                href="/course/{{ App\Models\CourseRating::find($report->course_rating_id)->course->id }}">
                {{ App\Models\CourseRating::find($report->course_rating_id)->course->name }} </a> <br>
        @elseif($report->resource_rating_id)
            • {{ __('messages.comment') }}:
            @if ($report->resource_comment)
                {{ $report->resource_comment }}
            @else
                {{ __('messages.noComment') }}
            @endif <br>
            • {{ __('messages.onResource') }}: <a
                href="/resource/{{ App\Models\ResourceRating::find($report->resource_rating_id)->resource->id }}">
                {{ App\Models\ResourceRating::find($report->resource_rating_id)->resource->name }} </a> <br>
        @elseif($report->teacher_rating_id)
            • {{ __('messages.comment') }}: @if ($report->teacher_comment)
                {{ $report->teacher_comment }}
            @else
                {{ __('messages.noComment') }}
            @endif <br>
            • {{ __('messages.onTeacher') }}: <a
                href="/teacher/{{ App\Models\TeacherRating::find($report->teacher_rating_id)->teacher->id }}">
                {{ App\Models\TeacherRating::find($report->teacher_rating_id)->teacher->name }} </a> <br>
        @else
            • {{ __('messages.comment') }}: <br>
        @endif
        <br>
        • {{ __('messages.reportedBy') }}: {{ $report->user->userName ?? 'Unknown' }}<br>

        • {{ __('messages.reasons') }}:
        {{ is_array($report->reasons) ? implode(', ', $report->reasons) : $report->reasons }}<br>

        • {{ __('messages.message') }}: @if ($report->message)
            {{ $report->message }}
        @else
            {{ __('messages.noMessage') }}
        @endif
        <br>
        <br>
        • {{ __('messages.timesWarned') }}: {{ App\Models\User::find($report->user_id)->counter }}<br>

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
    </x-infocard>

    @php
        $ratingId =
            $report->lecture_rating_id ??
            ($report->course_rating_id ?? ($report->resource_rating_id ?? $report->teacher_rating_id));
    @endphp
    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
        <form method="POST" action="{{ url('user/ban/' . $report->user_id . '/' . $report->type . '/' . $ratingId) }}">
            @csrf
            <button type="submit" class="button"
                style="background: @if ($report->status != 'PENDING') darkgray @else red @endif; color: white;"
                @if ($report->status != 'PENDING') disabled @endif">{{ __('messages.banUser') }}</button>
        </form>
        <form method="POST" action="{{ url(path: 'user/warn/' . $report->id) }}">
            @csrf
            <button type="submit" class="button"
                style="background: @if ($report->status != 'PENDING') darkgray @else orange @endif; color: white;"
                @if ($report->status != 'PENDING') disabled @endif>{{ __('messages.warnUser') }}</button>
        </form>
    </div>
    <form method="POST" action="{{ url('/ignore/' . $report->id) }}">
        @csrf
        <button type="submit" class="button"
            style="background: @if ($report->status != 'PENDING') darkgray @else dodgerblue @endif; color: white;"
            @if ($report->status != 'PENDING') disabled @endif">{{ __('messages.ignore') }}</button>
    </form>
</x-layout>
