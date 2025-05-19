@props(['course' => App\Models\Course::findOrFail(session('course'))])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.course') => url('/courses'), $course->name => Request::url()]" :align=true />
    <x-infocard :editLink="'course/edit/' . $course->id" :deleteLink="'deletecourse/' . $course->id" :object=$course
        objectType="Course" name="{{ $course->name }}" image="{{ asset($course->image) }}">
        ● {{ __('messages.courseName') }}: {{ $course->name }}<br>
        ● {{ __('messages.forSubject') }}: <a href="/subject/{{ $course->subject->id }}">{{$course->subject->name}}</a>
        <br>
        ● {{ __('messages.teacher') }}: <a href="/teacher/{{ $course->teacher->id }}">{{$course->teacher->name}}</a>
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
            <a href="/course/{{ $course->id }}/lectures">{{$course->lectures->count()}}</a> <br>
        @endif

    </x-infocard>

</x-layout>