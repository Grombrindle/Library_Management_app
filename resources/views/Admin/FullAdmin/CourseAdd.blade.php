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
            <label for="subject">
                {{ __('messages.subject') }}: <br>
            </label>
            <select name="subject" id="subject" required>
                <option value="" selected>{{ __('messages.selectSubject') }}</option>
                @foreach (App\Models\Subject::all() as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="teacher">
                {{ __('messages.teacher') }}: <br>
            </label>
            <select name="teacher" id="teacher" required disabled>
                <option value="" selected>{{ __('messages.selectTeacher') }}</option>
            </select>

        </div>
    </x-addcard>
</x-layout>

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
</script>
