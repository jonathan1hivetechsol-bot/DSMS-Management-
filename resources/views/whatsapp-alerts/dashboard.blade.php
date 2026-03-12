@extends('layouts.vertical', ['title' => 'WhatsApp Alerts', 'subTitle' => 'Dashboard'])

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">Total Sent Today</h5>
                <h2 class="text-primary fw-bold">{{ $stats['today_sent'] }}</h2>
                <small class="text-success">Out of {{ $stats['total_sent'] }} total</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">Pending</h5>
                <h2 class="text-warning fw-bold">{{ $stats['total_pending'] }}</h2>
                <small class="text-muted">Waiting to send</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">Failed</h5>
                <h2 class="text-danger fw-bold">{{ $stats['total_failed'] }}</h2>
                <small class="text-danger">Need attention</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">Active Recipients</h5>
                <h2 class="text-info fw-bold">{{ $stats['opted_in'] }}</h2>
                <small class="text-muted">Out of {{ $stats['total_recipients'] }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="btn-group" role="group">
            <a href="{{ route('whatsapp.send') }}" class="btn btn-primary">
                <i class="bx bx-send"></i> Send Alert
            </a>
            <a href="{{ route('whatsapp.templates') }}" class="btn btn-info">
                <i class="bx bx-file"></i> Manage Templates
            </a>
            <a href="{{ route('whatsapp.recipients') }}" class="btn btn-secondary">
                <i class="bx bx-user"></i> Manage Recipients
            </a>
        </div>
    </div>
</div>

<!-- Recent Alerts Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Recent Alerts</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Phone</th>
                                <th>Template</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Provider</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAlerts as $alert)
                                <tr>
                                    <td>{{ $alert->recipient_phone }}</td>
                                    <td>{{ $alert->template?->name ?? 'Custom' }}</td>
                                    <td>
                                        <span class="badge @if($alert->status === 'sent') bg-success @elseif($alert->status === 'failed') bg-danger @else bg-warning @endif">
                                            {{ ucfirst($alert->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $alert->sent_at?->format('M d, H:i') ?? '-' }}</td>
                                    <td><small class="text-muted">{{ $alert->provider }}</small></td>
                                    <td>
                                        <a href="{{ route('whatsapp.show', $alert) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No alerts sent yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
