@props(['resource' => App\Models\Resource::findOrFail(session('resource'))])
<x-layout>
    <x-editcard :link="'editresource/' . session('resource')" object="Resource" :objectModel=$resource :image=true>
        <div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_name">
                    {{ __('messages.resourceName') }}:
                </label>
                <input type="text" name="resource_name" id="resource_name" value="{{ $resource->name }}"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;" />
            </div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_description">
                    {{ __('messages.resourceDescription') }} ({{ __('messages.optional') }}):
                </label>
                <textarea name="resource_description" id="resource_description" autocomplete="off"
                    style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;max-height:500px;">{{ $resource->description }}</textarea>
            </div>
            <br>
            <label for="resource_subject_id">
                {{ __('messages.subject') }}: <br>
            </label>
            <select name="resource_subject_id" id="resource_subject_id" required>
                <option value="" selected>{{ __('messages.selectSubject') }}</option>
                @foreach (App\Models\Subject::all() as $subject)
                    <option value="{{ $subject->id }}" @if($resource->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option>
                @endforeach
            </select>
            <br>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_publish_date">
                    {{ __('messages.publishDate') }}:
                </label>
                <input type="date" name="resource_publish_date" id="resource_publish_date" value="{{ $resource->{'publish date'} }}" required>
            </div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="resource_author">
                    {{ __('messages.author') }}:
                </label>
                <input type="text" name="resource_author" id="resource_author" value="{{ $resource->author }}" autocomplete="off"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
            </div>
            <br>
            <span>{{ __('messages.uploadPDFs') }} ({{ __('messages.arabicOrEnglishRequired') }}):</span>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:10px;">
                <div>
                    <label for="pdf_ar">{{ __('messages.pdfArabic') }} ({{ __('messages.optional') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="pdf_ar" class="hidden-file-input" name="pdf_ar" accept="application/pdf">
                        <label for="pdf_ar" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-ar">
                                {{ $resource->pdf_files['ar'] ?? __('messages.chooseFile') }}
                            </span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="pdf_en">{{ __('messages.pdfEnglish') }} ({{ __('messages.optional') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="pdf_en" class="hidden-file-input" name="pdf_en" accept="application/pdf">
                        <label for="pdf_en" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-en">
                                {{ $resource->pdf_files['en'] ?? __('messages.chooseFile') }}
                            </span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="pdf_es">{{ __('messages.pdfSpanish') }} ({{ __('messages.optional') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="pdf_es" class="hidden-file-input" name="pdf_es" accept="application/pdf">
                        <label for="pdf_es" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-es">
                                {{ $resource->pdf_files['es'] ?? __('messages.chooseFile') }}
                            </span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="pdf_de">{{ __('messages.pdfGerman') }} ({{ __('messages.optional') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="pdf_de" class="hidden-file-input" name="pdf_de" accept="application/pdf">
                        <label for="pdf_de" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-de">
                                {{ $resource->pdf_files['de'] ?? __('messages.chooseFile') }}
                            </span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="pdf_fr">{{ __('messages.pdfFrench') }} ({{ __('messages.optional') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="pdf_fr" class="hidden-file-input" name="pdf_fr" accept="application/pdf">
                        <label for="pdf_fr" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-fr">
                                {{ $resource->pdf_files['fr'] ?? __('messages.chooseFile') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div id="pdf-error" style="color: red; display: none; text-align: center;">
                {{ __('messages.arabicOrEnglishRequired') }}
            </div>
            <br>
        </div>
    </x-editcard>
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
                    textElement.textContent = file.name;
                } else {
                    textElement.textContent = 'Choose a file';
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
