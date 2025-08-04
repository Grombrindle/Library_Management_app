@props(['course' => App\Models\Course::findOrFail(session('course'))])

<x-layout>
    <x-breadcrumb :links="[
        __('messages.home') => url('/welcome'),
        __('messages.course') => url('/courses'),
        $course->name => Request::url(),
    ]" :align=true />
    <x-infocard :editLink="'course/edit/' . $course->id" :deleteLink="'deletecourse/' . $course->id" :object=$course objectType="Course" name="{{ $course->name }}"
        image="{{ asset($course->image) }}">
        <br>
        ● {{ __('messages.courseName') }}: {{ $course->name }}<br>
        ● {{ __('messages.forSubject') }}: <a href="/subject/{{ $course->subject->id }}">{{ $course->subject->name }} ({{ $course->subject->literaryOrScientific ? "Literary" : "Scientific" }})</a>
        <br>
        ● {{ __('messages.description') }}:{{ $course->description }}<br>
        ● {{ __('messages.teacher') }}: <a href="/teacher/{{ $course->teacher->id }}">{{ $course->teacher->name }}</a>
        <br>
        @if ($course->sources)
            <div>
                ● {{ __('messages.links') }}:
                <br>
                @php
                    $sources = is_array($course->sources) ? $course->sources : json_decode($course->sources, true);
                @endphp
                @if ($sources)
                    @foreach ($sources as $source)
                        <a href="{{ $source['link'] }}" target="_blank" rel="noopener noreferrer">{{ $source['name'] }}</a>
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                @endif
            </div>
        @endif
        ● {{ __('messages.requirements') }}: <br>
        {{ $course->requirements ?: 'No requirements specified' }}
        <br>
        ● {{ __('messages.usersSubscribed') }}: @if ($course->users->count() == 0)
            0
        @else
            <a href="/course/{{ $course->id }}/users/"
                style="color:blue">{{ App\Models\Course::withCount('users')->find(session('course'))->users_count }}</a>
        @endif
        <br>
        ● {{ __('messages.lecturesNum') }}: @if ($course->lectures->count() == 0)
            0
        @else
            <a href="/course/{{ $course->id }}/lectures">{{ $course->lectures->count() }}</a> <br>
        @endif

        <br>
        <br>
        <div style="display:inline-block; vertical-align:middle;">
            @php
                $rating = $course->rating ?? 0;
            @endphp
            @for ($i = 1; $i <= 5; $i++)
                @if ($rating >= $i)
                    {{-- Full star --}}
                    <svg width="20" height="20" fill="gold" viewBox="0 0 20 20" style="display:inline;">
                        <polygon
                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" />
                    </svg>
                @elseif ($rating >= $i - 0.5)
                    {{-- Half star --}}
                    <svg width="20" height="20" viewBox="0 0 20 20" style="display:inline;">
                        <defs>
                            <linearGradient id="half-grad-{{ $course->id }}-{{ $i }}">
                                <stop offset="50%" stop-color="gold" />
                                <stop offset="50%" stop-color="lightgray" />
                            </linearGradient>
                        </defs>
                        <polygon
                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"
                            fill="url(#half-grad-{{ $course->id }}-{{ $i }})" />
                    </svg>
                @else
                    {{-- Empty star --}}
                    <svg width="20" height="20" fill="lightgray" viewBox="0 0 20 20" style="display:inline;">
                        <polygon
                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36" />
                    </svg>
                @endif
            @endfor
            <span>({{ number_format($rating, 1) }})</span>
            <span>({{ $course->ratings->count() }} reviews)</span>
            <!-- <span>({{ $course->ratings->count() }})</span> -->

        </div>
    </x-infocard>

</x-layout>
