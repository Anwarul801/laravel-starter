@extends('layouts.app')
{{--
 @Author: Anwarul
 @Date: 2025-12-31 11:31:40
 @LastEditors: Anwarul
 @LastEditTime: 2026-01-28 12:53:33
 @Description: Innova IT
 --}}

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Details</h5>
                <span class="badge {{ $user->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                    {{ $user->status }}
                </span>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    Back
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Profile Image --}}
                    <div class="col-md-3 text-center">
                        <img src="{{ $user->profile_image ? asset($user->profile_image) : asset('demo_img/user.png') }}"
                            class="img-fluid rounded-circle mb-3" style="width: 140px; height: 140px; object-fit: cover;">
                    </div>
                    {{-- User Info --}}
                    <div class="col-md-9">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th width="180">Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $user->dob ? date('d M Y', strtotime($user->dob)) : '—' }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ $user->gender ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Profession</th>
                                <td>{{ $user->profession ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $user->address }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $user->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            @if($user->devices->count())
                                <a href="{{ route('users.show', [$user->id, 'delete_all_devices' => 1]) }}"
                                class="btn btn-sm btn-danger mb-2"
                                onclick="return confirm('Delete all devices?')">
                                    Delete All Devices
                                </a>
                            @endif
                            <table class="table table-bordered table-striped text-center" style="width:100%">
                                <thead>
                                    <tr>
                                        <td>Sl</td>
                                        <td>Device Name</td>
                                        <td>Platform</td>
                                        <td>Type</td>
                                        <td>IP</td>
                                        <td>Last Date Time</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->devices as $device)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $device->device_name }}</td>
                                            <td>{{ $device->platform }}</td>
                                            <td>{{ $device->device_type }}</td>
                                            <td>{{ $device->ip_address }}</td>
                                            <td>{{ $device->last_used_at }}</td>
                                            <td>  <a href="{{ route('users.show', [$user->id, 'delete_device' => $device->id]) }}"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No Device Connected</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    @endsection
