@extends('layouts.vertical', ['title' => 'Add WhatsApp Recipient', 'subTitle' => 'Add New Recipient'])

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Add WhatsApp Recipient</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('whatsapp.recipient.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Enter full name" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number *</label>
                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                               value="{{ old('phone_number') }}" placeholder="e.g., +1234567890 or 1234567890" required>
                        <small class="text-muted">Include country code. e.g., +1 for US, +44 for UK</small>
                        @error('phone_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Recipient Type *</label>
                        <select name="recipient_type" class="form-select @error('recipient_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" @selected(old('recipient_type') === $type)>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('recipient_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="Optional email address">
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="opt_in" value="1" id="opt_in" 
                                   @checked(old('opt_in', true))>
                            <label class="form-check-label" for="opt_in">
                                Recipient has opted in to receive WhatsApp messages
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Add Recipient
                        </button>
                        <a href="{{ route('whatsapp.recipients') }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
