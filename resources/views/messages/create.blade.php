@extends('layouts.vertical', ['title' => 'New Message', 'subTitle' => 'Send a Message'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Compose Message</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="receiver_id" class="form-label">Recipient <span class="text-danger">*</span></label>
                        <select name="receiver_id" id="receiver_id" class="form-select @error('receiver_id') is-invalid @enderror" required>
                            <option value="">-- Select Recipient --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('receiver_id')==$user->id?'selected':'' }}>
                                    {{ $user->name }}
                                    @if($user->teacher)
                                        (Teacher)
                                    @elseif($user->student)
                                        (Student - {{ $user->student->schoolClass->name ?? 'No Class' }})
                                    @else
                                        (Admin)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('receiver_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Enter message subject" maxlength="255" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="body" id="body" class="form-control @error('body') is-invalid @enderror" rows="10" placeholder="Type your message here..." required>{{ old('body') }}</textarea>
                        <small class="form-text text-muted">Minimum 10 characters required.</small>
                        @error('body')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Message Tips</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0 ps-3">
                    <li>Be clear and concise with your message</li>
                    <li>Use proper grammar and spelling</li>
                    <li>Avoid using all caps (it seems like yelling)</li>
                    <li>Be respectful in your communication</li>
                    <li>Keep messages professional</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Links</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <a href="{{ route('messages.inbox') }}" class="text-decoration-none">View Inbox</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('messages.index') }}" class="text-decoration-none">All Messages</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
