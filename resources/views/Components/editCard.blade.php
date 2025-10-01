@props([
    'relations' => false,
    'link' => '#',
    'object' => null,
    'selectedSubjects' => null,
    'subjects' => null,
    'menu' => null,
    'menuModel' => null,
    'image' => false,
    'objectModel' => null,
    'model' => null,
    'lectures' => false,
    'subscribedLectureIds' => null,
    'isBanned' => null,
])
@if ($lectures != false)
    @php
        $subscribedLectureIds = $model->lectures->pluck('id')->toArray();
        $selectedLectures = $model->lectures->pluck('id')->toArray();
    @endphp
@endif
<style>
    .input-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 50%;
        grid-row-gap: 10%;
    }

    .icon {
        width: 80%;
        /* Adjust the size of the SVG icon */
        height: 80%;
        /* Adjust the size of the SVG icon */
        cursor: pointer;
        /* Optional: Add a pointer cursor for interactivity */
        transition: transform 0.3s ease;
    }

    .icon:hover {
        transform: scale(1.1);
        /* Slightly enlarge the icon on hover */
        transition: transform 0.3s ease;
        /* Smooth transition */
    }

    .ObjectContainer {
        width: 40rem;
        height: auto;
        display: flex;
        flex-direction: column;
        border: black 5px solid;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        margin-bottom: 0;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: var(--text-color);
    }

    .textContainer {
        line-height: 50px;
        z-index: 2;
        font-size: 30px;
        text-align: center;
    }

    .subject-button {
        background: #555184;
        padding: 5px 15px;
        font-size: 16px;
        border: none;
        color: #555184;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .subject-button.selected {
        background-color: #555184;
        color: white;
    }

    .lecture-button {
        background: #555184;
        padding: 5px 15px;
        font-size: 16px;
        border: none;
        color: #555184;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .lecture-button.selected {
        background-color: #555184;
        color: white;
    }

    .submit-button {
        margin-top: 20px;
        margin-right: auto;
        margin-left: auto;
        padding: 10px 20px;
        font-size: 18px;
        background: #9997BC;
        border: #555184 3px solid;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-button:hover:not(:disabled) {
        background: white;
        color: black;
        animation: pulse 1s infinite;
    }

    .submit-button:disabled:hover,
    .submit-button:disabled {
        background-color: white;
        color: darkgray;
        border-color: darkgray;
        cursor: not-allowed;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 40, 40, 0.7);
        }

        50% {
            box-shadow: 0 0 0 8px rgba(40, 40, 40, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(40, 40, 40, 0);
        }
    }

    .dropdown-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        gap: 5px;
    }

    .dropdown {
        padding: 5px;
        font-size: 16px;
        position: relative;
        display: inline-block;
        margin-right: auto;
        margin-left: auto;
    }

    .dropbtn {
        background-color: #9997BC;
        color: black;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 20px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        grid-template-columns: auto auto;
        gap: 10px;
        /* bottom: 100%; */
        /* top: auto; */
        right: 1rem;
    }

    .dropdown-content a {
        color: black;
        text-decoration: none;
        display: block;
        position: relative;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .nested-dropdown {
        display: none;
        position: absolute;
        left: 100%;
        top: 0;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a:hover+.nested-dropdown,
    .nested-dropdown:hover {
        display: block;
    }

    .subject-item {
        padding: 0.1rem 0.1rem;
    }

    .subject-item:hover .nested-dropdown {
        display: block;
    }

    .add-subject-btn {
        padding: 5px 10px;
        font-size: 16px;
        background: #555184;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: fit-content;
        text-align: center;
    }

    .image {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .edit-card {
        background: var(--card-bg);
        margin-top: clamp(1%, 2vw, 2%);
        font-size: clamp(14px, 1.5vw + 8px, 20px);
        border: var(--card-border) clamp(2px, 0.5vw, 4px) solid;
        color: var(--text-color);
        border-radius: clamp(2px, 0.5vw, 3px);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        transform: translateY(-2px);
        align-items: center;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: clamp(150px, 80vw, 800px);
        margin-left: auto;
        margin-right: auto;
        padding: clamp(2%, 3vw, 4%);
    }

    .edit-card-title {
        font-size: clamp(16px, 2vw + 10px, 24px);
        font-weight: bold;
        margin-bottom: clamp(1%, 2vw, 2%);
        text-align: center;
        width: 100%;
    }

    .edit-card-content {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: clamp(1%, 2vw, 2%);
    }

    .edit-card-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: clamp(1%, 2vw, 2%);
    }

    .edit-card-input {
        width: 100%;
        padding: clamp(0.5%, 1vw, 1%);
        font-size: clamp(14px, 1.5vw + 8px, 20px);
        border: var(--card-border) clamp(1px, 0.3vw, 2px) solid;
        border-radius: clamp(2px, 0.5vw, 3px);
        background: var(--input-bg);
        color: var(--text-color);
    }

    .edit-card-button {
        padding: clamp(0.5%, 1vw, 1%) clamp(1%, 2vw, 2%);
        font-size: clamp(14px, 1.5vw + 8px, 20px);
        border: var(--card-border) clamp(1px, 0.3vw, 2px) solid;
        border-radius: clamp(2px, 0.5vw, 3px);
        background: var(--button-bg);
        color: var(--button-text);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .edit-card-button:hover {
        background: var(--button-hover-bg);
        color: var(--button-hover-text);
    }

    .edit-card-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Remove all media queries and replace with clamp-based scaling */
    @media (max-width: 768px) {
        .edit-card {
            padding: clamp(1%, 2vw, 3%);
        }

        .edit-card-title {
            font-size: clamp(14px, 1.8vw + 8px, 20px);
        }

        .edit-card-input {
            padding: clamp(0.3%, 0.8vw, 0.8%);
        }

        .edit-card-button {
            padding: clamp(0.3%, 0.8vw, 0.8%) clamp(0.8%, 1.5vw, 1.5%);
        }
    }
</style>

<div class="ObjectContainer">
    <form action="/{{ $link }}" method="POST" enctype="multipart/form-data"
        onsubmit="@if ($relations) updateHiddenInput(); @endif return confirmEdit();"
        style="display:flex;flex-direction:column">
        @csrf
        @method('PUT')
        @if ($image)
            <div style="width:50%; height:10%; margin-left:auto; margin-right:auto">
                @if ($object == 'Exam')
                    <img src="{{ asset($objectModel->thumbnailUrl) }}" alt="" id="image_preview" class="image">
                @else
                    <img src="{{ asset($objectModel->image) }}" alt="" id="image_preview" class="image">
                @endif
            </div>
            <div
                style="display:flex; flex-direction:column; align-items:center; margin-top:5%;margin-bottom:5%; font-size:2rem;">
                <label for="object_image">
                    @if ($object == 'Teacher')
                        {{ __('messages.teacherImage') }}
                    @elseif($object == 'Admin')
                        {{ __('messages.adminImage') }}
                    @elseif ($object == 'Course')
                        {{ __('messages.courseImage') }}
                    @elseif ($object == 'Lecture')
                        {{ __('messages.lectureImage') }}
                    @elseif ($object == 'Subject')
                        {{ __('messages.subjectImage') }}
                    @endif
                </label>
                <input type="file" name="object_image" id="object_image"
                    placeholder="Enter the image of the {{ Str::lower($object) }}" accept="image/*"
                    onchange="validateImageSize(this)">
                <label for="object_image"
                    style="color:#222; font-size:2rem; text-align:center">{{ __('messages.imageSizeWarning') }}</label>
            </div>
            @error('object_image')
                <div class="error">{{ $message }}</div>
            @enderror
            <br>
        @endif
        <div class="textContainer">{{ $slot }}</div>
        @if ($relations)
            <div id="subject-buttons-container" class="buttonContainer">
                @foreach ($subjects as $subject)
                    <button type="button" class="subject-button selected" data-subject-id="{{ $subject->id }}">
                        @if ($menu == 'Subject')
                            {{ $subject->name }} ({{ $subject->literaryOrScientific ? 'Scientific' : 'Literary' }})
                        @elseif ($menu == 'Course')
                            {{ $subject->name }} ({{ $subject->subject->name }}
                            {{ $subject->literaryOrScientific ? 'Scientific' : 'Literary' }})
                            @elseif ($menu == 'Teacher')
                            {{ $subject->name }}
                        @endif
                    </button>
                @endforeach
            </div>
            <br>
            <div class="dropdown-container">
                <label for="subject-dropdown" style="font-size: 30px;">
                    @if ($menu == 'Teacher')
                        {{ __('messages.addTeacher') }}
                    @elseif ($menu == 'Subject')
                        {{ __('messages.addSubject') }}
                    @endif
                </label>
                <select id="subject-dropdown" class="dropdown" style="padding:0.5rem 2.5rem">
                    <option value="">
                        @if ($menu == 'Teacher')
                            {{ __('messages.selectTeacher') }}
                        @elseif ($menu == 'Course')
                            {{ __('messages.selectCourse') }}
                        @elseif ($menu == 'Subject')
                            {{ __('messages.selectSubject') }}
                        @endif
                    </option>
                    @foreach ($menuModel as $subject)
                        @if (!in_array($subject->id, $selectedSubjects))
                            <option value="{{ $subject->id }}">
                                @if ($menu == 'Subject')
                                    {{ $subject->name }}
                                    ({{ $subject->literaryOrScientific ? 'Scientific' : 'Literary' }})
                                @elseif ($menu == 'Course')
                                    {{ $subject->name }} ({{ $subject->subject->name }}
                                    {{ $subject->literaryOrScientific ? 'Scientific' : 'Literary' }})
                                    @elseif ($menu == 'Teacher')
                                    {{ $subject->name }}

                                @endif
                            </option>
                        @endif
                    @endforeach
                </select>
                <input type="button" id="add-subject-btn" class="add-subject-btn"
                    value="@if ($menu == 'Teacher') {{ __('messages.addTeacher') }}@elseif ($menu == 'Course'){{ __('messages.addCourse') }}@elseif ($menu == 'Subject'){{ __('messages.addSubject') }} @endif">
            </div>
            <input type="hidden" name="selected_objects" id="selected_objects_input">
        @endif
        {{-- @if ($lectures != false)
            <label for="selected_lectures">
                {{ __('messages.lectures') }}<br>
                ({{ __('messages.clickToRemoveAndReAdd') }})

            </label>
            <br>
            <div id="subscribed-lectures-container" class="buttonContainer">
                @foreach ($model->lectures->pluck('id')->toArray() as $lecture)
                    <button type="button" class="lecture-button selected" data-lecture-id="{{ $lecture }}"
                        onclick="toggleLectureSelection(this)">{{ App\Models\Lecture::findOrFail($lecture)->name }}
                        <br> ({{ App\Models\Lecture::findOrFail($lecture)->course->subject->name }}
                        {{ App\Models\Lecture::findOrFail($lecture)->course->subject->literaryOrScientific ? 'Scientific' : 'Literary' }},
                        {{ App\Models\Lecture::findOrFail($lecture)->course->name }})</button>
                @endforeach
            </div>
            <div class="dropdown" id="lectureD">
                <button class="dropbtn" onclick="toggleDropdown(event)">{{ __('messages.selectLecture') }}</button>
                <div class="dropdown-content" id="lectureDropdown">
                    @foreach (App\Models\Course::all() as $subject)
                        @if (!in_array($subject->id, $model->courses->pluck('id')->toArray()))
                            <div class="subject-item">
                                <a>{{ $subject->name }} ({{ $subject->subject->name }}) >
                                    <div class="nested-dropdown">
                                        @if ($subject->lectures->isEmpty())
                                            <div style="padding:0.25rem 0.25rem; background-color:darkgray">
                                                {{ __('messages.noLecturesForSubject', ['subject' => $subject->name]) }}
                                            </div>
                                        @else
                                            @foreach ($subject->lectures as $lecture)
                                                @if (!in_array($lecture->id, $model->lectures->pluck('id')->toArray()))
                                                    <div data-lecture-id="{{ $lecture->id }}"
                                                        style="padding:0.25rem 0.25rem; cursor:pointer"
                                                        onclick="selectLecture(this)">
                                                        {{ $lecture->name }} <br>
                                                        ({{ $lecture->course->subject->name }}
                                                        {{ $lecture->course->subject->literaryOrScientific ? 'Scientific' : 'Literary' }},
                                                        {{ $lecture->course->name }})
                                                    </div>
                                                @else
                                                    <div
                                                        style="padding:0.25rem 0.25rem; cursor:pointer; color:#333333; cursor:default; background-color:darkgray">
                                                        {{ $lecture->name }} <br>
                                                        ({{ $lecture->course->subject->name }},
                                                        {{ $lecture->course->name }})
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="subject-item" style="background-color:darkgray; line-height:2.5rem">
                                {{ $subject->name }} <br> ({{ __('messages.alreadySubscribed') }})
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="selected_lectures" id="selected_lectures_input">
        @endif --}}
        <br>
        <button type="submit" class="submit-button">
            @if ($object == 'Teacher')
                {{ __('messages.editTeacher') }}
            @elseif($object == 'Admin')
                {{ __('messages.editAdmin') }}
            @elseif($object == 'Exam')
                {{ __('messages.editExam') }}
            @elseif ($object == 'User')
                {{ __('messages.editUser') }}
            @elseif ($object == 'Course')
                {{ __('messages.editCourse') }}
            @elseif ($object == 'Lecture')
                {{ __('messages.editLecture') }}
            @elseif ($object == 'Subject')
                {{ __('messages.editSubject') }}
            @elseif ($object == 'Resource')
                {{ __('messages.editResource') }}
            @else
                {{ __('messages.edit') }}
            @endif
        </button>
    </form>
</div>

<script>
    function validateImageSize(input) {
        const maxSize = 2 * 1024 * 1024;
        if (input.files && input.files[0]) {
            const fileSize = input.files[0].size;
            if (fileSize > maxSize) {
                alert('Image size must be less than 2MB.');
                input.value = '';
            }
        }
    }
</script>
@php
    $model = 'App\Models\\' . $object;
    $imagePath = asset($model::where('id', session(Str::lower($object)))->first()->image);
@endphp
<script>
    const imageInput = document.getElementById('object_image');
    const imagePreview = document.getElementById('image_preview');
    if (@json($image) != false) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = $imagePath;
            }
        });
    }
