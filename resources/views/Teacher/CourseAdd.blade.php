@props(['subjectID' => null])
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
            @foreach (App\Models\Teacher::findOrFail(Auth::user()->teacher_id)->subjects as $subject)
                @if ($subjectID != null && $subjectID == $subject->id)
                    <option value="{{ $subject->id }}" selected>{{ $subject->name }}</option>
                @else
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endif
            @endforeach
        </select>
        </div>
    </x-addcard>
</x-layout>
<script>
    if (@json($subjectID)) {
            function smoothScrollToElement(element, duration = 1000) {
                const elementPosition = element.getBoundingClientRect().top;
                const startPosition = window.pageYOffset;
                const distance = elementPosition - 400; // Adjust offset (e.g., -100px from top)
                let startTime = null;

                function animation(currentTime) {
                    if (!startTime) startTime = currentTime;
                    const timeElapsed = currentTime - startTime;
                    const scrollAmount = easeInOutQuad(
                        timeElapsed,
                        startPosition,
                        distance,
                        duration
                    );
                    window.scrollTo(0, scrollAmount);
                    if (timeElapsed < duration) requestAnimationFrame(animation);
                }

                function easeInOutQuad(t, b, c, d) {
                    t /= d / 2;
                    if (t < 1) return (c / 2) * t * t + b;
                    t--;
                    return (-c / 2) * (t * (t - 2) - 1) + b;
                }

                requestAnimationFrame(animation);
            }

            // Usage
            smoothScrollToElement(document.getElementById('subject'), 1200); // 800ms duration
        }
</script>
