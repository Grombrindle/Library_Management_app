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

            <!-- Price Section -->
            <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
                <label for="course_price" style="margin-bottom:5%;">
                    Course Price:
                </label>
                <div style="position:relative; width: fit-content; height:fit-content;">
                    <input type="number" name="course_price" id="course_price" value="{{ $course->price ?? 0 }}"
                        min="0" step="0.01" autocomplete="off"
                        style="height:20%; text-align:center; font-size:40%; width:fit-content; padding-left:20px;" required>
                    <span style="position: absolute; left: 8px; top: 60%; transform: translateY(-50%); color: black; pointer-events: none;">$</span>
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
                    <input type="checkbox" name="course_paid" id="course_paid" value="1" {{ ($course->sparkies ?? false) ? 'checked' : '' }}>
                    <span class="slider round course-switch"></span>
                </label>
            </div>
    </x-editcard>
    </div>
</x-layout>

<style>
    /* Course switch styling */
    input:checked + .course-switch {
        background-color: #555184;
    }
</style>

<script>
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
