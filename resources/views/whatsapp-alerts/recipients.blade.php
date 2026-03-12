@extends('layouts.vertical', ['title' => 'WhatsApp Recipients', 'subTitle' => 'Manage Recipients'])

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="text-primary">WhatsApp Recipients</h4>
        <a href="{{ route('whatsapp.recipient.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add Recipient
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Opt-in</th>
                                <th>Verified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recipients as $recipient)
                                <tr>
                                    <td><strong>{{ $recipient->name }}</strong></td>
                                    <td>{{ $recipient->phone_number }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($recipient->recipient_type) }}</span>
                                    </td>
                                    <td>{{ $recipient->email ?? '-' }}</td>
                                    <td>
                                        <span class="badge @if($recipient->is_active) bg-success @else bg-danger @endif">
                                            {{ $recipient->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge @if($recipient->opt_in) bg-success @else bg-secondary @endif">
                                            {{ $recipient->opt_in ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($recipient->verified_at)
                                            <small class="text-success">{{ $recipient->verified_at->format('M d, Y') }}</small>
                                        @else
                                            <small class="text-muted">Pending</small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No recipients added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($recipients->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $recipients->firstItem() ?? 0 }} to {{ $recipients->lastItem() ?? 0 }} of {{ $recipients->total() }}
                        </div>
                        {{ $recipients->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
