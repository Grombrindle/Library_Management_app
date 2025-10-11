@props(['resource' => App\Models\Resource::findOrFail(session('resource'))])
<x-layout>
    <style>
        .custom-file-input {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .hidden-file-input {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: block;
            padding: 10px 15px;
            background-color: #555184;
            color: white;
            border: 2px solid #9997BC;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 14px;
            max-width: 100%;
            overflow: hidden;
            margin-left: auto;
            margin-right: auto;
        }

        .file-input-label:hover {
            background-color: #9997BC;
            border-color: #555184;
            color: #555184;
        }

        .file-input-text {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .file-input-label:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: #ccc;
            border-color: #999;
        }

        .file-input-label:disabled:hover {
            background-color: #ccc;
            border-color: #999;
            color: #666;
        }
    </style>
    <x-editcard :link="'editresource/' . session('resource')" object="Resource" :objectModel=$resource :image=true>
        <div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_name">
                    {{ __('messages.resourceName') }}:
                </label>
                <input type="text" name="resource_name" id="resource_name" value="{{ $resource->name }}"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;" />
            </div>
            <br>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_description">
                    {{ __('messages.resourceDescription') }} ({{ __('messages.optional') }}):
                </label>
                <textarea name="resource_description" id="resource_description" autocomplete="off"
                    style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;max-height:500px;">{{ $resource->description }}</textarea>
            </div>
            {{-- <br>
            <label for="resource_subject_id">
                {{ __('messages.subject') }}: <br>
            </label>
            <select name="resource_subject_id" id="resource_subject_id" required>
                <option value="" selected>{{ __('messages.selectSubject') }}</option>
                @foreach (App\Models\Subject::all() as $subject)
                    <option value="{{ $subject->id }}" @if ($resource->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option>
                @endforeach
            </select> --}}
            <br>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_publish_date">
                    {{ __('messages.publishDate') }}:
                </label>
                <input type="date" name="resource_publish_date" id="resource_publish_date"
                    value="{{ $resource->{'publish date'} }}" required>
            </div>
            <br>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_author">
                    {{ __('messages.author') }}:
                </label>
                <input type="text" name="resource_author" id="resource_author" value="{{ $resource->author }}"
                    autocomplete="off" style="height:20%; text-align:center; font-size:40%; width:fit-content;"
                    required>
            </div>
            <br>
            <span>{{ __('messages.uploadPDFs') }}:</span>
            <br>
            <br>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:10px;">
                @php
                    $pdfFiles = $resource->pdf_files;
                    $languages = [
                        'ar' => __('messages.pdfArabic'),
                        'en' => __('messages.pdfEnglish'),
                        'es' => __('messages.pdfSpanish'),
                        'de' => __('messages.pdfGerman'),
                        'fr' => __('messages.pdfFrench'),
                    ];
                @endphp

                @foreach ($languages as $langCode => $langName)
                    @php
                        $hasFile = isset($pdfFiles[$langCode]) && $pdfFiles[$langCode];
                        $fileName = $hasFile ? basename($pdfFiles[$langCode]) : __('messages.chooseFile');
                        // Truncate filename if too long
                        $maxLength = 30;
                        $displayFileName = $hasFile
                            ? (strlen($fileName) > $maxLength
                                ? substr($fileName, 0, $maxLength - 3) . '...'
                                : $fileName)
                            : __('messages.chooseFile');
                        $fullFileName = $hasFile
                            ? $fileName . ' (' . __('messages.fileAlreadyUploaded') . ')'
                            : __('messages.chooseFile');
                    @endphp
                    <div>
                        <label for="pdf_{{ $langCode }}">{{ $langName }}
                            @if ($langName !== 'Arabic PDF')
                                ({{ __('messages.optional') }})
                        </label>
                @endif
                <div class="custom-file-input">
                    <input type="file" id="pdf_{{ $langCode }}" class="hidden-file-input"
                        name="pdf_{{ $langCode }}" accept="application/pdf" {{ $hasFile ? 'disabled' : '' }}>
                    <label for="pdf_{{ $langCode }}" class="file-input-label"
                        {{ $hasFile ? 'style="opacity: 0.6; cursor: not-allowed;"' : '' }}>
                        <span class="file-input-text" id="file-input-text-{{ $langCode }}"
                            title="{{ $fullFileName }}">
                            {{ $displayFileName }}
                        </span>
                    </label>
                </div>
            </div>
            @endforeach
        </div>
        <div id="pdf-error" style="color: red; display: none; text-align: center;">
            {{ __('messages.arabicOrEnglishRequired') }}
        </div>
        <br>
        </div>
        <br>
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_audio_file">
                {{ __('messages.resourceAudioFile') }} ({{ __('messages.optional') }}):
            </label>
            <input type="file" name="resource_audio_file" id="resource_audio_file" accept="audio/*"
                style="@if ($resource->audio_file_url) cursor: not-allowed; @endif"
                @if ($resource->audio_file) disabled @endif>
            @if ($resource->audio_file)
                <span style="color: var(--disabled-text);">{{ __('messages.audioFileAlreadyUploaded') }}</span>
            @endif
        </div>
        @error('resource_audio_file')
            <div class="error">{{ $message }}</div>
        @enderror
    </x-editcard>
    <script>
        function setupFileInput(inputId, textId) {
            const input = document.getElementById(inputId);
            const textElement = document.getElementById(textId);

            // Skip setup if input is disabled (file already exists)
            if (input && input.disabled) {
                return;
            }

            if (input) {
                input.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.type !== 'application/pdf') {
                            alert('Invalid file type. Please upload a PDF file.');
                            event.target.value = '';
                            textElement.textContent = 'Choose a file';
                            return;
                        }
                        // Truncate filename if too long
                        const maxLength = 30;
                        const fileName = file.name.length > maxLength ?
                            file.name.substring(0, maxLength - 3) + '...' :
                            file.name;
                        textElement.textContent = fileName;
                        textElement.title = file.name; // Show full name on hover
                    } else {
                        textElement.textContent = 'Choose a file';
                        textElement.title = '';
                    }
                    document.getElementById('pdf-error').style.display = 'none';
                });
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const languages = ['ar', 'en', 'es', 'de', 'fr'];
            languages.forEach(lang => {
                setupFileInput(`pdf_${lang}`, `file-input-text-${lang}`);
            });
        });
    </script>
</x-layout>
