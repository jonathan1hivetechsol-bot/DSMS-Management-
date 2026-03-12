@extends('layouts.vertical', ['title' => 'Messages', 'subTitle' => 'Manage Messages'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Messages</h4>
                <div>
                    <a href="{{ route('messages.inbox') }}" class="btn btn-sm btn-outline-info me-2">Inbox</a>
                    <a href="{{ route('messages.create') }}" class="btn btn-primary">New Message</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if($messages->count() > 0)
                    <div class="list-group">
                        @foreach($messages as $message)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <a href="{{ route('messages.show', $message) }}" class="text-decoration-none">
                                            <h5 class="mb-1">
                                                @if(auth()->id() === $message->sender_id)
                                                    To: <strong>{{ $message->receiver->name }}</strong>
                                                @else
                                                    From: <strong>{{ $message->sender->name }}</strong>
                                                @endif
                            
                                                @if(!$message->read_at && auth()->id() === $message->receiver_id)
                                                    <span class="badge badge-success">New</span>
                                                @endif
                                            </h5>
                                            <p class="mb-1">
                                                <strong>{{ $message->subject }}</strong>
                                            </p>
                                            <small class="text-muted d-block">{{ Str::limit($message->body, 100) }}</small>
                                        </a>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                        <div class="mt-2">
                                            @if(!$message->read_at && auth()->id() === $message->receiver_id)
                                                <a href="{{ route('messages.markAsRead', $message) }}" class="btn btn-sm btn-outline-primary me-1">Mark Read</a>
                                            @endif
                                            <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this message?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $messages->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3">No Messages</h5>
                        <p class="text-muted">You don't have any messages yet.</p>
                        <a href="{{ route('messages.create') }}" class="btn btn-primary">Send a Message</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
