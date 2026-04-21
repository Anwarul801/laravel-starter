@extends('layouts.app')
@section('page_title')
    Division
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
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>{{ __('Manage Division') }}</h5>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-outline-info waves-effect waves-light"
                            data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fa fa-plus-circle"></i> {{ __('Add New') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body form-body">
            <form action="" method="get" id="search_form"></form>
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <label>{{ __('Name') }}</label>
                    <input type="text" value="{{ $request->name }}" name="name" class="form-control"
                           placeholder="Search By Name" form="search_form">
                </div>
                <div class="col-md-3 mb-2">
                    <label>{{ __('Bangla Name') }}</label>
                    <input type="text" value="{{ $request->bn_name }}" name="bn_name" class="form-control"
                           placeholder="Search By Bangla Name" form="search_form">
                </div>
                <div class="col-md-2 mb-2">
                    <label>{{ __('Country') }}</label>
                    <select form="search_form" name="country_id" class="form-control">
                        <option value="" disabled selected>Search By Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ $request->country_id == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label>{{ __('Status') }}</label>
                    <select form="search_form" name="status" class="form-control">
                        <option value="" disabled selected>Search By Status</option>
                        <option value="1" {{ $request->status == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $request->status == '0' ? 'selected' : '' }}>In-Active</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label>{{ __('Action') }}</label>
                    <br>
                    <button class="btn btn-outline-dark" form="search_form" type="submit">
                        <span class="fa fa-search"></span> Search
                    </button>
                    <a href="{{ route('division.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </div>

            <table class="table text-center" style="width: 100%;">
                <thead>
                <tr class="main_title">
                    <th>#</th>
                    <th>{{ __('Country') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Bangla Name') }}</th>
                    <th class="text-center">{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($divisions as $division)
                    <tr>
                        <td>{{ ($divisions->currentpage() - 1) * $divisions->perpage() + $loop->iteration }}</td>
                        <td>{{ $division->country->name ?? 'N/A' }}</td>
                        <td>{{ $division->name }}</td>
                        <td>{{ $division->bn_name }}</td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">Action</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-bs-toggle="modal"
                                       data-bs-target="#editModal{{ $division->id }}">Edit</a>
                                    <form action="{{ route('division.destroy', $division->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Are you sure to Delete?')">Delete</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Edit Modal --}}
                            <div id="editModal{{ $division->id }}" class="modal fade text-start" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Division</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('division.update', $division->id) }}" method="post">
                                                @method('put')
                                                @csrf
                                                <div class="mb-3">
                                                    <label>Country*</label>
                                                    <select name="country_id" class="form-control" required>
                                                        <option value="" disabled selected>Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" {{ $division->country_id == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Name*</label>
                                                    <input type="text" name="name" value="{{ $division->name }}" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Bangla Name*</label>
                                                    <input type="text" name="bn_name" value="{{ $division->bn_name }}" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="1" {{ $division->status == 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ $division->status == 0 ? 'selected' : '' }}>In-Active</option>
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-info">Save</button>
                                        </div>
                                        </form>
                                    </div>
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
            {{ $divisions->appends(request()->input())->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Create Division</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('division.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label>Country*</label>
                            <select name="country_id" class="form-control" required>
                                <option value="" disabled selected>Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Name*</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Bangla Name*</label>
                            <input type="text" name="bn_name" value="{{ old('bn_name') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-info">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>


@endsection
