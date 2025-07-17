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
</x-layout>
