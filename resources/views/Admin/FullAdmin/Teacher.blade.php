@props(['teacher' => App\Models\Teacher::findOrFail(session('teacher'))])

<x-layout>
    <x-breadcrumb :links="[
        __('messages.home') => url('/welcome'),
        __('messages.teachers') => url('/teacher'),
        $teacher->name => Request::url(),
    ]" align=true />
    <x-infocard :editLink="'teacher/edit/' . $teacher->id" deleteLink="deleteteacher/{{ $teacher->id }}" :object=$teacher objectType="Teacher"
        image="{{ asset($teacher->image) }}" name="{{ $teacher->name }}">
        <br>
        ● {{ __('messages.teacherName') }}: {{ $teacher->name }}<br>
        ● {{ __('messages.teacherUserName') }}: {{ $teacher->userName }}<br>
        ● {{ __('messages.teacherNumber') }}: {{ $teacher->countryCode }} {{ $teacher->number }}<br>
        ● {{ __('messages.teacherDescription') }}: {{ $teacher->description }}<br>
        @if ($teacher->subjects->count() == 0)
            ● {{ __('messages.subjects') }}: none
            <br>
        @elseif($teacher->subjects->count() == 1)
            ● {{ __('messages.subject') }}:
            <div>
                @foreach ($teacher->subjects as $subject)
                    <a href="/subject/{{ $subject->id }}" style="color:blue;">
                        {{ $subject->name }}
                    </a>
                @endforeach
            </div>
        @else
            ● {{ __('messages.subjects') }}:
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
            ● {{ __('messages.courses') }}: none <br>
        @elseif($teacher->courses->count() == 1)
            ● {{ __('messages.course') }}:
            <div>
                @foreach ($teacher->courses as $course)
                    <a href="/course/{{ $course->id }}" style="color:blue;">
                        {{ $course->name }}
                    </a>
                @endforeach
            </div>
        @else
            ● {{ __('messages.courses') }}:
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
        ● {{ __('messages.links') }}:
        <br>
        @if ($links['Facebook'])
            <a href="{{ $links['Facebook'] }}" target="_blank">Facebook</a>
            @if ($links['Instagram'] || $links['Telegram'] || $links['YouTube'])
                -
            @endif
        @endif
        @if ($links['Instagram'])
            <a href="{{ $links['Instagram'] }}" target="_blank">Instagram</a>
            @if ($links['Telegram'] || $links['YouTube'])
                -
            @endif
        @endif
        @if ($links['Telegram'])
            <a href="{{ $links['Telegram'] }}" target="_blank">Telegram</a>
            @if ($links['YouTube'])
                -
            @endif
        @endif
        @if ($links['YouTube'])
            <a href="{{ $links['YouTube'] }}">YouTube</a>
        @endif
        <br>
        <br>
        <div style="display:inline-block; vertical-align:middle;">
            @php
                $rating = $teacher->rating ?? 0;
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
                            <linearGradient id="half-grad-{{ $teacher->id }}-{{ $i }}">
                                <stop offset="50%" stop-color="gold" />
                                <stop offset="50%" stop-color="lightgray" />
                            </linearGradient>
                        </defs>
                        <polygon
                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"
                            fill="url(#half-grad-{{ $teacher->id }}-{{ $i }})" />
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
            <span>({{ $teacher->ratings->count() }} reviews)</span>

        </div>
    </x-infocard>

</x-layout>
