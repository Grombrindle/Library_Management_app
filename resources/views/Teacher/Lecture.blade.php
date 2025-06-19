@props(['lecture' => App\Models\Lecture::findOrFail(session('lecture')), 'subject' => false])

<x-layout>
    <x-breadcrumb :links="array_merge([__('messages.home') => url('/welcome'), __('messages.lectures') => url('/lectures')], [
        $lecture->name => Request::url(),
    ])" align=true />
    <x-infocard :editLink="'lecture/edit/' . $lecture->id" deleteLink="deletelecture/{{ $lecture->id }}"
        :object=$lecture objectType="Lecture" image="{{ asset($lecture->image) }}" name="{{ $lecture->name }}"
        :file=true>
        <br>
        ● {{ __('messages.lectureName') }}: {{ $lecture->name }}<br>
        ● {{ __('messages.lectureDescription') }}: {{ $lecture->description }}<br>
        ● {{ __('messages.fromCourse') }}: <a href="/course/{{ $lecture->course_id }}"
            style="color:blue">{{ App\Models\Course::findOrFail($lecture->course_id)->name }}</a>

        <br>
        <br>
        <div style="display:inline-block; vertical-align:middle;">
            @php
                $rating = $lecture->rating ?? 0;
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
                            <linearGradient id="half-grad-{{ $lecture->id }}-{{ $i }}">
                                <stop offset="50%" stop-color="gold" />
                                <stop offset="50%" stop-color="lightgray" />
                            </linearGradient>
                        </defs>
                        <polygon
                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"
                            fill="url(#half-grad-{{ $lecture->id }}-{{ $i }})" />
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
            <span>({{ $lecture->ratings->count() }} reviews)</span>
        </div>
    </x-infocard>

</x-layout>