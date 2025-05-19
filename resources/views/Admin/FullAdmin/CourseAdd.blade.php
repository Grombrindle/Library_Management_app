<x-layout>
    <x-addcard : link="addcourse" object="Course">

        <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">

            <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:10%;">
                <label for="course_name">
                    {{ __('messages.courseName') }}:
                </label>
                <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}"
                    autocomplete="off" style="height:20%; text-align:center; font-size:40%; width:fit-content;"
                    required>
            </div>
            @error('course_name')
                <div class="error">{{ $message }}</div>
            @enderror
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
            <select name="teacher" id="teacher" required>
                <option value="" selected>{{ __('messages.selectTeacher') }}</option>
                @foreach (App\Models\Teacher::all() as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
            <br>
            </div>
    </x-addcard>
</x-layout>