@props(['teacher' => App\Models\Teacher::findOrFail(session('teacher'))])

<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.teachers') => url('/teacher'), $teacher->name => Request::url()]" align=true />
    <x-infocard :editLink="'teacher/edit/' . $teacher->id" deleteLink="deleteteacher/{{ $teacher->id }}" :object=$teacher objectType="Teacher"
        image="{{ asset($teacher->image) }}" name="{{ $teacher->name }}">
        ● {{__('messages.teacherName')}}: {{ $teacher->name }}<br>
        ● {{__('messages.teacherUserName')}}: {{ $teacher->userName }}<br>
        ● {{__('messages.teacherNumber')}}: {{ $teacher->countryCode }} {{ $teacher->number }}<br>
        @if ($teacher->subjects->count() == 0)
            ● {{__('messages.subjects')}}: none
            <br>
        @elseif($teacher->subjects->count() == 1)
            ● {{__('messages.subject')}}:
            <div>
                @foreach ($teacher->subjects as $subject)
                    <a href="/subject/{{ $subject->id }}" style="color:blue;">
                        {{ $subject->name }}
                    </a>
                @endforeach
            </div>
        @else
            ● {{__('messages.subjects')}}:
            <div>
                <div>
                    [
                    @foreach ($teacher->subjects as $subject)
                        <a href="/subject/{{ $subject->id }}" style="color:blue;">
                            {{ $subject->name }}
                        </a>
                        @if (!$loop->last)
                            -
                        @endif
                    @endforeach
                    ]
                </div>
            </div>

        @endif

        @if ($teacher->courses->count() == 0)
            ● {{__('messages.courses')}}: none
        @elseif($teacher->courses->count() == 1)
            ● {{__('messages.course')}}:
            <div>
                @foreach ($teacher->courses as $course)
                    <a href="/course/{{ $course->id }}" style="color:blue;">
                        {{ $course->name }}
                    </a>
                @endforeach
            </div>
        @else
            ● {{__('messages.courses')}}:
            <div>
                <div>
                    [
                    @foreach ($teacher->courses as $course)
                        <a href="/course/{{ $course->id }}" style="color:blue;">
                            {{ $course->name }}
                        </a>
                        @if (!$loop->last)
                            -
                        @endif
                    @endforeach
                    ]
                </div>
            </div>

        @endif
        @php
            $links = json_decode($teacher->links, true);
        @endphp
        ● {{__('messages.links')}}:
        <br>
        @if ($links['Facebook'])
            <a href="{{ $links['Facebook'] }}" target="_blank">Facebook</a>
            @if ($links['Telegram'])
                -
            @endif
        @endif
        @if ($links['Telegram'])
            <a href="{{ $links['Telegram'] }}">Telegram</a>
        @endif
    </x-infocard>

</x-layout>
