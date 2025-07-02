@props(['resource' => App\Models\Resource::findOrFail(session('resource'))])
<x-layout>
    <x-editcard link="editresource/{{ session('resource') }}" object="Resource" :objectModel="$resource">
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
            <label for="resource_pdf_file">
                {{ __('messages.resourcePdfFile') }}:
            </label>
            <input type="file" name="resource_pdf_file" id="resource_pdf_file" accept="application/pdf">
            @if($resource->pdf_file)
                <a href="{{ asset($resource->pdf_file) }}" target="_blank">{{ __('messages.viewCurrentPdf') }}</a>
            @endif
        </div>
        @error('resource_pdf_file')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_audio_file">
                {{ __('messages.resourceAudioFile') }} ({{ __('messages.optional') }}):
            </label>
            <input type="file" name="resource_audio_file" id="resource_audio_file" accept="audio/*">
            @if($resource->audio_file)
                <a href="{{ asset($resource->audio_file) }}" target="_blank">{{ __('messages.listenCurrentAudio') }}</a>
            @endif
        </div>
        @error('resource_audio_file')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_image">
                {{ __('messages.resourceImage') }}:
            </label>
            <input type="file" name="resource_image" id="resource_image" accept="image/*">
            @if($resource->image)
                <img src="{{ asset($resource->image) }}" alt="Current Image" style="max-width:100px; max-height:100px;">
            @endif
        </div>
        @error('resource_image')
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
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_literary_or_scientific">
                {{ __('messages.resourceType') }}:
            </label>
            <select name="resource_literary_or_scientific" id="resource_literary_or_scientific">
                <option value="1" @if($resource->literaryOrScientific == 1) selected @endif>{{ __('messages.literary') }}</option>
                <option value="2" @if($resource->literaryOrScientific == 2) selected @endif>{{ __('messages.scientific') }}</option>
            </select>
        </div>
        @error('resource_literary_or_scientific')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_subject_id">
                {{ __('messages.resourceSubject') }}:
            </label>
            <select name="resource_subject_id" id="resource_subject_id">
                @foreach(App\Models\Subject::all() as $subject)
                    <option value="{{ $subject->id }}" @if($resource->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        @error('resource_subject_id')
            <div class="error">{{ $message }}</div>
        @enderror
    </x-editcard>
</x-layout> 