</script>
@if ($relations)
    <script>
        let initialValues = {};
        let submitButton = document.querySelector(".submit-button");
        // Check banned status
        let initialBannedStatus = @json($isBanned) ? true :
            false; // Changed to isBanned to match Laravel convention
        const bannedCheckbox = document.getElementById('isBanned'); // Changed to match HTML id
        if (bannedCheckbox) {
            if (bannedCheckbox.checked !== initialBannedStatus) {
                hasChanged = true;
            }
        }
        document.querySelectorAll(
            "input[type='text'], input[type='password'], input[type='file'], input[type='url'], input[type='number'], textarea"
        ).forEach(
            input => {
                initialValues[input.name] = input.value;
            });

        // Store initial values for checkboxes
        document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
            initialValues[checkbox.name] = checkbox.checked;
        });

        // Store initial value for sources JSON
        const sourcesJsonInput = document.getElementById('sources-json');
        if (sourcesJsonInput) {
            initialValues['sources'] = sourcesJsonInput.value;
        }

        function checkForChanges() {
            let hasChanged = false;
            document.querySelectorAll(
                "input[type='text'], input[type='password'], input[type='file'], input[type='url'], input[type='number'], textarea"
            ).forEach(
                input => {
                    if (input.value !== initialValues[input.name]) hasChanged = true;
                });

            // Check checkboxes (including course_paid switch)
            document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
                const initialValue = initialValues[checkbox.name];
                if (checkbox.checked !== (initialValue === '1' || initialValue === true)) {
                    hasChanged = true;
                }
            });

            // Check sources JSON
            const sourcesJsonInput = document.getElementById('sources-json');
            if (sourcesJsonInput && sourcesJsonInput.value !== initialValues['sources']) {
                hasChanged = true;
            }

            const bannedCheckbox = document.getElementById('isBanned'); // Changed to match HTML id
            if (bannedCheckbox) {
                if (bannedCheckbox.checked !== initialBannedStatus) {
                    hasChanged = true;
                }
            }
            let initialSubjectsSet = new Set(@json($selectedSubjects).map(String));
            let selectedSubjectsSet = new Set([...selectedSubjects].map(String));
            console.log(initialSubjectsSet);
            console.log(selectedSubjectsSet);
            if (!setsAreEqual(initialSubjectsSet, selectedSubjectsSet)) hasChanged = true;
            @if ($lectures != false)
                let initialLecturesSet = new Set(@json($selectedLectures).map(String));
                let selectedLecturesSet = new Set([...selectedLectures].map(String));
                if (!setsAreEqual(initialLecturesSet, selectedLecturesSet)) hasChanged = true;
            @endif
            submitButton.disabled = !hasChanged;
            // Initialize banned checkbox listener
        }

        function setsAreEqual(setA, setB) {
            if (setA.size !== setB.size) return false;
            for (let item of setA)
                if (!setB.has(item)) return false;
            return true;
        }

        let selectedSubjects = new Set();
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".subject-button").forEach(button => {
                let subjectId = button.getAttribute("data-subject-id");
                selectedSubjects.add(subjectId);
                button.addEventListener("click", function() {
                    toggleSubjectSelection(this, subjectId);
                });
            });
            const bannedCheckbox = document.getElementById('isBanned');
            if (bannedCheckbox) {
                bannedCheckbox.addEventListener('change', checkForChanges);
            }
            document.getElementById('add-subject-btn').addEventListener('click', function() {
                let dropdown = document.getElementById('subject-dropdown');
                let selectedSubjectId = dropdown.value;
                let selectedSubjectName = dropdown.options[dropdown.selectedIndex].text;
                if (!selectedSubjectId || selectedSubjects.has(selectedSubjectId)) return;
                let buttonContainer = document.getElementById('subject-buttons-container');
                let newButton = document.createElement('button');
                newButton.type = "button";
                newButton.classList.add('subject-button', 'selected');
                newButton.setAttribute('data-subject-id', selectedSubjectId);
                newButton.textContent = selectedSubjectName;
                newButton.style.backgroundColor = "#555184";
                newButton.style.color = "";
                newButton.addEventListener('click', function() {
                    toggleSubjectSelection(this, selectedSubjectId);
                });
                buttonContainer.appendChild(newButton);
                selectedSubjects.add(selectedSubjectId);
                checkForChanges();
            });
        });

        function toggleSubjectSelection(button, subjectId) {
            if (selectedSubjects.has(subjectId)) {
                selectedSubjects.delete(subjectId);
                button.classList.remove("selected");
                button.style.backgroundColor = "";
                button.style.color = "#555184";
            } else {
                selectedSubjects.add(subjectId);
                button.classList.add("selected");
                button.style.backgroundColor = "#555184";
                button.style.color = "#FFFFFF";
            }
            checkForChanges();
        }

        function updateHiddenInput() {
            document.getElementById('selected_objects_input').value = JSON.stringify(Array.from(selectedSubjects));
        }
        document.querySelectorAll(
            "input[type='text'], input[type='password'], input[type='file'], input[type='url'], input[type='number'], select, textarea"
        ).forEach(input => {
            input.addEventListener("input", checkForChanges);
        });

        // Add event listeners for checkboxes
        document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
            checkbox.addEventListener("change", checkForChanges);
        });
        submitButton.disabled = true;
    </script>
