@extends('layouts.app')

@section('content')
    <div class="card"> 

        <div class="card-body">
            <div class="card-body-title">
                <h5 class="">
                {{ $page_type == 'edit' ? 'Edit User' : 'Add New User' }}
                </h5>
            </div>

            <form method="POST" action="{{ $page_type == 'edit' ? route('users.update', $user->id) : route('users.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if ($page_type == 'edit')
                    @method('PUT')
                @endif
                <div class="form-content" style="max-width: 650px; margin:auto">
                    <!-- Name -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="name" class="form-control" required
                                value="{{ old('name', $page_type == 'edit' ? $user->name : '') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="email" name="email" class="form-control" autocomplete="off"
                                value="{{ old('email', $page_type == 'edit' ? $user->email : '') }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="phone" class="form-control" required
                                value="{{ old('phone', $page_type == 'edit' ? $user->phone : '') }}">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                        </div>
                        <div class="col-md-8">
                            <input type="date" name="dob" class="form-control"
                                value="{{ old('dob', $page_type == 'edit' ? $user->dob : '') }}">
                            @error('dob')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                        </div>
                        <div class="col-md-8">
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                @foreach (['Male', 'Female', 'Others'] as $gender)
                                    <option value="{{ $gender }}"
                                        {{ old('gender', $page_type == 'edit' ? $user->gender : '') == $gender ? 'selected' : '' }}>
                                        {{ $gender }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gender')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Profession -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Profession</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="profession" class="form-control"
                                value="{{ old('profession', $page_type == 'edit' ? $user->profession : '') }}">
                            @error('profession')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Address <span class="text-danger"></span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address" class="form-control" 
                                value="{{ old('address', $page_type == 'edit' ? $user->address : '') }}">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <select name="status" class="form-control" required>
                                @foreach (['Active', 'Inactive'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status', $page_type == 'edit' ? $user->status : 'Active') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Profile Image -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Profile Image</label>
                        </div>
                        <div class="col-md-8">
                            <input type="file" name="profile_image" class="form-control">
                            @if ($page_type == 'edit' && $user->profile_image)
                                <img src="{{ asset($user->profile_image) }}" class="mt-2 rounded" width="80">
                            @endif
                            @error('profile_image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">
                                Password {{ $page_type == 'edit' ? '(Leave blank to keep old)' : '' }}
                                @if ($page_type == 'create')
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                        </div>
                        <div class="col-md-8">
                            <input type="password" name="password" autocomplete="new-password" class="form-control"
                                {{ $page_type == 'create' ? 'required' : '' }}>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-8 d-flex gap-3">
                            <button type="submit" class="tc-primary-btn">
                                {{ $page_type == 'edit' ? 'Update User' : 'Save User' }}
                            </button>

                            <a href="{{ route('users.index') }}" class="tc-cancel-btn"><i class="ri-arrow-left-line"></i>Back</a>
                        </div>
                    </div>
                </div>


            </form>
        </div>
    </div>
@endsection
