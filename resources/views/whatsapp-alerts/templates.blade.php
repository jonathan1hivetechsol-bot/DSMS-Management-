@extends('layouts.vertical', ['title' => 'WhatsApp Templates', 'subTitle' => 'Manage Alert Templates'])

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="text-primary">Alert Templates</h4>
        <a href="{{ route('whatsapp.template.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Create Template
        </a>
    </div>
</div>

@if($templates->isEmpty())
    <div class="alert alert-info">
        <strong>No templates yet!</strong> Create your first template to start sending alerts.
    </div>
@endif

<div class="row">
    @foreach($templates as $template)
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ $template->name }}</h5>
                        <small class="text-muted">{{ ucfirst($template->category) }}</small>
                    </div>
                    <span class="badge @if($template->is_active) bg-success @else bg-secondary @endif">
                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ Str::limit($template->description, 100) }}</p>
                    <div class="bg-light p-3 rounded mb-3">
                        <small class="text-muted d-block mb-2">Template:</small>
                        <p class="mb-0 text-muted" style="font-size: 12px; word-break: break-all;">
                            {{ Str::limit($template->template, 150) }}
                        </p>
                    </div>
                    @if($template->variables)
                        <small class="text-muted d-block mb-2">
                            <strong>Variables:</strong> {{ implode(', ', $template->variables) }}
                        </small>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('whatsapp.template.edit', $template) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                    <form action="{{ route('whatsapp.template.destroy', $template) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                            <i class="bx bx-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Pagination -->
@if($templates->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $templates->links('pagination::bootstrap-4') }}
    </div>
@endif
@endsection
