@extends('layouts.vertical', ['title' => 'Announcements', 'subTitle' => 'Manage'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Announcements</h4>
                <a href="{{ route('announcements.create') }}" class="btn btn-primary">New Announcement</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Important</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $announcement)
                                <tr>
                                    <td>{{ $announcement->title }}</td>
                                    <td>{{ $announcement->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $announcement->important ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this announcement?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No announcements yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection