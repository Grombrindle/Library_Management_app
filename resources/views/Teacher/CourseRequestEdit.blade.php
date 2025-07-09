<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => route('teacher.course_requests.index'), __('messages.courseRequestEdit') => Request::url()]" />
    <x-cardcontainer :model="[]" addLink="" :showNameSort="false" num="0">
        <x-card object="CourseRequest" image="/Images/Web/MindSpark.png">
            <h2 style="margin-bottom: 1rem;">{{ __('messages.courseRequestEdit') }}</h2>
            <form method="POST" action="{{ route('teacher.course_requests.update', $request->id) }}" style="max-width:500px; margin:auto;">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="name">{{ __('messages.courseRequestName') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ $request->name }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="description">{{ __('messages.courseRequestDescription') }}</label>
                    <textarea name="description" class="form-control" required>{{ $request->description }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="subject_id">{{ __('messages.courseRequestSubject') }}</label>
                    <input type="number" name="subject_id" class="form-control" value="{{ $request->subject_id }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="image">{{ __('messages.courseRequestImage') }}</label>
                    <input type="text" name="image" class="form-control" value="{{ $request->image }}">
                </div>
                <div class="form-group mb-3">
                    <label for="sources">{{ __('messages.courseRequestSources') }}</label>
                    <input type="text" name="sources" class="form-control" value='{{ json_encode($request->sources) }}'>
                </div>
                <div class="form-group mb-3">
                    <label for="price">{{ __('messages.courseRequestPrice') }}</label>
                    <input type="text" name="price" class="form-control" value="{{ $request->price }}">
                </div>
                <button type="submit" class="btn btn-success w-100">{{ __('messages.courseRequestResubmit') }}</button>
            </form>
        </x-card>
    </x-cardcontainer>
</x-layout>
