@extends('layouts.app')
@section('page_title')
    {{ $page_type }} Page
@endsection
@section('content')
    <style>
        #datatable-buttons_info,
        #datatable-buttons_paginate,
        #datatable-buttons_filter {
            display: none;
        }
    </style>

    <div class="card">

        <div class="card-body form-body">
            <div class="card-body-title">
                <h5>{{ $page_type }} Page</h5>
            </div>
            <form action="{{ $page_type == 'Create' ? route('page.store') : route('page.update', $page->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @if ($page_type == 'Edit')
                    @method('PUT')
                @endif

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="row g-3">
                            <div class="col-item">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('Title') }}</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title', $page->title ?? '') }}" placeholder="Enter Title">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-item">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('Content') }}</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea name="content" id="elm1" cols="30" rows="10" class="form-control">{{ old('content', $page->content ?? '') }}</textarea>
                                        @error('content')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if ($page_type == 'Edit')
                                <div class="col-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('Status') }} <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="status" class="form-control">
                                                <option value="Active"
                                                    {{ old('status', $page->status ?? '') == 'Active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="In-Active"
                                                    {{ old('status', $page->status ?? '') == 'In-Active' ? 'selected' : '' }}>
                                                    In-Active</option>
                                            </select>
                                            @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-item">
                                <div class="row justify-content-end">
                                    <div class="col-md-8 d-flex gap-3">
                                        <div class="d-flex justify-content-center gap-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('page.index') }}"
                                                class="tc-cancel-btn">
                                                <i class="fa fa-arrow-left"></i> {{ __('Back To List') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endsection
