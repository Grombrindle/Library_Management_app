@props(['subjectID' => null])

<x-layout>
    <x-addcard : link="addcourse" object="Course">
        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">

            <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
                <label for="course_name">
                    {{ __('messages.courseName') }}:
                </label>
                <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}" autocomplete="off"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;" required>
            </div>
            @error('course_name')
                <div class="error">{{ $message }}</div>
            @enderror
            <div style="display:flex; flex-direction:column; align-items:center; height:100%;">
                <label for="course_description">
                    {{ __('messages.courseDescription') }}:
                </label>
                <textarea name="course_description" id="course_description" autocomplete="off" value="{{ old('course_description') }}"
                    style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;max-height:500px;"></textarea>
            </div>
            <br>
            <!-- Sources Section -->
            <div id="sources-section" style="width:80%; margin-bottom: 1.5rem;">
                <label>{{ __('messages.sources') ?? 'Sources' }}:</label>
                <div id="sources-list"></div>
                <button type="button" onclick="addSourceField()" style="margin-top: 0.5rem;">+ Add Source</button>
                <input type="hidden" name="sources" id="sources-json">
            </div>
            <!-- End Sources Section -->
            <!-- Requirements Section -->
            <div id="requirements-section"
                style="width:80%; margin-bottom: 1.5rem; display:flex; flex-direction:column; align-items:center;">
                <label style="font-weight:600; margin-bottom:0.5rem;">Requirements:</label>
                <div id="requirements-buttons"
                    style="display:flex; flex-wrap:wrap; gap:0.5rem; justify-content:center;">
                    <button type="button" class="requirement-btn" data-value="A Brain">A Brain</button>
                    <button type="button" class="requirement-btn" data-value="Calculator">Calculator</button>
                    <button type="button" class="requirement-btn" data-value="Pen and Paper">Pen and Paper</button>
                    <button type="button" class="requirement-btn" data-value="Laptop">Laptop</button>
                    <button type="button" class="requirement-btn" data-value="Textbook">Textbook</button>
                    <button type="button" class="requirement-btn" data-value="Internet Access">Internet Access</button>
                    <button type="button" class="requirement-btn" data-value="Notebook">Notebook</button>
                    <button type="button" class="requirement-btn" data-value="Headphones">Headphones</button>
                    <button type="button" class="requirement-btn" data-value="Other">Other</button>
                </div>
                <input type="hidden" name="requirements" id="requirements-hidden">
            </div>
            <!-- End Requirements Section -->
            <!-- Price Section -->
            <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
                <label for="course_price" style="margin-bottom:5%;">
                    Course Price:
                </label>
                <div style="position:relative; width: fit-content; height:fit-content;">
                    <input type="number" name="course_price" id="course_price" value="{{ old('course_price', 0) }}"
                        min="0" step="0.01" autocomplete="off"
                        style="height:20%; text-align:center; font-size:40%; width:fit-content; padding-left:20px;" required>
                    <span style="position: absolute; left: 8px; top: 60%; transform: translateY(-50%); font-size:40%; color: black; pointer-events: none;">$</span>
                </div>
            </div>
            @error('course_price')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Price Toggle Section -->
            <div style="margin-top: 20px; display: flex; align-items: center; flex-direction:column; justify-content: space-between; margin-left:auto; margin-right:auto; width:fit-content">
                <div>
                    <label for="course_paid" style="font-weight: bold;">
                        Course Status
                    </label>
                    <span style="margin-left: 10px;">
                        {{ old('course_paid') ? 'Paid Course' : 'Free Course' }}
                    </span>
                </div>
                <label class="switch">
                    <input type="checkbox" name="course_paid" id="course_paid" value="1" {{ old('course_paid') ? 'checked' : '' }}>
                    <span class="slider round course-switch"></span>
                </label>
            </div>

            <label for="subject">
                {{ __('messages.subject') }}: <br>
            </label>
            <select name="subject" id="subject" required>
                <option value="" selected>{{ __('messages.selectSubject') }}</option>
                @foreach (App\Models\Teacher::findOrFail(Auth::user()->teacher_id)->subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}
                        ({{ $subject->literaryOrScientific ? 'Literary' : 'Scientific' }})
                    </option>
                @endforeach
            </select>
            <br>
        </div>
    </x-addcard>
</x-layout>

