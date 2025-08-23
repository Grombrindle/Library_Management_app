@props(['exam' => App\Models\Exam::findOrFail(session('exam'))])

<x-layout>
    <x-editcard link="editexam/{{ session('exam') }}" object="Exam" :objectModel=$exam :image=true>
        <div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="exam_title">
                    {{ __('messages.examTitle') }}:
                </label>
                <input type="text" name="title" id="exam_title" value="{{ $exam->title }}"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;" required />
            </div>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror

            <br>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="exam_description">
                    {{ __('messages.examDescription') }}:
                </label>
                <div style="position: relative; width: 80%;">
                    <textarea name="description" id="exam_description" autocomplete="off"
                        style="height:150px; width:100%; font-size:16px; padding:10px; padding-bottom:30px; resize:vertical;max-height:500px;"
                        maxlength="500" oninput="updateCharCount(this, 500)" required>{{ $exam->description }}</textarea>
                    <div id="charCount"
                        style="position: absolute; bottom: 5px; right: 10px; font-size: 12px; color: #666; padding: 2px 6px; border-radius: 3px;">
                        0/500
                    </div>
                </div>
            </div>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror

            <br>
            <label for="subject">
                {{ __('messages.subject') }}: <br>
            </label>
            <select name="subject_id" id="subject" required>
                <option value="">{{ __('messages.selectSubject') }}</option>
                @foreach (App\Models\Subject::all() as $subject)
                    <option value="{{ $subject->id }}" {{ $exam->subject_id == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }} ({{$subject->literaryOrScientific ? "Scientific" : "Literary"}})
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <div class="error">{{ $message }}</div>
            @enderror

            <br><br>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="exam_date">
                    {{ __('messages.examDate') }}:
                </label>
                <input type="date" name="date" id="exam_date" value="{{ $exam->date->format('Y-m-d') }}"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
            </div>
            @error('date')
                <div class="error">{{ $message }}</div>
            @enderror

            <br>
        </div>
    </x-editcard>
</x-layout>

<style>
    .file-upload-section {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .custom-file-input {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }

    .file-input-label {
        display: block;
        width: 100%;
        padding: 12px;
        background: #9997BC;
        border: 2px solid #555184;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: white;
        font-weight: 500;
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

    .error {
        color: red;
        font-size: 0.9rem;
        margin-top: 5px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .custom-file-input {
            max-width: 100%;
        }
    }
</style>

<script>
    // Character count function
    function updateCharCount(textarea, maxLength) {
        const charCount = document.getElementById('charCount');
        const currentLength = textarea.value.length;
        charCount.textContent = `${currentLength}/${maxLength}`;

        if (currentLength > maxLength * 0.9) {
            charCount.style.color = '#ff6b6b';
        } else if (currentLength > maxLength * 0.7) {
            charCount.style.color = '#ffa500';
        } else {
            charCount.style.color = '#666';
        }
    }

    // Initialize character count
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('exam_description');
        updateCharCount(textarea, 500);
    });

    // Function to handle file input changes
    function setupFileInput(inputId, textId, allowedTypes, maxSize = null) {
        const input = document.getElementById(inputId);
        const textElement = document.getElementById(textId);

        input.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                // Check file type
                const isAllowed = allowedTypes.some(type => file.type.startsWith(type) || file.name.toLowerCase().endsWith(type));

                if (!isAllowed) {
                    alert('{{ __("messages.invalidFileType") }}');
                    event.target.value = '';
                    textElement.innerHTML = textElement.innerHTML.split('<br>')[0]; // Keep original text
                    return;
                }

                // Check file size if specified
                if (maxSize && file.size > maxSize) {
                    const maxSizeMB = maxSize / (1024 * 1024);
                    alert(`File size must be less than ${maxSizeMB}MB`);
                    event.target.value = '';
                    textElement.innerHTML = textElement.innerHTML.split('<br>')[0]; // Keep original text
                    return;
                }

                // Update the display text
                const originalText = textElement.innerHTML.split('<br>')[0];
                textElement.innerHTML = originalText + '<br><small style="color: #90EE90;">New file: ' + file.name + '</small>';
            }
        });
    }

    // Set up file inputs
    document.addEventListener('DOMContentLoaded', function() {
        setupFileInput('object_image', 'image-text', ['image'], 2 * 1024 * 1024); // 2MB limit
        setupFileInput('pdf_file', 'pdf-text', ['.pdf', 'application/pdf'], 10 * 1024 * 1024); // 10MB limit
    });
</script>