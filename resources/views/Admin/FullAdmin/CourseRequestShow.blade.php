@props(['courseRequest' => App\Models\CourseRequest::findOrFail(session('courseRequest'))])

<style>
    .request-action-bar {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
    }

    .request-action-bar form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .request-action-bar .btn {
        font-weight: 600;
        border-radius: 6px;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 1rem 2rem;
        cursor: pointer;
    }

    .request-action-bar .btn-success {
        background: linear-gradient(90deg, #28a745 60%, #218838 100%);
        color: #fff;
        border: none;
    }

    .request-action-bar .btn-success:hover {
        background: linear-gradient(90deg, #218838 60%, #28a745 100%);
        color: #fff;
    }

    .request-action-bar .btn-danger {
        background: linear-gradient(90deg, #dc3545 60%, #c82333 100%);
        color: #fff;
        border: none;
    }

    .request-action-bar .btn-danger:hover {
        background: linear-gradient(90deg, #c82333 60%, #dc3545 100%);
        color: #fff;
    }

    .request-action-bar .form-control {
        min-width: 200px;
        max-width: 300px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: border-color 0.2s, box-shadow 0.2s;
        padding: 2.5rem 1.5rem;
        text-wrap-mode: ;
    }

    .request-action-bar .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    .request-action-bar .auto-resize {
        min-width: 200px;
        max-width: 300px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: border-color 0.2s, box-shadow 0.2s;
        padding: 0.5rem 1.5rem;
        resize: none;
        /* Prevent manual resizing if you want only auto */
        overflow: hidden;
    }
</style>

<x-layout object="{{ __('messages.courseRequests') }}" :nav=false>
    <x-infocard :object="$courseRequest" image="{{ $request->image ? asset($request->image) : asset('Images/Courses/default.png') }}" objectType="CourseRequest"
        name="{{ $courseRequest->name }}" :request=true>
        <h2 style="margin-bottom: 1rem;">{{ __('messages.courseRequestDetails') }}</h2>
        <p><strong>{{ __('messages.courseRequestName') }}:</strong> {{ $request->name }}</p>
        <p><strong>{{ __('messages.courseRequestDescription') }}:</strong> {{ $request->description }}</p>
        <p><strong>{{ __('messages.courseRequestSubject') }}:</strong> {{ $request->subject->name ?? 'N/A' }}
            ({{ $request->subject->literaryOrScientific ? 'Scientific' : 'Literary' }})</p>
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
        @if ($request->status === 'pending')
            <div class="request-action-bar">
                <form action="{{ route('admin.course_requests.approve', $request->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success px-4">
                        {{ __('messages.approve') }}
                    </button>
                </form>
                <form action="{{ route('admin.course_requests.reject', $request->id) }}" method="POST"
                    class="d-flex align-items-center gap-2">
                    @csrf
                    <textarea name="rejection_reason" placeholder="{{ __('messages.rejectionReason') }}" class="form-control auto-resize"
                        rows="1" style="min-width: 200px; max-width: 300px;"></textarea>
                    <button type="submit" class="btn btn-danger px-4">
                        {{ __('messages.reject') }}
                    </button>
                </form>
            </div>
        @endif
    </x-infocard>
</x-layout>
<script>
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('auto-resize')) {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight) + 'px';
        }
    });
</script>
