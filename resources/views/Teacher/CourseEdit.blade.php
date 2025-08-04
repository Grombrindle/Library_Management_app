@props(['course' => App\Models\Course::findOrFail(session('course'))])
<x-layout>



    <x-editcard link="editcourse/{{ session('course') }}" object="Course" :objectModel=$course :image=true>
        <div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="course_name" style="margin-bottom:10%;">
                    {{ __('messages.courseName') }}:
                </label>
                <input type="text" name="course_name" id="course_name" value="{{ $course->name }}"
                    style="height:20%; text-align:center; font-size:40%; width:fit-content;margin-bottom:10%;">
            </div>
            <div style="display:flex; flex-direction:column; align-items:center;">
                <label for="course_description" style="margin-bottom:10%;">
                    {{ __('messages.description') }}:
                </label>
                <textarea name="course_description" id="course_description" autocomplete="off" style="height:150px; width:80%; font-size:16px; padding:10px; resize:vertical;" required="">{{ $course->description }}</textarea>
            </div>

            <!-- Sources Section -->
            <div id="sources-section" style="width:80%; margin-bottom: 1.5rem;">
                <label>{{ __('messages.sources') ?? 'Sources' }}:</label>
                <div id="sources-list">
                    @php
                        $sources = is_array($course->sources) ? $course->sources : json_decode($course->sources, true);
                    @endphp
                    @if ($sources)
                        @foreach ($sources as $source)
                            <div style="display:flex; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center;">
                                <input type="text" placeholder="Source Name" class="source-name" value="{{ $source['name'] }}" style="flex:1;" required>
                                <input type="url" placeholder="Source Link" class="source-link" value="{{ $source['link'] }}" style="flex:2;" required>
                                <button type="button" onclick="this.parentElement.remove(); updateSourcesJson();">&times;</button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addSourceField()" style="margin-top: 0.5rem;">+ Add Source</button>
                <input type="hidden" name="sources" id="sources-json" value="{{ $course->sources }}">
            </div>
            <!-- End Sources Section -->

            <!-- Price Section -->
            <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
                <label for="course_price" style="margin-bottom:5%;">
                    Course Price:
                </label>
                <div style="position:relative; width: fit-content; height:fit-content;">
                    <input type="number" name="course_price" id="course_price" value="{{ $course->price ?? 0 }}"
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
                        {{ ($course->is_paid ?? false) ? 'Purchaseable with Sparkies' : 'Unpurchaseable with Sparkies' }}
                    </span>
                </div>
                <label class="switch">
                    <input type="checkbox" name="course_paid" id="course_paid" value="1" {{ ($course->is_paid ?? false) ? 'checked' : '' }}>
                    <span class="slider round course-switch"></span>
                </label>
            </div>
    </x-editcard>
    </div>
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
    #sources-list > div {
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
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
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
    #sources-section > button[type="button"] {
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
    #sources-section > button[type="button"]:hover {
        background: #218838;
    }
    /* Course switch styling */
    input:checked + .course-switch {
        background-color: #555184;
    }
</style>

<script>
    // Sources dynamic fields logic
    function addSourceField(name = '', link = '') {
        const list = document.getElementById('sources-list');
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.gap = '0.5rem';
        div.style.marginBottom = '0.5rem';
        div.style.alignItems = 'center';
        div.innerHTML = `
            <input type="text" placeholder="Source Name" class="source-name" value="${name}" style="flex:1;" required>
            <input type="url" placeholder="Source Link" class="source-link" value="${link}" style="flex:2;" required>
            <button type="button" onclick="this.parentElement.remove(); updateSourcesJson();">&times;</button>
        `;
        list.appendChild(div);
        
        // Add event listeners to new inputs
        div.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', updateSourcesJson);
            input.addEventListener('change', updateSourcesJson);
        });
        
        updateSourcesJson();
    }

    function updateSourcesJson() {
        const names = document.querySelectorAll('.source-name');
        const links = document.querySelectorAll('.source-link');
        const sources = [];
        
        for (let i = 0; i < names.length; i++) {
            if (names[i].value.trim() && links[i].value.trim()) {
                sources.push({
                    name: names[i].value.trim(),
                    link: links[i].value.trim()
                });
            }
        }
        
        document.getElementById('sources-json').value = JSON.stringify(sources);

        // Trigger edit card change detection
        // if (window.triggerEditCardChangeDetection) {
        //     window.triggerEditCardChangeDetection();
        // }
    }

    // Ensure sources are updated before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        updateSourcesJson();
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

        // Add event listeners to existing source inputs
        document.querySelectorAll('.source-name, .source-link').forEach(input => {
            input.addEventListener('input', updateSourcesJson);
            input.addEventListener('change', updateSourcesJson);
        });
        
        // Wait for the editCard component to initialize first, then update sources
        setTimeout(() => {
            updateSourcesJson();
        }, 100);
    });
</script>
