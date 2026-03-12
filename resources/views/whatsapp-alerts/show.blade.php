@extends('layouts.vertical', ['title' => 'Alert Details', 'subTitle' => 'WhatsApp Alert Information'])

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Alert #{{ $alert->id }} Details</h5>
                @if($alert->isFailed())
                    <form action="{{ route('whatsapp.retry', $alert) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning">
                            <i class="bx bx-refresh"></i> Retry
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                <!-- Status Badge -->
                <div class="mb-4">
                    <span class="badge @if($alert->status === 'sent') bg-success @elseif($alert->status === 'failed') bg-danger @elseif($alert->status === 'delivered') bg-info @else bg-warning @endif" style="font-size: 14px;">
                        {{ ucfirst($alert->status) }}
                    </span>
                </div>

                <!-- Alert Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Recipient Phone</h6>
                        <p class="fw-bold">{{ $alert->recipient_phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Provider</h6>
                        <p class="fw-bold">{{ ucfirst($alert->provider) }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Template</h6>
                        <p class="fw-bold">{{ $alert->template?->name ?? 'Custom Message' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Category</h6>
                        <p class="fw-bold">{{ ucfirst($alert->template?->category ?? 'General') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Created At</h6>
                        <p class="fw-bold">{{ $alert->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Sent At</h6>
                        <p class="fw-bold">
                            @if($alert->sent_at)
                                {{ $alert->sent_at->format('M d, Y H:i:s') }}
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Delivered At</h6>
                        <p class="fw-bold">
                            @if($alert->delivered_at)
                                {{ $alert->delivered_at->format('M d, Y H:i:s') }}
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Provider Message ID</h6>
                        <p class="fw-bold text-break" style="word-break: break-all;">
                            {{ $alert->provider_message_id ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Message Content</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0 text-break">{{ $alert->message }}</p>
                    </div>
                </div>

                <!-- Retry Count and Error -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Retry Count</h6>
                        <p class="fw-bold">{{ $alert->retry_count }}</p>
                    </div>
                    <div class="col-md-6">
                        @if($alert->error_message)
                            <h6 class="text-muted">Error Message</h6>
                            <p class="fw-bold text-danger text-break">{{ $alert->error_message }}</p>
                        @endif
                    </div>
                </div>

                <!-- Data/Variables -->
                @if($alert->data)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Message Data</h6>
                        <div class="bg-light p-3 rounded">
                            <pre style="margin: 0; font-size: 12px;">{{ json_encode($alert->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <a href="{{ route('whatsapp.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Alerts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
