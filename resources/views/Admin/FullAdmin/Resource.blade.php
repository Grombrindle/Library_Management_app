@props(['resource' => App\Models\Resource::findOrFail(session('resource'))])
<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.resources') => url('/resources'), $resource->name => Request::url()]" align=true />
    <x-infocard :editLink="'resource/edit/' . $resource->id" deleteLink="deleteresource/{{ $resource->id }}" :object=$resource objectType="Resource" :file=true
        image="{{ asset($resource->image) }}" name="{{ $resource->name }}">
        <br>
        ● {{__('messages.resourceName')}}: {{ $resource->name }}<br>
        ● {{__('messages.resourceAuthor')}}: {{ $resource->author }}<br>
        ● {{__('messages.resourceDescription')}}: {{ $resource->description }}<br>
        ● {{__('messages.resourceSubject')}}: <a href="/subject/{{ $resource->subject_id }}">{{ $resource->subject->name }} ({{ $resource->subject->literaryOrScientific == 0 ? __('messages.literary') : __('messages.scientific') }})</a><br>
        ● {{__('messages.resourcePublishDate')}}: {{ $resource['publish date'] }}<br>
    </x-infocard>
</x-layout>
