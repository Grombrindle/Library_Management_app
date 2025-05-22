<x-layout>
    <x-addcard link="addlecture" object="Lecture">
        <div style="display:flex; flex-direction:column; align-items:center;">
            <label for="lecture_name">
                {{ __('messages.lectureName') }}:
            </label>
            <input type="text" name="lecture_name" id="lecture_name" value="" autocomplete="off"
                style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
        </div>
        <div style="display:flex; flex-direction:column; align-items:center; height:100%;">
            <label for="lecture_description">
                {{ __('messages.lectureDescription') }} ({{ __('messages.optional') }}):
            </label>
            <textarea name="lecture_description" id="lecture_description" autocomplete="off"
                style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;max-height:500px;"></textarea>
        </div>
        <br>
        <label for="subject">
            {{ __('messages.course') }}: <br>
        </label>
        <select name="course" id="course" required>
            <option value="" selected>{{ __('messages.selectCourse') }}</option>
            @foreach (App\Models\Course::all() as $course)
                <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->subject->name }})</option>
            @endforeach
        </select>
        <br>
        <br>
        <div style="display:flex; justify-content:center; margin-bottom:20px;">
            <div class="upload-type-toggle">
                <button type="button" class="toggle-btn active" data-type="pdf">PDF</button>
                <button type="button" class="toggle-btn" data-type="video">Video</button>
                <div class="toggle-slider"></div>
            </div>
        </div>

        <br>

        <div class="video-inputs" style="display: none;">
            <span>{{ __('messages.videoFile') }} ({{ __('messages.uploadAtLeastOne') }}):</span>
            <br>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px">
                <div>
                    <label for="actual-file-input-360">360p ({{ __('messages.mandatory') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="actual-file-input-360" class="hidden-file-input" name="lecture_file_360"
                            accept="video/*">
                        <label for="actual-file-input-360" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-360">{{ __('messages.chooseFile') }}</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="actual-file-input-720">720p ({{ __('messages.optional') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="actual-file-input-720" class="hidden-file-input" name="lecture_file_720"
                            accept="video/*">
                        <label for="actual-file-input-720" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-720">{{ __('messages.chooseFile') }}</span>
                        </label>
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction:row;">
                <div style="margin-left:auto;margin-right:auto;">
                    <label for="actual-file-input-1080">1080p ({{ __('messages.optional') }})</label>
                    <div class="custom-file-input">
                        <input type="file" id="actual-file-input-1080" class="hidden-file-input" name="lecture_file_1080"
                            accept="video/*">
                        <label for="actual-file-input-1080" class="file-input-label">
                            <span class="file-input-text" id="file-input-text-1080">{{ __('messages.chooseFile') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div id="file-error" style="color: red; display: none; text-align: center;">
            {{ __('messages.uploadAtLeastOneVideo') }}
        </div>
        <div class="pdf-input" style="display: block;">
            <span>{{ __('messages.pdfFile') }}:</span>
            <br>
            <div class="custom-file-input">
                <input type="file" id="actual-file-input-pdf" class="hidden-file-input" name="lecture_file_pdf"
                    accept=".pdf">
                <label for="actual-file-input-pdf" class="file-input-label">
                    <span class="file-input-text" id="file-input-text-pdf">{{ __('messages.chooseFile') }}</span>
                </label>
            </div>
        </div>
        <br>
    </x-addcard>

    <style>
        .upload-type-toggle {
            display: flex;
            gap: 2px;
            background: #f0f0f0;
            padding: 4px;
            border-radius: 30px;
            position: relative;
            width: fit-content;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .upload-type-toggle:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .toggle-btn {
            padding: 10px 24px;
            border: none;
            border-radius: 26px;
            cursor: pointer;
            background: transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            color: #666;
            position: relative;
            z-index: 1;
            min-width: 120px;
            text-align: center;
        }

        .toggle-btn:hover {
            color: #007bff;
        }

        .toggle-btn.active {
            color: white;
        }

        .toggle-slider {
            position: absolute;
            top: 4px;
            left: 12px;
            height: calc(100% - 8px);
            background: #007bff;
            border-radius: 26px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
            width: calc(40% - 4px);
        }

        .toggle-btn.active + .toggle-slider {
            transform: translateX(calc(100% + 2px));
        }
    </style>

    <script>
        // Toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtns = document.querySelectorAll('.toggle-btn');
            const toggleSlider = document.querySelector('.toggle-slider');
            const videoInputs = document.querySelector('.video-inputs');
            const pdfInput = document.querySelector('.pdf-input');
            const pdfFileInput = document.getElementById('actual-file-input-pdf');
            const videoFileInputs = [
                document.getElementById('actual-file-input-360'),
                document.getElementById('actual-file-input-720'),
                document.getElementById('actual-file-input-1080')
            ];

            function updateTogglePosition(activeBtn) {
                const rect = activeBtn.getBoundingClientRect();
                const containerRect = activeBtn.parentElement.getBoundingClientRect();
                const offset = activeBtn.dataset.type === 'pdf' ? 2 : 0;
                toggleSlider.style.transform = `translateX(${rect.left - containerRect.left + offset}px)`;
            }

            function updateRequiredAttributes(type) {
                if (type === 'pdf') {
                    pdfFileInput.required = true;
                    videoFileInputs.forEach(input => input.required = false);
                } else {
                    pdfFileInput.required = false;
                    videoFileInputs[0].required = true; // Only 360p is required for video
                    videoFileInputs.slice(1).forEach(input => input.required = false);
                }
            }

            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    toggleBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    // Update slider position
                    updateTogglePosition(this);

                    // Toggle visibility of inputs
                    if (this.dataset.type === 'video') {
                        videoInputs.style.display = 'block';
                        pdfInput.style.display = 'none';
                        updateRequiredAttributes('video');
                    } else {
                        videoInputs.style.display = 'none';
                        pdfInput.style.display = 'block';
                        updateRequiredAttributes('pdf');
                    }
                });
            });

            // Initialize toggle position and required attributes
            const activeBtn = document.querySelector('.toggle-btn.active');
            if (activeBtn) {
                updateTogglePosition(activeBtn);
                updateRequiredAttributes(activeBtn.dataset.type);
            }

            // Function to handle file input changes
            function setupFileInput(inputId, textId, allowedTypes) {
                const input = document.getElementById(inputId);
                const textElement = document.getElementById(textId);

                input.addEventListener('change', function(event) {
                    const file = event.target.files[0];

                    if (file) {
                        // Check file type
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

                    // Hide error message when a file is selected
                    document.getElementById('file-error').style.display = 'none';
                });
            }

            // Set up all file inputs
            setupFileInput('actual-file-input-pdf', 'file-input-text-pdf', ['application/pdf']);
            setupFileInput('actual-file-input-360', 'file-input-text-360', ['video']);
            setupFileInput('actual-file-input-720', 'file-input-text-720', ['video']);
            setupFileInput('actual-file-input-1080', 'file-input-text-1080', ['video']);
        });

        // Form validation function
        function validateLectureForm() {
            const isVideoMode = document.querySelector('.toggle-btn.active').dataset.type === 'video';
            
            if (isVideoMode) {
                const file360 = document.getElementById('actual-file-input-360').files.length;
                const file720 = document.getElementById('actual-file-input-720').files.length;
                const file1080 = document.getElementById('actual-file-input-1080').files.length;

                if (!file360 && !file720 && !file1080) {
                    document.getElementById('file-error').style.display = 'block';
                    document.getElementById('file-error').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return false;
                }
            } else {
                const pdfFile = document.getElementById('actual-file-input-pdf').files.length;
                if (!pdfFile) {
                    document.getElementById('file-error').style.display = 'block';
                    document.getElementById('file-error').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return false;
                }
            }

            return true;
        }
    </script>
</x-layout>
