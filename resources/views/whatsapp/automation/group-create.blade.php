<!-- Create Group View -->
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="mb-4">Create New Group</h2>

            <form method="POST" action="{{ route('whatsapp.automation.group.store') }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <!-- Group Name -->
                        <div class="mb-3">
                            <label class="form-label">Group Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Class 1 Students" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Group description"></textarea>
                        </div>

                        <!-- Group Type -->
                        <div class="mb-3">
                            <label class="form-label">Group Type</label>
                            <select name="type" class="form-select" onchange="updateGroupType(this.value)" required>
                                <option value="">-- Select --</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Class Filter (for students) -->
                        <div class="mb-3" id="classFilterDiv" style="display:none;">
                            <label class="form-label">Select Class (Optional)</label>
                            <select name="class_id" class="form-select">
                                <option value="">-- All Classes --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Custom Phone Numbers (for custom type) -->
                        <div class="mb-3" id="phoneNumbersDiv" style="display:none;">
                            <label class="form-label">Phone Numbers (One per line)</label>
                            <textarea name="phone_numbers" class="form-control" rows="5" placeholder="+923001234567&#10;+923009876543"></textarea>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info" id="infoBox">
                            <small>Please select a group type</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Create Group
                    </button>
                    <a href="{{ route('whatsapp.automation.groups') }}" class="btn btn-secondary btn-lg">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateGroupType(type) {
    document.getElementById('classFilterDiv').style.display = 'none';
    document.getElementById('phoneNumbersDiv').style.display = 'none';
    
    const infoBox = document.getElementById('infoBox');
    
    switch(type) {
        case 'students':
            document.getElementById('classFilterDiv').style.display = 'block';
            infoBox.innerHTML = '<small>✓ All students will be included</small>';
            break;
        case 'teachers':
            infoBox.innerHTML = '<small>✓ All teachers will be included</small>';
            break;
        case 'parents':
            infoBox.innerHTML = '<small>✓ All parents will be included</small>';
            break;
        case 'guardians':
            infoBox.innerHTML = '<small>✓ All guardians will be included</small>';
            break;
        case 'custom':
            document.getElementById('phoneNumbersDiv').style.display = 'block';
            infoBox.innerHTML = '<small>✓ Only entered phone numbers will be included</small>';
            break;
    }
}
</script>
