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
            margin-left:auto;
            margin-right:auto;
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
    <x-addcard link="addresource" object="Resource">
        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="resource_name">
                {{ __('messages.resourceName') }}:
            </label>
            <input type="text" name="resource_name" id="resource_name" value="" autocomplete="off"
                style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
        </div>
        <br>
        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="resource_description">
                {{ __('messages.resourceDescription') }} ({{ __('messages.optional') }}):
            </label>
            <textarea name="resource_description" id="resource_description" autocomplete="off"
                style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;max-height:500px;"></textarea>
        </div>
        <br>
        <label for="resource_subject_id">
            {{ __('messages.subject') }}: <br>
        </label>
        <select name="resource_subject_id" id="resource_subject_id" required>
            <option value="" selected>{{ __('messages.selectSubject') }}</option>
            @foreach (App\Models\Subject::all() as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->literaryOrScientific ? __('messages.scientific') : __('messages.literary') }})</option>
            @endforeach
        </select>
        <br>
        <br>
        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="resource_publish_date">
                {{ __('messages.publishDate') }}:
            </label>
            <input type="date" name="resource_publish_date" id="resource_publish_date" required>
        </div>
        <br>
        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="resource_author">
                {{ __('messages.author') }}:
            </label>
            <input type="text" name="resource_author" id="resource_author" value="" autocomplete="off"
                style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
        </div>
        <br>
        <span>{{ __('messages.uploadPDFs') }} ({{ __('messages.arabicRequired') }}):</span>
        <br>
        <br>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:10px;">
            <div>
                <label for="pdf_ar">{{ __('messages.pdfArabic') }}</label>
                <div class="custom-file-input">
                    <input type="file" id="pdf_ar" class="hidden-file-input" name="pdf_ar" accept="application/pdf" required>
                    <label for="pdf_ar" class="file-input-label">
                        <span class="file-input-text" id="file-input-text-ar">{{ __('messages.chooseFile') }}</span>
                    </label>
                </div>
            </div>
            <div>
                <label for="pdf_en">{{ __('messages.pdfEnglish') }} ({{ __('messages.optional') }})</label>
                <div class="custom-file-input">
                    <input type="file" id="pdf_en" class="hidden-file-input" name="pdf_en" accept="application/pdf">
                    <label for="pdf_en" class="file-input-label">
                        <span class="file-input-text" id="file-input-text-en">{{ __('messages.chooseFile') }}</span>
                    </label>
                </div>
            </div>
            <div>
                <label for="pdf_es">{{ __('messages.pdfSpanish') }} ({{ __('messages.optional') }})</label>
                <div class="custom-file-input">
                    <input type="file" id="pdf_es" class="hidden-file-input" name="pdf_es" accept="application/pdf">
                    <label for="pdf_es" class="file-input-label">
                        <span class="file-input-text" id="file-input-text-es">{{ __('messages.chooseFile') }}</span>
                    </label>
                </div>
            </div>
            <div>
                <label for="pdf_de">{{ __('messages.pdfGerman') }} ({{ __('messages.optional') }})</label>
                <div class="custom-file-input">
                    <input type="file" id="pdf_de" class="hidden-file-input" name="pdf_de" accept="application/pdf">
                    <label for="pdf_de" class="file-input-label">
                        <span class="file-input-text" id="file-input-text-de">{{ __('messages.chooseFile') }}</span>
                    </label>
                </div>
            </div>
            <div>
                <label for="pdf_fr">{{ __('messages.pdfFrench') }} ({{ __('messages.optional') }})</label>
                <div class="custom-file-input">
                    <input type="file" id="pdf_fr" class="hidden-file-input" name="pdf_fr" accept="application/pdf">
                    <label for="pdf_fr" class="file-input-label">
                        <span class="file-input-text" id="file-input-text-fr">{{ __('messages.chooseFile') }}</span>
                    </label>
                </div>
            </div>
        </div>
        <div id="pdf-error" style="color: red; display: none; text-align: center;">
            {{ __('messages.arabicOrEnglishRequired') }}
        </div>
        <br>

        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
            <label for="resource_audio_file">
                {{ __('messages.resourceAudioFile') }} ({{ __('messages.optional') }}):
            </label>
            <input type="file" name="resource_audio_file" id="resource_audio_file" accept="audio/*">
        </div>
        @error('resource_audio_file')
            <div class="error">{{ $message }}</div>
        @enderror
    </x-addcard>
    <script>
        function setupFileInput(inputId, textId) {
            const input = document.getElementById(inputId);
            const textElement = document.getElementById(textId);
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
                    const fileName = file.name.length > maxLength
                        ? file.name.substring(0, maxLength - 3) + '...'
                        : file.name;
                    textElement.textContent = fileName;
                    textElement.title = file.name; // Show full name on hover
                } else {
                    textElement.textContent = 'Choose a file';
                    textElement.title = '';
                }
                document.getElementById('pdf-error').style.display = 'none';
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            setupFileInput('pdf_ar', 'file-input-text-ar');
            setupFileInput('pdf_en', 'file-input-text-en');
            setupFileInput('pdf_es', 'file-input-text-es');
            setupFileInput('pdf_de', 'file-input-text-de');
            setupFileInput('pdf_fr', 'file-input-text-fr');
        });
    </script>
</x-layout>
