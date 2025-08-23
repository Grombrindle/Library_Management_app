@props(['subject' => App\Models\Subject::findOrFail(session('subject'))])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.subjects') => url('/subjects'), $subject->name => url(Request::url())]" align=true />
    <x-infocard :editLink="'subject/edit/' . $subject->id" deleteLink="deletesubject/{{ $subject->id }}"
        editLecturesLink="subject/{{ $subject->id }}/lectures" editSubscriptionsLink="subject/{{ $subject->id }}/users"
        lecturesCount="{{ $subject->lecturesCount }}" :object=$subject objectType="Subject"
        image="{{ asset($subject->image) }}" name="{{ $subject->name }}"
        warning="{{ __('messages.deleteSubjectWarning') }}">
        <br>
        ● {{ __('messages.subjectName') }}: {{ $subject->name }}<br>
        ● {{ __('messages.subjectType') }}: {{  $subject->literaryOrScientific == 0 ? __('messages.literary') : __('messages.scientific')}} <br>
        ● {{ __('messages.coursesNum') }}:
        @if ($subject->courses->count())
            <a href="/subject/{{ $subject->id }}/courses"> {{ $subject->courses->count() }}</a>
        @else
            0
        @endif
        <br>
        @if (App\Models\Subject::withCount('teachers')->find(session('subject'))->teachers_count == 1)
            ● {{ __('messages.teacher') }}:
            @foreach ($subject->teachers as $teacher)
                <br>
                <a href="/teacher/{{ $teacher->id }}" style="color:blue">
                    {{ $teacher->name }}
                </a>
            @endforeach
        @elseif(App\Models\Subject::withCount('teachers')->find(session('subject'))->teachers_count == 0)
            ● {{ __('messages.teachers') }}: {{ __('messages.none') }}
        @else
            ● {{ __('messages.teachers') }}:<br>[
            @foreach ($subject->teachers as $teacher)
                <a href="/teacher/{{ $teacher->id }}" style="color:blue">
                    {{ $teacher->name }}
                </a>
                @if (!$loop->last)
                    -
                @endif
            @endforeach
            ]
        @endif

        <br>
    </x-infocard>

</x-layout>