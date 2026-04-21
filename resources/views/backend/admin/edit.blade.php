@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-body-title">
                <h5>Update user</h5>
            </div>

            <div class="form-content" style="max-width: 650px; margin:auto">
                <form method="post" action="{{ route('admin.update', $user->id) }}">
                    @method('patch')
                    @csrf
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ $user->name }}" placeholder="Name" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ $user->email }}" placeholder="Email address" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="phone">Phone</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ $user->phone }}" placeholder="Phone">
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="password">Password</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" value="{{ old('password') }}" placeholder="Password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="role">Role</label>
                        </div>
                        <div class="col-md-8">
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                                <option value="">Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $role->id == $user->role_id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-8 d-flex gap-3">                             
                            <button type="submit" class="tc-primary-btn">Update user</button>
                            <a href="{{ route('admin.index') }}" class="tc-cancel-btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
