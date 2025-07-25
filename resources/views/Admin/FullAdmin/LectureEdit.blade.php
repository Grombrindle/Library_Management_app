@props(['lecture' => App\Models\Lecture::findOrFail(session('lecture'))])
<x-layout>
    <x-editcard : link="editlecture/{{ session('lecture') }}" object="Lecture" :objectModel=$lecture :image=true>
        <div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="lecture_name">
                    {{ __('messages.lectureName') }}:
                </label>
                <input type="text" name="lecture_name" id="lecture_name" value="{{ $lecture->name }}"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;" />
            </div>
            <br>

            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="lecture_description">
                    {{ __('messages.lectureDescription') }}:
                </label>

                <textarea name="lecture_description" id="lecture_description" autocomplete="off"
                    style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;"
                    required>{{ $lecture->description }}</textarea>
            </div>
            <br>
            @if ($lecture->type)

                <span>{{ __('messages.videoFile') }}:</span>
                <br>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px">
                    <div>
                        <label for="actual-file-input-360">360p</label>
                        <div class="custom-file-input">
                            <input type="file" id="actual-file-input-360" class="hidden-file-input" name="lecture_file_360"
                                accept="video/*" @if ($lecture->file_360 != null) disabled @endif>
                            <label for="actual-file-input-360" class="file-input-label" @if ($lecture->file_360 != null)
                            disabled @endif>
                                <span class="file-input-text" id="file-input-text-360">{{ __('messages.chooseFile') }}
                                    @if ($lecture->file_360 != null)
                                        <br> ({{ __('messages.fileAlreadyUploaded') }})
                                    @endif
                                </span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label for="actual-file-input-720">720p</label>
                        <div class="custom-file-input">
                            <input type="file" id="actual-file-input-720" class="hidden-file-input" name="lecture_file_720"
                                accept="video/*" @if ($lecture->file_720 != null) disabled @endif>
                            <label for="actual-file-input-720" class="file-input-label" @if ($lecture->file_720 != null)
                            disabled @endif>
                                <span class="file-input-text" id="file-input-text-720">{{ __('messages.chooseFile') }}
                                    @if ($lecture->file_720 != null)
                                        <br> ({{ __('messages.fileAlreadyUploaded') }})
                                    @endif
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div style="display: flex; flex-direction:row;">
                    <div style="margin-left:auto;margin-right:auto;">

                        <label for="actual-file-input-1080">1080p</label>
                        <div class="custom-file-input">
                            <input type="file" id="actual-file-input-1080" class="hidden-file-input"
                                name="lecture_file_1080" accept="video/*" @if ($lecture->file_1080 != null) disabled @endif>
                            <label for="actual-file-input-1080" class="file-input-label" @if ($lecture->file_1080 != null)
                            disabled @endif>
                                <span class="file-input-text" id="file-input-text-1080">{{ __('messages.chooseFile') }}
                                    @if ($lecture->file_1080 != null)
                                        <br> ({{ __('messages.fileAlreadyUploaded') }})
                                    @endif
                                </span>
                            </label>
                        </div>
                    </div>
                    <br>
                </div>
            @else
                <div class="pdf-input" style="display: block;">
                    <span>{{ __('messages.pdfFile') }}:</span>
                    <br>
                    <div class="custom-file-input">
                        <input type="file" id="actual-file-input-pdf" class="hidden-file-input" name="lecture_file_pdf"
                            accept=".pdf" disabled>
                        <label for="actual-file-input-pdf" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-pdf">{{ __('messages.chooseFile') }}</span>
                                    @if ($lecture->file_pdf != null)
                                        <br> ({{ __('messages.fileAlreadyUploaded') }})
                                    @endif
                        </label>
                    </div>
                </div>
            @endif

    </x-editcard>
    </div>
</x-layout>
<script>
    // Function to handle file input changes
    function setupFileInput(inputId, textId) {
        const input = document.getElementById(inputId);
        const textElement = document.getElementById(textId);

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                // Check file type
                const allowedTypes = ['video'];
                const isAllowed = allowedTypes.some(type => file.type.startsWith(type));

                if (!isAllowed) {
                    alert('{{ __("messages.invalidFileType") }}');
                    event.target.value = '';
                    textElement.textContent = '{{ __("messages.chooseFile") }}';
                    return;
                }

                // Update the display text
                textElement.textContent = file.name;
            } else {
                textElement.textContent = '{{ __("messages.chooseFile") }}';
            }
        });
    }

    // Set up all file inputs
    setupFileInput('actual-file-input-360', 'file-input-text-360');
    setupFileInput('actual-file-input-720', 'file-input-text-720');
    setupFileInput('actual-file-input-1080', 'file-input-text-1080');
</script>
<style>
.custom-file-input {
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
}

.file-input-label {
    display: block;
    width: 100%;
    padding: 8px;
    background: #9997BC;
    border: 2px solid #555184;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
}

.file-input-label:hover {
    background: white;
    color: #555184;
    border-color: #555184;
}

.hidden-file-input {
    display: none;
}

.file-input-text {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .custom-file-input {
        max-width: 100%;
    }
}
</style>