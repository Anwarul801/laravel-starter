@extends('layouts.app')
@section('page_title')
    District
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
                    <h5>{{ __('Manage District') }}</h5>
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
                    <label>{{ __('Division') }}</label>
                    <select form="search_form" name="division_id" class="form-control">
                        <option value="" disabled selected>Search By Division</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ $request->division_id == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
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
                    <a href="{{ route('district.index') }}" class="btn btn-outline-danger">Reset</a>
                </div>
            </div>

            <table class="table text-center" style="width: 100%;">
                <thead>
                <tr class="main_title">
                    <th>#</th>
                    <th>{{ __('Division') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Bangla Name') }}</th>
                    <th>{{ __('Latitude') }}</th>
                    <th>{{ __('Longitude') }}</th>
                    <th>{{ __('Code') }}</th>
                    <th class="text-center">{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($districts as $district)
                    <tr>
                        <td>{{ ($districts->currentpage() - 1) * $districts->perpage() + $loop->iteration }}</td>
                        <td>{{ $district->division->name ?? 'N/A' }}</td>
                        <td>{{ $district->name }}</td>
                        <td>{{ $district->bn_name ?? 'N/A' }}</td>
                        <td>{{ $district->lat ?? 'N/A' }}</td>
                        <td>{{ $district->lon ?? 'N/A' }}</td>
                        <td>{{ $district->code ?? 'N/A' }}</td>

                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown">Action</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-bs-toggle="modal"
                                       data-bs-target="#editModal{{ $district->id }}">Edit</a>
                                    <form action="{{ route('district.destroy', $district->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Are you sure to Delete?')">Delete</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Edit Modal --}}
                            <div id="editModal{{ $district->id }}" class="modal fade text-start" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit District</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('district.update', $district->id) }}" method="post">
                                                @method('put')
                                                @csrf
                                                <div class="mb-3">
                                                    <label>Division*</label>
                                                    <select name="division_id" class="form-control" required>
                                                        <option value="" disabled selected>Select Division</option>
                                                        @foreach($divisions as $division)
                                                            <option value="{{ $division->id }}" {{ $district->division_id == $division->id ? 'selected' : '' }}>
                                                                {{ $division->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Name*</label>
                                                    <input type="text" name="name" value="{{ $district->name }}" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Bangla Name</label>
                                                    <input type="text" name="bn_name" value="{{ $district->bn_name }}" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Latitude</label>
                                                    <input type="text" name="lat" value="{{ $district->lat }}" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Longitude</label>
                                                    <input type="text" name="lon" value="{{ $district->lon }}" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>URL</label>
                                                    <input type="text" name="url" value="{{ $district->url }}" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Code</label>
                                                    <input type="text" name="code" value="{{ $district->code }}" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="1" {{ $district->status == 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ $district->status == 0 ? 'selected' : '' }}>In-Active</option>
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
            {{ $districts->appends(request()->input())->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title">Create District</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('district.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label>Division*</label>
                            <select name="division_id" class="form-control" required>
                                <option value="" disabled selected>Select Division</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Name*</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Bangla Name</label>
                            <input type="text" name="bn_name" value="{{ old('bn_name') }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Latitude</label>
                            <input type="text" name="lat" value="{{ old('lat') }}" class="form-control" placeholder="e.g., 23.8103">
                        </div>
                        <div class="mb-3">
                            <label>Longitude</label>
                            <input type="text" name="lon" value="{{ old('lon') }}" class="form-control" placeholder="e.g., 90.4125">
                        </div>
                        <div class="mb-3">
                            <label>URL</label>
                            <input type="text" name="url" value="{{ old('url') }}" class="form-control" placeholder="https://example.com">
                        </div>
                        <div class="mb-3">
                            <label>Code</label>
                            <input type="text" name="code" value="{{ old('code') }}" class="form-control" placeholder="e.g., DHA">
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

    <script>
        function confirmStatusChange(selectElement) {
            var selectedValue = selectElement.value;
            var statusText = selectedValue == '1' ? 'Active' : 'In-Active';

            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure you want to change the status to " + statusText + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0eb041',
                cancelButtonColor: '#ff0000',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    selectElement.form.submit();
                } else {
                    window.location.reload();
                }
            });
        }
    </script>
@endsection
