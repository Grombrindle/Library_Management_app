@props(['course' => App\Models\Course::findOrFail(session('course'))])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.yourCourses') => url('/courses'), $course->name => url(Request::url())]" align=true />
    <x-infocard :editLink="'course/edit/' . $course->id" :deleteLink="'deletecourse/' . $course->id"
        editLecturesLink="course/{{ $course->id }}/lectures" editSubscriptionsLink="course/{{ $course->id }}/users"
        lecturesCount="{{ $course->lecturesCount }}"
        subscriptionsCount="{{ App\Models\Course::withCount('users')->find(session('course'))->users_count }}"
        :object=$course objectType="Course" image="{{ asset($course->image) }}" name="{{ $course->name }}"
        warning="{{ __('messages.deleteCourseWarning') }}" :addLecture=true>
        <br>
        ● {{ __('messages.courseName') }}: {{ $course->name }}<br>
        ● {{ __('messages.description') }}: {{ $course->description }}<br>
        ● {{ __('messages.forSubject') }}: {{ $course->subject->name }}<br>
        ● {{ __('messages.lecturesNum') }}:
        @if ($course->lectures->count() == 0)
            0
        @else
            <a href="/course/{{ $course->id }}/lectures" style="color:blue">{{ $course->lectures->count() }}</a>
        @endif
        <br>
        ● {{ __('messages.usersSubscribed') }}:
        <span>{{ App\Models\Course::withCount('users')->find(session('course'))->users_count }}</span>

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
        </div>
    </x-infocard>

</x-layout>