<style>
    #sources-section {
        border-radius: 8px;
        padding: 1rem 1.5rem 1.5rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    #sources-section label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    #sources-list>div {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        align-items: center;
    }

    #sources-list input[type="text"],
    #sources-list input[type="url"] {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #fff;
    }

    #sources-list input[type="text"]:focus,
    #sources-list input[type="url"]:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .15);
        outline: none;
    }

    #sources-list button[type="button"] {
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background 0.2s;
    }

    #sources-list button[type="button"]:hover {
        background: #c82333;
    }

    #sources-section>button[type="button"] {
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        margin-top: 0.5rem;
        cursor: pointer;
        transition: background 0.2s;
    }

    #sources-section>button[type="button"]:hover {
        background: #218838;
    }

    #requirements-buttons .requirement-btn {
        background: #555184;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0.5rem 1.2rem;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    #requirements-buttons .requirement-btn.selected {
        background: #9997BC;
        color: #222;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(85, 81, 132, 0.15);
    }

    /* Course switch styling */
    input:checked + .course-switch {
        background-color: #555184;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subjectSelect = document.getElementById('subject');
        const teacherSelect = document.getElementById('teacher');

        // Store all teachers with their subjects
        const teachersData = @json(App\Models\Teacher::with('subjects')->get());
        subjectSelect.addEventListener('change', function() {
            const subjectId = parseInt(this.value);

            // Reset and disable teacher select
            teacherSelect.innerHTML =
                '<option value="" selected>{{ __('messages.selectTeacher') }}</option>';
            teacherSelect.disabled = true;

            if (subjectId) {
                // Filter teachers who teach the selected subject
                const teachersForSubject = teachersData.filter(teacher =>
                    teacher.subjects.some(subject => subject.id === subjectId)
                );

                // Add filtered teachers to select
                teachersForSubject.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.textContent = teacher.name;
                    teacherSelect.appendChild(option);
                });

                teacherSelect.disabled = false;
            }
        });
    });

    // Sources dynamic fields logic
    function addSourceField(name = '', link = '') {
        const list = document.getElementById('sources-list');
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.gap = '0.5rem';
        div.style.marginBottom = '0.5rem';
        div.innerHTML = `
            <input type="text" placeholder="Source Name" class="source-name" value="${name}" style="flex:1;" required>
            <input type="url" placeholder="Source Link" class="source-link" value="${link}" style="flex:2;" required>
            <button type="button" onclick="this.parentElement.remove(); updateSourcesJson();">&times;</button>
        `;
        list.appendChild(div);
        updateSourcesJson();
        // Update on input
        div.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', updateSourcesJson);
        });
    }

    function updateSourcesJson() {
        const names = document.querySelectorAll('.source-name');
        const links = document.querySelectorAll('.source-link');
        const sources = [];
        for (let i = 0; i < names.length; i++) {
            if (names[i].value && links[i].value) {
                sources.push({
                    name: names[i].value,
                    link: links[i].value
                });
            }
        }
        document.getElementById('sources-json').value = JSON.stringify(sources);
    }
    // Ensure sources are updated before submit
    document.querySelector('form').addEventListener('submit', updateSourcesJson);

    // Requirements button toggle logic
    document.addEventListener('DOMContentLoaded', function() {
        const reqButtons = document.querySelectorAll('#requirements-buttons .requirement-btn');
        const reqHidden = document.getElementById('requirements-hidden');
        reqButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                btn.classList.toggle('selected');
                updateRequirementsHidden();
            });
        });

        function updateRequirementsHidden() {
            const selected = Array.from(reqButtons).filter(b => b.classList.contains('selected')).map(b => b
                .getAttribute('data-value'));
            reqHidden.value = JSON.stringify(selected);
        }
        // Ensure requirements are updated before submit
        document.querySelector('form').addEventListener('submit', updateRequirementsHidden);
    });

    // Course switch functionality
    document.addEventListener('DOMContentLoaded', function() {
        const courseSwitch = document.getElementById('course_paid');
        const statusSpan = courseSwitch.parentElement.parentElement.querySelector('span');

        function updateStatus() {
            if (courseSwitch.checked) {
                statusSpan.textContent = 'Purchaseable with Sparkies';
            } else {
                statusSpan.textContent = 'Unpurchaseable with Sparkies';
            }
        }

        courseSwitch.addEventListener('change', updateStatus);
        updateStatus(); // Set initial status
    });
</script>
