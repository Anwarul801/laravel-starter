@extends('layouts.app')
{{--
 @Author: Anwarul
 @Date: 2026-01-24 12:44:52
 @LastEditors: Anwarul
 @LastEditTime: 2026-01-24 14:39:53
 @Description: Innova IT
 --}}

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-body-title">
                <h5 class="">Add new user</h5>
            </div>
            <div class="form-content" style="max-width: 650px; margin:auto">
                <h5 class="card-title mb-4 text-center">Add new user and assign role.</h5>
                <form method="POST" action="{{ route('admin.store') }}">
                    @csrf
                    <!-- Name -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="name">
                                Name <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input value="{{ old('name') }}" type="text" class="form-control" id="name"
                                name="name" placeholder="Name" required>
                            @if ($errors->has('name'))
                                <div class="text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="email">
                                Email <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input value="{{ old('email') }}" type="email" class="form-control" id="email"
                                name="email" placeholder="Email address" required>
                            @if ($errors->has('email'))
                                <div class="text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="phone">
                                Phone <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input value="{{ old('phone') }}" type="text" class="form-control" id="phone"
                                name="phone" placeholder="Phone" required>
                            @if ($errors->has('phone'))
                                <div class="text-danger">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="password">
                                Password <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                            @if ($errors->has('password'))
                                <div class="text-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label" for="role">
                                Role <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="col-md-8">
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role'))
                                <div class="text-danger">{{ $errors->first('role') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-8 d-flex gap-3">
                            <button type="submit" class="tc-primary-btn">Save user</button>
                            <a href="{{ route('admin.index') }}" class="tc-cancel-btn"><i class="ri-arrow-left-line"></i> Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
