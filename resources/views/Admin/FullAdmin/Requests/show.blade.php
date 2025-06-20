@extends('Components.layout')

@section('content')
    <x-breadcrumb :items="[
        ['text' => 'Admin', 'link' => '/welcome'],
        ['text' => 'Teacher Requests', 'link' => '/teacher-requests'],
        ['text' => 'Request Details', 'link' => '/teacher-requests/' . $request->id]
    ]" />

    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Request Details</h4>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="card-title">Request Information</h5>
                        <p><strong>Request ID:</strong> {{ $request->id }}</p>
                        <p>
                            <strong>Action Type:</strong>
                            <span class="badge {{ $request->action_type === 'add' ? 'bg-success' : ($request->action_type === 'edit' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($request->action_type) }}
                            </span>
                        </p>
                        <p><strong>Target Type:</strong> {{ ucfirst($request->target_type) }}</p>
                        @if ($request->action_type !== 'add')
                            <p><strong>Target ID:</strong> {{ $request->target_id }}</p>
                        @endif
                        <p><strong>Created:</strong> {{ $request->created_at->format('F j, Y, g:i a') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title">Teacher Information</h5>
                        <p><strong>Name:</strong> {{ $request->teacher->name }}</p>
                        <p><strong>Username:</strong> {{ $request->teacher->userName }}</p>
                        <p><strong>Contact:</strong> {{ $request->teacher->countryCode }} {{ $request->teacher->number }}</p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Request Payload</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($request->payload, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('teacher-requests.index') }}" class="btn btn-secondary">Back to List</a>
                    <div>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal">
                            Decline
                        </button>
                        <form action="{{ route('teacher-requests.approve', $request->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-success ms-2">Approve</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('teacher-requests.decline', $request->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="declineModalLabel">Decline Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for declining</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                            <small class="text-muted">This will be shared with the teacher.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Decline</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 