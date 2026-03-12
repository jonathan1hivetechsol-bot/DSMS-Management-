<!-- Automation Dashboard -->
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-4">🤖 WhatsApp Automation Dashboard</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('whatsapp.automation.broadcast') }}" class="btn btn-success btn-lg">
                <i class="bi bi-broadcast"></i> Broadcast Message
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-muted">Sent Today</h5>
                    <h2 class="text-primary">{{ $stats['today_sent'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-muted">Pending</h5>
                    <h2 class="text-warning">{{ $stats['pending'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-muted">Delivered</h5>
                    <h2 class="text-success">{{ $stats['delivered'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-muted">Failed</h5>
                    <h2 class="text-danger">{{ $stats['failed'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">⚡ Quick Send</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#quickSendModal">
                                <i class="bi bi-phone"></i> Send to Number
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#sendStudentsModal">
                                <i class="bi bi-people"></i> Send to Students
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#sendTeachersModal">
                                <i class="bi bi-person-badge"></i> Send to Teachers
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Alerts -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📨 Recent Messages</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAlerts as $alert)
                                <tr>
                                    <td>{{ $alert->recipient_phone }}</td>
                                    <td>{{ Str::limit($alert->message, 50) }}</td>
                                    <td>
                                        @if($alert->status === 'sent')
                                            <span class="badge bg-success">✓ Sent</span>
                                        @elseif($alert->status === 'delivered')
                                            <span class="badge bg-info">✓✓ Delivered</span>
                                        @elseif($alert->status === 'failed')
                                            <span class="badge bg-danger">✗ Failed</span>
                                        @else
                                            <span class="badge bg-warning">⏳ Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $alert->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No messages</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Groups Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">👥 Groups</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($groups as $group)
                        <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $group->name }}</h6>
                                <small class="text-muted">{{ $group->type }}</small>
                            </div>
                            <span class="badge bg-secondary">{{ $group->member_count }}</span>
                        </a>
                    @empty
                        <div class="p-3 text-center text-muted">
                            <p>No groups created</p>
                            <a href="{{ route('whatsapp.automation.group.create') }}" class="btn btn-sm btn-primary">
                                Create Group
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Send Modal -->
<div class="modal fade" id="quickSendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message to Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickSendForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" placeholder="+923001234567" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('quickSendForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const response = await fetch('{{ route("whatsapp.automation.quick-send") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: formData
    });
    
    const data = await response.json();
    alert(data.message);
    
    if (data.success) {
        document.getElementById('quickSendForm').reset();
        // Modal ko properly band karo
        const modal = document.getElementById('quickSendModal');
        if (modal) {
            // Check if Bootstrap is available
            if (typeof bootstrap !== 'undefined') {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            } else {
                // Fallback: manually hide modal
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            }
        }
        location.reload();
    }
});
</script>
