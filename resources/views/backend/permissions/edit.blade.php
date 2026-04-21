@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-body-title">
                <h5>Edit permission</h5>
            </div>
            <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                @method('patch')
                @csrf
                <div class="form-content" style="max-width: 650px; margin:auto">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="name" class="form-control" required
                                value="{{ $permission->name }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> 

                    <div class="row justify-content-end mt-4">
                        <div class="col-md-8 d-flex gap-3">
                        <label class="form-label cursor-pointer">Stay this page after submission
                            <input type="checkbox" name="stay" value="on" checked></label>
                    </div> 
                    </div> 
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-8 d-flex gap-3">
                            <button type="submit" class="tc-primary-btn">
                                 Save permission
                            </button>

                            <a href="{{ route('permissions.index') }}" class="tc-cancel-btn"><i class="ri-arrow-left-line"></i>Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
