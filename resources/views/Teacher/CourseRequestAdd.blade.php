{{-- @extends('layouts.app') --}}
<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => route('teacher.course_requests.index'), __('messages.courseRequestAdd') => Request::url()]" />
    <x-cardcontainer :model="[]" addLink="" :showNameSort="false" num="0">
        <x-card object="CourseRequest" image="/Images/Web/MindSpark.png">
            <h2 style="margin-bottom: 1rem;">{{ __('messages.courseRequestAdd') }}</h2>
            <form method="POST" action="{{ route('teacher.course_requests.store') }}" style="max-width:500px; margin:auto;">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">{{ __('messages.courseRequestName') }}</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="description">{{ __('messages.courseRequestDescription') }}</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="subject_id">{{ __('messages.courseRequestSubject') }}</label>
                    <input type="number" name="subject_id" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="image">{{ __('messages.courseRequestImage') }}</label>
                    <input type="text" name="image" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="sources">{{ __('messages.courseRequestSources') }}</label>
                    <input type="text" name="sources" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="price">{{ __('messages.courseRequestPrice') }}</label>
                    <input type="text" name="price" class="form-control">
                </div>
                <button type="submit" class="btn btn-success w-100">{{ __('messages.courseRequestSubmit') }}</button>
            </form>
        </x-card>
    </x-cardcontainer>
</x-layout>
