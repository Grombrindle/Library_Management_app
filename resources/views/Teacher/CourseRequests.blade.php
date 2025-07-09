<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => Request::url()]" />
    <x-cardcontainer :model="$requests" addLink="{{ route('teacher.course_requests.create') }}" :showNameSort="false" num="{{ $requests->count() }}">
        <div style="width:100%; display:flex; flex-direction:column; gap:20px;">
            @foreach ($requests as $request)
                <x-card object="CourseRequest" image="/Images/Web/MindSpark.png" link="{{ route('teacher.course_requests.show', $request->id) }}">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong>{{ __('messages.courseRequestName') }}:</strong> {{ $request->name }}<br>
                            <strong>{{ __('messages.courseRequestStatus') }}:</strong>
                            @if($request->status === 'pending')
                                <span class="badge bg-warning">{{ __('messages.courseRequestStatus') }}: {{ ucfirst($request->status) }}</span>
                            @elseif($request->status === 'approved')
                                <span class="badge bg-success">{{ __('messages.courseRequestStatus') }}: {{ ucfirst($request->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('messages.courseRequestStatus') }}: {{ ucfirst($request->status) }}</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('teacher.course_requests.show', $request->id) }}" class="btn btn-info btn-sm">{{ __('messages.view') }}</a>
                            @if ($request->status === 'rejected')
                                <a href="{{ route('teacher.course_requests.edit', $request->id) }}" class="btn btn-warning btn-sm">{{ __('messages.courseRequestEdit') }}</a>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    </x-cardcontainer>
</x-layout>
