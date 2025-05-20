@props(['course' => App\Models\Course::findOrFail(session('course'))])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.yourCourses') => url('/courses'), $course->name => url(Request::url())]" align=true />
    <x-infocard :editLink="'course/edit/' . $course->id" :deleteLink="'deletecourse/' . $course->id" editLecturesLink="course/{{ $course->id }}/lectures"
        editSubscriptionsLink="course/{{ $course->id }}/users" lecturesCount="{{ $course->lecturesCount }}"
        subscriptionsCount="{{ App\Models\Course::withCount('users')->find(session('course'))->users_count }}"
        :object=$course objectType="Course" image="{{ asset($course->image) }}" name="{{ $course->name }}"
        warning="{{ __('messages.deleteCourseWarning') }}" :addLecture=true>
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
    </x-infocard>

</x-layout>