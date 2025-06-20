@extends('Components.layout')

@section('content')
    <x-breadcrumb :items="[['text' => 'Admin', 'link' => '/welcome'], ['text' => 'Teacher Requests', 'link' => '/teacher-requests']]" />

    <div class="container mt-4">
        <h1 class="mb-4">Pending Teacher Requests</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($requests->isEmpty())
            <div class="alert alert-info">
                No pending teacher requests found.
            </div>
        @else
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Teacher</th>
                        <th>Action</th>
                        <th>Target</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->teacher->name }}</td>
                            <td>
                                <span class="badge {{ $request->action_type === 'add' ? 'bg-success' : ($request->action_type === 'edit' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($request->action_type) }}
                                </span>
                            </td>
                            <td>
                                {{ ucfirst($request->target_type) }}
                                @if ($request->action_type !== 'add')
                                    #{{ $request->target_id }}
                                @endif
                            </td>
                            <td>{{ $request->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('teacher-requests.show', $request->id) }}" class="btn btn-sm btn-info">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
@endsection 