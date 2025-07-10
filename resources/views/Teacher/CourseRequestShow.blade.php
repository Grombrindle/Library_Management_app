
@props(['courseRequest' => App\Models\CourseRequest::findOrFail(session('courseRequest'))])

<x-layout>
    <x-breadcrumb :links="[
        __('messages.home') => url('/welcome'),
        __('messages.courseRequests') => route('admin.course_requests.index'),
        __('messages.courseRequestShow') => Request::url(),
    ]" />
    <x-infocard :object="$courseRequest" image="/Images/Web/MindSpark.png" objectType="CourseRequest"
        name="{{ $courseRequest->name }}">
        <h2 style="margin-bottom: 1rem;">{{ __('messages.courseRequestDetails') }}</h2>
        <p><strong>{{ __('messages.courseRequestName') }}:</strong> {{ $request->name }}</p>
        <p><strong>{{ __('messages.courseRequestDescription') }}:</strong> {{ $request->description }}</p>
        <p><strong>{{ __('messages.courseRequestSubject') }}:</strong> {{ $request->subject->name ?? 'N/A' }} ({{ $request->subject->literaryOrScientific ? 'Scientific' : 'Literary' }})</p>
        <p><strong>{{ __('messages.courseRequestTeacher') }}:</strong> {{ $request->teacher->name ?? 'N/A' }}</p>
        <p><strong>{{ __('messages.courseRequestStatus') }}:</strong>
            @if ($request->status === 'pending')
                <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
            @elseif($request->status === 'approved')
                <span class="badge bg-success">{{ ucfirst($request->status) }}</span>
            @else
                <span class="badge bg-danger">{{ ucfirst($request->status) }}</span>
            @endif
        </p>
        @if ($request->status === 'rejected')
            <p style="color:red"><strong>{{ __('messages.courseRequestRejectionReason') }}:</strong>
                {{ $request->rejection_reason }}</p>
        @endif
    </x-infocard>
</x-layout>
