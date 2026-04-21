@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5>{{ ucfirst($role->name) }} Role</h5>
        </div>
        <div class="card-body">
            <h5 class="card-title">Assigned permissions</h5>

            <table class="table table-striped table-bordered ">
                <thead>
                    <tr class="main_title">
                        <th scope="col" width="20%">Name</th>
                        <th scope="col" width="1%">Guard</th>
                    </tr>
                </thead>

                @foreach($rolePermissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->guard_name }}</td>
                    </tr>
                @endforeach
            </table>
            <div class="mt-4">
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-info">Edit</a>
                <a href="{{ route('roles.index') }}" class="btn btn-default">Back</a>
            </div>
        </div>
    </div>













@endsection
