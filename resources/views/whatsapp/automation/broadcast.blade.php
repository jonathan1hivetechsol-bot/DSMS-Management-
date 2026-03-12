<!-- Broadcast Message View -->
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">📢 Broadcast Message</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('whatsapp.automation.broadcast.send') }}">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Message Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">👥 Select Group</label>
                            <select name="group_id" class="form-select form-select-lg" required>
                                <option value="">-- Select a group --</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->name }} ({{ $group->member_count }} members)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Need to create a group? 
                                <a href="{{ route('whatsapp.automation.group.create') }}">Click here</a>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📋 Template (Optional)</label>
                            <select name="template_id" class="form-select" onchange="loadTemplate(this.value)">
                                <option value="">-- No template --</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">💬 Message Text</label>
                            <textarea name="message" class="form-control" rows="6" required placeholder="Write your message here..."></textarea>
                            <small class="text-muted d-block mt-2">
                                <strong>Available variables:</strong> {name}, {student_name}, {date}, {amount}, {marks}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">📊 Summary</h5>
                        <div class="mb-2">
                            <small class="text-muted">Selected Group:</small>
                            <p class="mb-0" id="groupSummary">None</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Total Members:</small>
                            <p class="mb-0" id="memberSummary">0</p>
                        </div>
                        <hr>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="confirm" id="confirmCheck" required>
                            <label class="form-check-label" for="confirmCheck">
                                I confirm to send this message to all members
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-send"></i> Send Message
                </button>
                <a href="{{ route('whatsapp.automation.dashboard') }}" class="btn btn-secondary btn-lg w-100 mt-2">
                    Back
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.querySelector('select[name="group_id"]')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const memberCount = selectedOption.text.match(/\((\d+)\s/)?.[1] || 0;
    
    document.getElementById('groupSummary').textContent = selectedOption.text || 'None';
    document.getElementById('memberSummary').textContent = memberCount;
});

function loadTemplate(templateId) {
    if (!templateId) return;
    
    // In a real scenario, you'd fetch the template via AJAX
    // For now, this is a placeholder
}
</script>
