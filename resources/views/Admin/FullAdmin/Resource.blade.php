@props(['resource' => App\Models\Resource::findOrFail(session('resource'))])
<x-layout>
    <x-breadcrumb :links="[
        __('messages.home') => url('/welcome'),
        __('messages.resources') => url('/resources'),
        $resource->name => Request::url(),
    ]" align=true />
    <x-infocard :editLink="'resource/edit/' . $resource->id" deleteLink="deleteresource/{{ $resource->id }}" :object=$resource objectType="Resource"
        :file=true image="{{ asset($resource->image) }}" name="{{ $resource->name }}">
        <br>
        ● {{ __('messages.resourceName') }}: {{ $resource->name }}<br>
        ● {{ __('messages.resourceAuthor') }}: {{ $resource->author }}<br>
        ● {{ __('messages.resourceDescription') }}: {{ $resource->description }}<br>
        ● {{ __('messages.resourceSubject') }}: <a
            href="/subject/{{ $resource->subject_id }}">{{ $resource->subject->name }}
            ({{ $resource->subject->literaryOrScientific == 0 ? __('messages.literary') : __('messages.scientific') }})</a><br>
        ● {{ __('messages.resourcePublishDate') }}: {{ $resource['publish date'] }}<br>
        <br>
        <div style="display:inline-block; vertical-align:middle;">
            @php
                $rating = $resource->rating ?? 0;
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
                            <linearGradient id="half-grad-{{ $resource->id }}-{{ $i }}">
                                <stop offset="50%" stop-color="gold" />
                                <stop offset="50%" stop-color="lightgray" />
                            </linearGradient>
                        </defs>
                        <polygon
                            points="10,1 12.59,7.36 19.51,7.36 13.97,11.63 16.56,17.99 10,13.72 3.44,17.99 6.03,11.63 0.49,7.36 7.41,7.36"
                            fill="url(#half-grad-{{ $resource->id }}-{{ $i }})" />
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
            <span>({{ $resource->ratings->count() }} {{ __('messages.reviews') }})</span>
        </div>
    </x-infocard>
</x-layout>
