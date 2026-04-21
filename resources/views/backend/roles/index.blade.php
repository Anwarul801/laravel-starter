@extends('layouts.app')
{{--
 @Author: Anwarul
 @Date: 2025-11-17 18:12:54
 @LastEditors: Anwarul
 @LastEditTime: 2025-11-17 18:20:42
 @Description: Innova IT
 --}}

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-auto">
                    <h5>Roles</h5>
                </div>
                <div class="col-auto text-end">
                    <div class="primary-btns-wraper">
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">Add role<i class="fas fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table id="datatable-buttons" class="table text-center table-bordered">
                <thead>
                    <tr class="main_title">
                        <th width="1%">No</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>

                @foreach ($roles as $key => $role)
                    <tr>
                        <td scope="row">{{ $loop->index + 1 }}</td>
                        <td>{{ $role->name }}</td>

                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Action <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('roles.show', $role->id) }}" class="dropdown-item">Show</a>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="dropdown-item">Edit</a>
                                    <button class="dropdown-item text-danger deleteBtn" data-id="{{ $role->id }}">
                                        {{ __('Delete') }}
                                    </button>
                                    <form id="delete-form-{{ $role->id }}"
                                        action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </div> 
                        </td>  
                    </tr>
                @endforeach

            </table>

            <div class="d-flex">
                {!! $roles->links() !!}
            </div>
        </div>
    </div>
@endsection
