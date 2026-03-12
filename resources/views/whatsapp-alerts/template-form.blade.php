@extends('layouts.vertical', ['title' => isset($template) ? 'Edit Template' : 'Create Template', 'subTitle' => 'WhatsApp Alert Template'])

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    @if(isset($template))
                        Edit Template: {{ $template->name }}
                    @else
                        Create New WhatsApp Alert Template
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <form action="@if(isset($template)) {{ route('whatsapp.template.update', $template) }} @else {{ route('whatsapp.template.store') }} @endif" method="POST">
                    @csrf
                    @if(isset($template))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Template Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $template->name ?? '') }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" @selected(old('category', $template->category ?? '') === $key)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Template Message *</label>
                        <textarea name="template" class="form-control @error('template') is-invalid @enderror" 
                                  rows="6" placeholder="Enter your message. Use {variable_name} for dynamic content." required>{{ old('template', $template->template ?? '') }}</textarea>
                        <small class="text-muted d-block mt-2">
                            <strong>Example:</strong> "Hello {student_name}, your attendance for {date} has been marked as {status}."
                        </small>
                        @error('template')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Variables (Comma-separated)</label>
                        <input type="text" name="variables" class="form-control @error('variables') is-invalid @enderror" 
                               placeholder="e.g., student_name, date, status"
                               value="{{ old('variables', $template->variables ? implode(', ', $template->variables) : '') }}">
                        <small class="text-muted d-block">Optional. List the variables used in your template for reference.</small>
                        @error('variables')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Brief description of when this template is used.">{{ old('description', $template->description ?? '') }}</textarea>
                        @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                   @checked(old('is_active', $template->is_active ?? true))>
                            <label class="form-check-label" for="is_active">
                                Active (Available for sending)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> @if(isset($template)) Update @else Create @endif Template
                        </button>
                        <a href="{{ route('whatsapp.templates') }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
