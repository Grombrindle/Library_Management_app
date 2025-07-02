@props(['resource' => App\Models\Resource::findOrFail(session('resource'))])
<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.resources') => url('/resources'), $resource->name => Request::url()]" align=true />
    <x-infocard :editLink="'resource/edit/' . $resource->id" deleteLink="deleteresource/{{ $resource->id }}" :object=$resource objectType="Resource"
        image="{{ asset($resource->image) }}" name="{{ $resource->name }}">
        <br>
        ● {{__('messages.resourceName')}}: {{ $resource->name }}<br>
        ● {{__('messages.resourceAuthor')}}: {{ $resource->author }}<br>
        ● {{__('messages.resourceDescription')}}: {{ $resource->description }}<br>
        ● {{__('messages.resourceType')}}: {{ $resource->literaryOrScientific == 1 ? __('messages.literary') : __('messages.scientific') }}<br>
        ● {{__('messages.resourceSubject')}}: <a href="/subject/{{ $resource->subject_id }}" style="color:var(--text-color);">{{ $resource->subject->name }}</a><br>
        ● {{__('messages.resourcePublishDate')}}: {{ $resource['publish date'] }}<br>
        ● {{__('messages.resourcePdfFile')}}: <a href="{{ asset($resource->pdf_file) }}" target="_blank" class="button">{{ __('messages.viewPdf') }}</a><br>
        @if($resource->audio_file)
            ● {{__('messages.resourceAudioFile')}}: <a href="{{ asset($resource->audio_file) }}" target="_blank" class="button">{{ __('messages.listenAudio') }}</a><br>
        @endif
    </x-infocard>
</x-layout>
