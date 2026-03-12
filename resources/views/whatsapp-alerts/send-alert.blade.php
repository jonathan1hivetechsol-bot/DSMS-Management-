@extends('layouts.vertical', ['title' => 'Send WhatsApp Alert', 'subTitle' => 'Send Alert to Recipients'])

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Send WhatsApp Alert</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('whatsapp.send.action') }}" method="POST">
                    @csrf

                    <div class="alert alert-info">
                        <strong>Note:</strong> Choose either a predefined recipient or enter a custom phone number.
                    </div>

                    <!-- Option 1: Select from Recipients -->
                    <div class="mb-3">
                        <label class="form-label">Select Recipient (Optional)</label>
                        <select name="recipient_id" class="form-select" id="recipientSelect">
                            <option value="">-- Choose a recipient --</option>
                            @foreach($recipients as $recipient)
                                <option value="{{ $recipient->id }}" data-phone="{{ $recipient->phone_number }}">
                                    {{ $recipient->name }} ({{ $recipient->phone_number }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Selecting a recipient will auto-fill their phone number.</small>
                    </div>

                    <!-- Option 2: Custom Phone Number -->
                    <div class="mb-3">
                        <label class="form-label">Phone Number (Custom)</label>
                        <input type="text" name="phone_number" class="form-control" id="phoneInput"
                               placeholder="e.g., +1234567890" value="{{ old('phone_number') }}">
                        <small class="text-muted">Leave empty to use selected recipient's number.</small>
                    </div>

                    <!-- Template Selection -->
                    <div class="mb-3">
                        <label class="form-label">Select Template (Optional)</label>
                        <select name="template_id" class="form-select" id="templateSelect">
                            <option value="">-- Choose a template --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" data-message="{{ $template->template }}">
                                    {{ $template->name }} ({{ $template->category }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Message Content -->
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="custom_message" class="form-control @error('custom_message') is-invalid @enderror" 
                                  id="messageInput" rows="6" placeholder="Enter your message or select a template above" 
                                  required>{{ old('custom_message') }}</textarea>
                        @error('custom_message')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <!-- Character Count -->
                    <div class="mb-3">
                        <small class="text-muted">
                            Message length: <span id="charCount">0</span> characters
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-send"></i> Send Alert
                        </button>
                        <a href="{{ route('whatsapp.index') }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recipientSelect = document.getElementById('recipientSelect');
    const phoneInput = document.getElementById('phoneInput');
    const templateSelect = document.getElementById('templateSelect');
    const messageInput = document.getElementById('messageInput');
    const charCount = document.getElementById('charCount');

    // Update phone when recipient is selected
    recipientSelect.addEventListener('change', function() {
        if (this.value) {
            const phone = this.options[this.selectedIndex].dataset.phone;
            phoneInput.value = phone;
        }
    });

    // Update message when template is selected
    templateSelect.addEventListener('change', function() {
        if (this.value) {
            const message = this.options[this.selectedIndex].dataset.message;
            messageInput.value = message;
            updateCharCount();
        }
    });

    // Update character count
    messageInput.addEventListener('input', updateCharCount);

    function updateCharCount() {
        charCount.textContent = messageInput.value.length;
    }
});
</script>
@endsection
