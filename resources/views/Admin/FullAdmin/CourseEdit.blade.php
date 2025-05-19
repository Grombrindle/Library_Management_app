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
    </x-editcard>
    </div>
</x-layout>
