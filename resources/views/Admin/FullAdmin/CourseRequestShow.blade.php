<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => route('admin.course_requests.index'), __('messages.courseRequestShow') => Request::url()]" />
    <x-cardcontainer :model="[]" addLink="" :showNameSort="false" num="0">
        <x-card object="CourseRequest" image="/Images/Web/MindSpark.png">
            <h2 style="margin-bottom: 1rem;">{{ __('messages.courseRequestDetails') }}</h2>
            <p><strong>{{ __('messages.courseRequestName') }}:</strong> {{ $request->name }}</p>
            <p><strong>{{ __('messages.courseRequestDescription') }}:</strong> {{ $request->description }}</p>
            <p><strong>{{ __('messages.courseRequestSubject') }}:</strong> {{ $request->subject_id }}</p>
            <p><strong>{{ __('messages.courseRequestTeacher') }}:</strong> {{ $request->teacher->name ?? 'N/A' }}</p>
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
                <p style="color:red"><strong>{{ __('messages.courseRequestRejectionReason') }}:</strong> {{ $request->rejection_reason }}</p>
            @endif
            @if ($request->status === 'pending')
                <form action="{{ route('admin.course_requests.approve', $request->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success">{{ __('messages.approve') }}</button>
                </form>
                <form action="{{ route('admin.course_requests.reject', $request->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="{{ __('messages.rejectionReason') }}" class="form-control" style="width:200px;display:inline-block;">
                    <button type="submit" class="btn btn-danger">{{ __('messages.reject') }}</button>
                </form>
            @endif
            <a href="{{ route('admin.course_requests.index') }}" class="btn btn-secondary mt-3">{{ __('messages.backToRequests') }}</a>
        </x-card>
    </x-cardcontainer>
</x-layout>
