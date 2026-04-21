@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5>Add new permission</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('permissions.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input value="{{ old('name') }}"
                           type="text"
                           class="form-control"
                           name="name"
                           placeholder="Name" required>

                    @if ($errors->has('name'))
                        <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label cursor-pointer">Stay this page after submission
                        <input type="checkbox" name="stay" value="on" checked></label>
                </div>

                <button type="submit" class="btn btn-outline-info">Save permission</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-outline-success text-dark">Back</a>
            </form>
        </div>
    </div>
@endsection