@else
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let submitButton = document.querySelector(".submit-button");
            let form = document.querySelector("form");
            let initialValues = {};

            document.querySelectorAll(
                "input[type='text'], input[type='password'], input[type='file'], input[type='url'], input[type='number'], select, textarea"
            ).forEach(
                input => {
                    initialValues[input.name] = input.value;
                });

            // Store initial values for checkboxes
            document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
                initialValues[checkbox.name] = checkbox.checked;
            });

            // Store initial value for sources JSON - delay to ensure sources are properly initialized
            setTimeout(() => {
                const sourcesJsonInput = document.getElementById('sources-json');
                if (sourcesJsonInput) {
                    initialValues['sources'] = sourcesJsonInput.value;
                }
            }, 150);

            function checkForChanges() {
                let hasChanged = false;
                document.querySelectorAll(
                    "input[type='text'], input[type='password'], input[type='file'], input[type='url'], input[type='number'], select, textarea"
                ).forEach(
                    input => {
                        if (input.value !== initialValues[input.name]) hasChanged = true;
                    });

                // Check checkboxes (including course_paid switch)
                document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
                    const initialValue = initialValues[checkbox.name];
                    if (checkbox.checked !== (initialValue === '1' || initialValue === true)) {
                        hasChanged = true;
                    }
                });

                // Check sources JSON
                const sourcesJsonInput = document.getElementById('sources-json');
                if (sourcesJsonInput && sourcesJsonInput.value !== initialValues['sources']) {
                    // hasChanged = true;
                }

                submitButton.disabled = !hasChanged;
            }

            document.querySelectorAll(
                "input[type='text'], input[type='password'], input[type='file'], input[type='number'], select, textarea"
            ).forEach(
                input => {
                    input.addEventListener("input", checkForChanges);
                    input.addEventListener("change", checkForChanges);
                });

            // Add event listeners for checkboxes
            document.querySelectorAll("input[type='checkbox']").forEach(checkbox => {
                checkbox.addEventListener("change", checkForChanges);
            });
            submitButton.disabled = true;

            // Global function to trigger change detection (for sources updates)
            window.triggerEditCardChangeDetection = function() {
                checkForChanges();
            };
        });
    </script>
