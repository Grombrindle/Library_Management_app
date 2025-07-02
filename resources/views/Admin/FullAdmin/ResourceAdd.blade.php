<x-layout>
    <x-addcard link="addresource" object="Resource">
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_name">
                {{ __('messages.resourceName') }}:
            </label>
            <input type="text" name="resource_name" id="resource_name" value="{{ old('resource_name') }}"
                autocomplete="off" style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
        </div>
        @error('resource_name')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_description">
                {{ __('messages.resourceDescription') }}:
            </label>
            <textarea name="resource_description" id="resource_description" autocomplete="off" value="{{ old('resource_description') }}"
                style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;max-height:500px;"></textarea>
        </div>
        @error('resource_description')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_author">
                {{ __('messages.resourceAuthor') }}:
            </label>
            <input type="text" name="resource_author" id="resource_author" value="{{ old('resource_author') }}"
                autocomplete="off" style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
        </div>
        @error('resource_author')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_pdf_file">
                {{ __('messages.resourcePdfFile') }}:
            </label>
            <input type="file" name="resource_pdf_file" id="resource_pdf_file" accept="application/pdf" required>
        </div>
        @error('resource_pdf_file')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_audio_file">
                {{ __('messages.resourceAudioFile') }} ({{ __('messages.optional') }}):
            </label>
            <input type="file" name="resource_audio_file" id="resource_audio_file" accept="audio/*">
        </div>
        @error('resource_audio_file')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_image">
                {{ __('messages.resourceImage') }}:
            </label>
            <input type="file" name="resource_image" id="resource_image" accept="image/*">
        </div>
        @error('resource_image')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_publish_date">
                {{ __('messages.resourcePublishDate') }}:
            </label>
            <input type="date" name="resource_publish_date" id="resource_publish_date" value="{{ old('resource_publish_date') }}" required>
        </div>
        @error('resource_publish_date')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_literary_or_scientific">
                {{ __('messages.resourceType') }}:
            </label>
            <select name="resource_literary_or_scientific" id="resource_literary_or_scientific" required>
                <option value="1">{{ __('messages.literary') }}</option>
                <option value="2">{{ __('messages.scientific') }}</option>
            </select>
        </div>
        @error('resource_literary_or_scientific')
            <div class="error">{{ $message }}</div>
        @enderror
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_subject_id">
                {{ __('messages.resourceSubject') }}:
            </label>
            <select name="resource_subject_id" id="resource_subject_id" required>
                @foreach(App\Models\Subject::all() as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        @error('resource_subject_id')
            <div class="error">{{ $message }}</div>
        @enderror
    </x-addcard>
</x-layout> 