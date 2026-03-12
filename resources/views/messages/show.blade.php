@extends('layouts.vertical', ['title' => 'View Message', 'subTitle' => $message->subject])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">{{ $message->subject }}</h4>
                    </div>
                    <div>
                        <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="border-bottom pb-3 mb-3">
                    @if(auth()->id() === $message->sender_id)
                        <p class="mb-1">
                            <strong>To:</strong> <span class="badge badge-primary">{{ $message->receiver->name }}</span>
                        </p>
                    @else
                        <p class="mb-1">
                            <strong>From:</strong> <span class="badge badge-primary">{{ $message->sender->name }}</span>
                        </p>
                    @endif
                    <p class="mb-0">
                        <small class="text-muted">{{ $message->created_at->format('M d, Y \a\t h:i A') }}</small>
                    </p>
                </div>

                <div class="message-body" style="line-height: 1.6;">
                    {{ nl2br(e($message->body)) }}
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Reply</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ auth()->id() === $message->sender_id ? $message->receiver_id : $message->sender_id }}">
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" value="Re: {{ $message->subject }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Message</label>
                        <textarea name="body" id="body" class="form-control" rows="6" placeholder="Type your reply..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Message Details</h5>
            </div>
            <div class="card-body">
                @if(auth()->id() === $message->sender_id)
                    <div class="mb-3">
                        <label class="form-label">Recipient</label>
                        <p class="mb-0">{{ $message->receiver->name }}</p>
                    </div>
                @else
                    <div class="mb-3">
                        <label class="form-label">From</label>
                        <p class="mb-0">{{ $message->sender->name }}</p>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <p class="mb-0">{{ $message->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <p class="mb-0">
                        @if($message->read_at)
                            <span class="badge badge-success">Read</span> on {{ $message->read_at->format('M d, Y h:i A') }}
                        @else
                            <span class="badge badge-warning">Unread</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
