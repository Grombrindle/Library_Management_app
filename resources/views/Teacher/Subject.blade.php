@props(['subject' => App\Models\Subject::findOrFail(session('subject'))])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.yourSubjects') => url('/subjects'), $subject->name => url(Request::url())]" align=true />
    <x-infocard :editLink=null editLecturesLink="subject/{{ $subject->id }}/lectures"
        editSubscriptionsLink="subject/{{ $subject->id }}/users" lecturesCount="{{ $subject->lecturesCount }}"
        :object=$subject objectType="Subject" image="{{ asset($subject->image) }}" name="{{ $subject->name }}"
        warning="{{ __('messages.deleteSubjectWarning') }}" :addCourse=true>
        ● {{ __('messages.subjectName') }}: {{ $subject->name }}<br>

        @if (App\Models\Subject::withCount('teachers')->find(session('subject'))->teachers_count == 1)
            ● {{ __('messages.teacher') }}:
            @foreach ($subject->teachers as $teacher)
                <br>
                <span>
                    {{ $teacher->name }} ({{ __('messages.you') }})
                </span>
            @endforeach
        @elseif(App\Models\Subject::withCount('teachers')->find(session('subject'))->teachers_count == 0)
            ● {{ __('messages.teachers') }}: {{ __('messages.none') }}
        @else
            ● {{ __('messages.teachers') }}:<br>[
            @foreach ($subject->teachers as $teacher)
                <span>
                    {{ $teacher->name }} @if ($teacher->userName == Auth::user()->userName)
                        ({{ __('messages.you') }})
                    @endif
                </span>
                @if (!$loop->last)
                    -
                @endif
            @endforeach
            ]
        @endif
        <br>
        ● {{ __('messages.coursesFromYou') }}:
        @if ($subject->courses->where('teacher_id', auth()->user()->teacher_id)->count() == 0)
            0
        @else
            <a
                href="{{$subject->id}}/courses">{{ $subject->courses->where('teacher_id', auth()->user()->teacher_id)->count() }}</a>
        @endif
        <br>
    </x-infocard>

</x-layout>