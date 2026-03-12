<!-- Groups Management View -->
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-4">👥 WhatsApp Groups</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('whatsapp.automation.group.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> New Group
            </a>
        </div>
    </div>

    @if($groups->isEmpty())
        <div class="alert alert-info">
            <h5>No groups created</h5>
            <p><a href="{{ route('whatsapp.automation.group.create') }}">Click here to create your first group</a></p>
        </div>
    @else
        <div class="row">
            @foreach($groups as $group)
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title">{{ $group->name }}</h5>
                                    <p class="card-text text-muted">{{ $group->description }}</p>
                                    <small class="badge bg-secondary">
                                        @switch($group->type)
                                            @case('students') Students @break
                                            @case('teachers') Teachers @break
                                            @case('parents') Parents @break
                                            @case('guardians') Guardians @break
                                            @default {{ $group->type }} @endswitch
                                    </small>
                                </div>
                                <div>
                                    @if($group->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    <strong>{{ $group->member_count }}</strong> members
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-success" onclick="quickSendToGroup({{ $group->id }})">
                                        <i class="bi bi-send"></i> Send
                                    </button>
                                    <form action="{{ route('whatsapp.automation.group.delete', $group) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function quickSendToGroup(groupId) {
    // This would open a modal to send message to this group
    alert('Coming soon: Send message to group ' + groupId);
}
</script>