@endif
<script>
    const object = @json($object);
    const authID = @json(auth()->id());
    const sessionID = @json(session('admin'));

    function confirmEdit() {
        if (object === "Admin" && authID == sessionID) return confirm(
            "Changing your info will require logging out.\n\nAre you sure you want to proceed?");
    }
</script>
@if ($lectures != false)
    <script>
        let selectedLectures = new Set(@json($subscribedLectureIds).map(id => parseInt(id)));

        function toggleLectureSelection(button) {
            const lectureId = parseInt(button.getAttribute('data-lecture-id'));
            if (selectedLectures.has(lectureId)) {
                selectedLectures.delete(lectureId);
                button.classList.remove("selected");
                button.style.backgroundColor = "";
                button.style.color = "#555184";
            } else {
                selectedLectures.add(lectureId);
                button.classList.add("selected");
                button.style.backgroundColor = "#555184";
                button.style.color = "#FFFFFF";
            }
            document.getElementById('selected_lectures_input').value = JSON.stringify(Array.from(selectedLectures));
            checkForChanges();
        }

        function addLectureButton(lectureId, lectureName) {
            const buttonContainer = document.getElementById('subscribed-lectures-container');
            const newButton = document.createElement('button');
            newButton.type = "button";
            newButton.classList.add('lecture-button', 'selected');
            newButton.setAttribute('data-lecture-id', lectureId);
            newButton.textContent = lectureName;
            newButton.addEventListener('click', function() {
                toggleLectureSelection(this);
            });
            buttonContainer.appendChild(newButton);
            checkForChanges();
        }

        function removeLectureButton(lectureId) {
            const button = document.querySelector(`.lecture-button[data-lecture-id="${lectureId}"]`);
            if (button) button.remove();
            checkForChanges();
        }

        function selectLecture(element) {
            const lectureId = parseInt(element.getAttribute('data-lecture-id'));
            if (selectedLectures.has(lectureId)) {

                // selectedLectures.delete(lectureId);
                // element.style.backgroundColor = '';
                // element.style.color = '';
                // removeLectureButton(lectureId);

            } else {
                selectedLectures.add(lectureId);
                element.style.backgroundColor = '';
                element.style.color = '';
                addLectureButton(lectureId, element.textContent);
            }
            document.getElementById('selected_lectures_input').value = JSON.stringify(Array.from(selectedLectures));
            checkForChanges();
        }
        // Function to toggle the dropdown
        function toggleDropdown(event) {
            event.preventDefault(); // Prevent the button from submitting the form
            const dropdownContent = event.target.nextElementSibling;
            if (dropdownContent && dropdownContent.classList.contains('dropdown-content')) {
                dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
            }
        }

        // Close the dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('lectureD');
            const dropdownContent = document.getElementById('lectureDropdown');

            // Check if the click is outside the dropdown
            if (!dropdown.contains(event.target)) {
                dropdownContent.style.display = 'none'; // Hide the dropdown
            }
        });
    </script>
@endif
