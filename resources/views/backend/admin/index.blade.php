@extends('layouts.app')
{{--
 @Author: Anwarul
 @Date: 2025-11-17 18:12:54
 @LastEditors: Anwarul
 @LastEditTime: 2026-01-24 14:56:48
 @Description: Innova IT
--}}

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-auto">
                    <h5 class="mb-0"> Admins </h5>
                </div>
                <div class="col-auto text-end">
                    <div class="primary-btns-wraper">
                        <a href="{{ route('admin.create') }}" class="btn btn-primary">Add new user<i class="fas fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row g-3">
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="input_wrap">
                            <input type="text" class="form-control" name="name" value="{{ $request->name }}"
                                placeholder="">
                            <div class="label_text">Enter Name</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="input_wrap">
                            <input type="text" class="form-control" name="email" value="{{ $request->email }}"
                                placeholder="">
                            <div class="label_text">Enter Email</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="input_wrap">
                            <input type="text" class="form-control" name="phone" value="{{ $request->phone }}">
                            <div class="label_text">Enter Phone </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="search-btns-wrap">
                            <button class="search-btn"><i class="ri-search-line"></i> Search</button>
                            <a href="{{ route('admin.index') }}" class="reset-btn"><i class="mdi mdi-restart"></i>Reset</a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive min-h-400">
                <table class="table text-center table-striped table-bordered">
                    <thead>
                        <tr class="main_title">
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $user)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td> <span class="badge bg-primary">
                                        {{ $user->roles->pluck('name')->implode(', ') ?: 'No Role' }}
                                    </span></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action <i class="mdi mdi-chevron-down"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('admin.show', $user->id) }}" class="dropdown-item">Show</a>
                                            <a href="{{ route('admin.edit', $user->id) }}" class="dropdown-item">Edit</a> 
                                            <button class="dropdown-item text-danger deleteBtn"
                                                data-id="{{ $user->id }}">
                                                {{ __('Delete') }}
                                            </button>
                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('admin.destroy', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </div> 
                                </td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex">
                {!! $admins->links() !!}
            </div>
        </div>
    </div>
@endsection
