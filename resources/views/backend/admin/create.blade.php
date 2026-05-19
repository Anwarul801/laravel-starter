@extends('layouts.app')

@section('content')
@php
    $isEdit       = ($page_type ?? 'create') === 'edit';
    $action       = $isEdit ? route('admin.update', $user->id) : route('admin.store');
    $userRole     = $userRole ?? [];
    $currentRole  = $isEdit ? ($user->role_id ?? null) : null;
@endphp

<div class="card">
    <div class="card-body">
        <div class="card-body-title">
            <h5>{{ $isEdit ? 'Update Admin' : 'Add new Admin' }}</h5>
        </div>

        <div class="form-content" style="max-width: 650px; margin:auto">
            @if(!$isEdit)
                <h5 class="card-title mb-4 text-center">Add new Admin and assign role.</h5>
            @endif

            <form method="POST" action="{{ $action }}" autocomplete="off">
                @csrf
                @if($isEdit) @method('PUT') @endif

                {{-- Name --}}
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $isEdit ? $user->name : '') }}"
                               placeholder="Name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $isEdit ? $user->email : '') }}"
                               placeholder="Email address" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Phone --}}
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label" for="phone">Phone <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="phone" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $isEdit ? $user->phone : '') }}"
                               placeholder="Phone" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label" for="password">
                            Password {{ $isEdit ? '(Leave blank to keep current)' : '' }}
                            @if(!$isEdit) <span class="text-danger">*</span> @endif
                        </label>
                    </div>
                    <div class="col-md-8">
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password" placeholder="Password"
                               {{ $isEdit ? '' : 'required' }}>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <select id="role" name="role"
                                class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">Select role</option>
                            @foreach($roles as $role)
                                @php
                                    $selected = $isEdit
                                        ? in_array($role->name, $userRole)
                                        : (old('role') == $role->id);
                                @endphp
                                <option value="{{ $role->id }}" {{ $selected ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>


                {{-- Status --}}
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label" for="adminStatus">Status <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <select id="adminStatus" name="status"
                                class="form-control @error('status') is-invalid @enderror" required>
                            @foreach(['Active', 'Inactive'] as $status)
                                <option value="{{ $status }}"
                                    {{ old('status', $isEdit ? ($user->status ?? 'Active') : 'Active') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="row justify-content-end mt-4">
                    <div class="col-md-8 d-flex gap-3">
                        <button type="submit" class="tc-primary-btn">
                            {{ $isEdit ? 'Update Admin' : 'Save Admin' }}
                        </button>
                        <a href="{{ route('admin.index') }}" class="tc-cancel-btn">
                            <i class="ri-arrow-left-line"></i> {{ $isEdit ? 'Cancel' : 'Back' }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
