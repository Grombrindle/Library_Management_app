<x-layout>
    <x-breadcrumb :links="[__('messages.home') => url('/welcome'), __('messages.courseRequests') => Request::url()]" />
    <x-cardcontainer :model="$requests" addLink="" :showNameSort="false" num="{{ $requests->count() }}">
        <div style="width:100%; display:flex; flex-direction:column; gap:20px;">
            @foreach ($requests as $request)
                <x-card object="CourseRequest" image="/Images/Web/MindSpark.png" link="{{ route('admin.course_requests.show', $request->id) }}">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong>{{ __('messages.courseRequestName') }}:</strong> {{ $request->name }}<br>
                            <strong>{{ __('messages.courseRequestTeacher') }}:</strong> {{ $request->teacher->name ?? 'N/A' }}<br>
                            <strong>{{ __('messages.courseRequestStatus') }}:</strong>
                            @if($request->status === 'pending')
                                <span class="badge bg-warning">{{ ucfirst($request->status) }}</span>
                            @elseif($request->status === 'approved')
                                <span class="badge bg-success">{{ ucfirst($request->status) }}</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($request->status) }}</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('admin.course_requests.show', $request->id) }}" class="btn btn-info btn-sm">{{ __('messages.view') }}</a>
                            @if ($request->status === 'pending')
                                <form action="{{ route('admin.course_requests.approve', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">{{ __('messages.approve') }}</button>
                                </form>
                                <form action="{{ route('admin.course_requests.reject', $request->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <input type="text" name="rejection_reason" placeholder="{{ __('messages.rejectionReason') }}" class="form-control form-control-sm" style="width:150px;display:inline-block;">
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('messages.reject') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    </x-cardcontainer>
</x-layout>
