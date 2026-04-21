@extends('layouts.app')
{{--
 @Author: Anwarul
 @Date: 2025-11-17 18:12:54
 @LastEditors: Anwarul
 @LastEditTime: 2025-11-17 18:19:58
 @Description: Innova IT
 --}}

@section('content')

<div class="card">
    <div class="card-header">
        <div class="row g-2 align-items-center justify-content-between">
            <div class="col-auto">
                <h5>Permissions</h5>
            </div>
            <div class="col-auto text-end">
                <div class="primary-btns-wraper">
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary">Add permissions <i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table id="datatable-buttons" class="table text-center table-bordered mt-0">
            <thead>
                <tr class="main_title">
                    <th scope="col" width="15%">Name</th>
                    <th scope="col">Guard</th>
                    <th scope="col" colspan="3" width="1%">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->guard_name }}</td>

                    <td>
                        <a href="{{ route('permissions.edit', $permission->id) }}"
                           class="btn btn-outline-info btn-sm">
                            Edit
                        </a>
                    </td>

                    <td>
                        <form action="{{ route('permissions.destroy', $permission->id) }}"
                              method="POST"
                              style="display:inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="ms-1 btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Are you sure to delete this permission?')">
                                Delete
                            </button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection
