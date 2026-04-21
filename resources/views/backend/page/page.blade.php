@extends('layouts.app')
@section('page_title')
    Pages
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
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-auto">
                    <h5>{{ __('Manage Pages') }}</h5>
                </div>
                <div class="col-auto text-end">
                    <a href="{{ route('page.create') }}" class="btn btn-primary"> {{ __('Add New') }} <i
                            class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body form-body">
            <form action="" method="get" id="search_form"></form>
            <div class="row g-3">
                <div class="col-xl-4 col-sm-6">
                    <div class="input_wrap">
                        <input type="text" value="{{ $request->title }}" name="title" class="form-control"
                            placeholder="Search By Title" form="search_form">
                        <div class="label_text">{{ __('Title') }}</div>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6">
                    <div class="input_wrap">
                        <select form="search_form" name="status" class="form-control">
                            <option value="" disabled selected>Search By Status</option>
                            <option value="Active" {{ $request->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="In-Active" {{ $request->status == 'In-Active' ? 'selected' : '' }}>In-Active
                            </option>
                        </select>
                        <div class="label_text">{{ __('Status') }}</div>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6">
                    <div class="search-btns-wrap">
                        <button class="search-btn" form="search_form" type="submit">
                            <span class="fa fa-search"></span> Search
                        </button>
                        <a href="{{ route('page.index') }}" class="reset-btn">Reset</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive min-h-400">
                <table class="table table-bordered table-striped text-center" style="width:100%">
                    <thead>
                        <tr class="main_title">
                            <th>#</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Page Link') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        @forelse($pages as $page)
                            <tr>
                                <td>
                                    {{ ($pages->currentPage() - 1) * $pages->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $page->title ?? 'N/A' }}</td>
    
                                <td>
                                    <button data-url="{{ route('page_view', $page->slug) }}"
                                        class="btn btn-outline-dark copy-btn btn-sm"><i class=" ri-links-line"></i> Copy</button>
                                </td>
    
                                <td>
                                    <span class="badge bg-{{ $page->status === 'Active' ? 'success' : 'danger' }}">
                                        {{ $page->status }}
                                    </span>
                                </td>
    
                                {{-- Action --}}
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            Action
                                        </button>
    
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('page.edit', $page->id) }}">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            @if ($page->deletable == 1)
                                                <form action="{{ route('page.destroy', $page->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
    
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Are you sure to delete?')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
    
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100">No Data Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $pages->appends(request()->input())->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.copy-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');

                    navigator.clipboard.writeText(url).then(() => {
                        // Optional feedback
                        this.innerHTML = '<i class="ri-check-line"></i> Copied';
                        this.classList.remove('btn-outline-dark');
                        this.classList.add('btn-success');

                        setTimeout(() => {
                            this.innerHTML = '<i class="ri-links-line"></i> Copy';
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-dark');
                        }, 1500);
                    }).catch(() => {
                        alert('Copy failed!');
                    });
                });
            });
        });
    </script>
@endsection
