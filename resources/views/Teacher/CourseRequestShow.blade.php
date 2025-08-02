<<<<<<< HEAD
=======
<<<<<<< HEAD
<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => route('teacher.course_requests.index'), __('messages.courseRequestShow') => Request::url()]" />
    <x-cardcontainer :model="[]" addLink="{{ route('teacher.course_requests.create') }}" :showNameSort="false" num="0">
        <x-card :object="$request" image="/Images/Web/MindSpark.png">
            <h2 style="margin-bottom: 1rem;">{{ __('messages.courseRequestDetails') }}</h2>
            <p><strong>{{ __('messages.courseRequestName') }}:</strong> {{ $request->name }}</p>
            <p><strong>{{ __('messages.courseRequestDescription') }}:</strong> {{ $request->description }}
            </p>
            <p><strong>{{ __('messages.courseRequestSubject') }}:</strong>
                {{ $request->subject ? $request->subject->name : $request->subject_id }}</p>
            <p><strong>Lectures Count:</strong> {{ $request->lecturesCount ?? 'N/A' }}</p>
            <p><strong>Subscriptions:</strong> {{ $request->subscriptions ?? 'N/A' }}</p>
            <p><strong>{{ __('messages.courseRequestStatus') }}:</strong>
                @if($request->status === 'pending')
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
            <a href="{{ route('teacher.course_requests.index') }}"
                class="btn btn-secondary mt-3">{{ __('messages.backToRequests') }}</a>

        </x-card>
    </x-cardcontainer>
=======
>>>>>>> 7eee2c33febddca43ae4a164832d8d78027d64d6

@props(['courseRequest' => App\Models\CourseRequest::findOrFail(session('courseRequest'))])

<x-layout>
    <x-breadcrumb :links="[
        __('messages.home') => url('/welcome'),
        __('messages.yourRequests') => route('admin.course_requests.index'),
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
<<<<<<< HEAD
=======
>>>>>>> a239985f5d0e6f8a5ad9a53b67fa56104e903321
>>>>>>> 7eee2c33febddca43ae4a164832d8d78027d64d6
</x-layout>
