@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-body-title">
                <h5>Show user</h5>
            </div>
            <div class="form-content" style="max-width: 650px; margin:auto">
                <div>
                    Name: {{ $user->name }}
                </div>
                <div>
                    Email: {{ $user->email }}
                </div>
                <div>
                    Phone: {{ $user->phone }}
                </div>
                <div class="mt-4 d-flex gap-3 justify-content-center">
                    <a href="{{ route('users.edit', $user->id) }}" class="tc-primary-btn">Edit</a>
                    <a href="{{ route('users.index') }}" class="tc-cancel-btn"><i class="ri-arrow-left-line"></i>Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
