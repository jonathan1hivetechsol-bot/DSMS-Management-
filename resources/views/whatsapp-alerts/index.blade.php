@extends('layouts.vertical', ['title' => 'WhatsApp Alerts', 'subTitle' => 'All Alerts'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Filter Alerts</h5>
                <a href="{{ route('whatsapp.send') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus"></i> Send Alert
                </a>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('whatsapp.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">WhatsApp Alerts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Phone Number</th>
                                <th>Template</th>
                                <th>Status</th>
                                <th>Provider</th>
                                <th>Sent At</th>
                                <th>Retry Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alerts as $alert)
                                <tr>
                                    <td><strong>{{ $alert->recipient_phone }}</strong></td>
                                    <td>{{ $alert->template?->name ?? 'Custom' }}</td>
                                    <td>
                                        <span class="badge @if($alert->status === 'sent') bg-success @elseif($alert->status === 'failed') bg-danger @elseif($alert->status === 'delivered') bg-info @else bg-warning @endif">
                                            {{ ucfirst($alert->status) }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ ucfirst($alert->provider) }}</small></td>
                                    <td>{{ $alert->sent_at?->format('M d, Y H:i') ?? '-' }}</td>
                                    <td>{{ $alert->retry_count }}</td>
                                    <td>
                                        <a href="{{ route('whatsapp.show', $alert) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bx bx-eye"></i>
                                        </a>
                                        @if($alert->isFailed())
                                            <form action="{{ route('whatsapp.retry', $alert) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Retry">
                                                    <i class="bx bx-refresh"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No alerts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $alerts->firstItem() ?? 0 }} to {{ $alerts->lastItem() ?? 0 }} of {{ $alerts->total() }}
                    </div>
                    {{ $alerts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
