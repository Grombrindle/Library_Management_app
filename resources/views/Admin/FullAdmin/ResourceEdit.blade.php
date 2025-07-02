@props(['resource' => App\Models\Resource::findOrFail(session('resource'))])
<x-layout>
    <x-editcard link="editresource/{{ session('resource') }}" object="Resource" :objectModel="$resource" :image="true">
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_name">
                {{ __('messages.resourceName') }}:
            </label>
            <input type="text" name="resource_name" id="resource_name" value="{{ $resource->name }}"
                style="height:20%; text-align:center; font-size:40%; width:fit-content; margin-bottom:10%;">
        </div>
        @error('resource_name')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_description">
                {{ __('messages.resourceDescription') }}:
            </label>
            <textarea name="resource_description" id="resource_description" style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;max-height:500px;">{{ $resource->description }}</textarea>
        </div>
        @error('resource_description')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_author">
                {{ __('messages.resourceAuthor') }}:
            </label>
            <input type="text" name="resource_author" id="resource_author" value="{{ $resource->author }}"
                style="height:20%; text-align:center; font-size:40%; width:fit-content;">
        </div>
        @error('resource_author')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_publish_date">
                {{ __('messages.resourcePublishDate') }}:
            </label>
            <input type="date" name="resource_publish_date" id="resource_publish_date" value="{{ $resource['publish date'] }}">
        </div>
        @error('resource_publish_date')
            <div class="error">{{ $message }}</div>
        @enderror
    </x-editcard>
</x-layout>