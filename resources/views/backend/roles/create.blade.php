@extends('layouts.app')

@section('content')


    <div class="card">
        <div class="card-header">            
            <h5>Add new role</h5>
        </div>
        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input value="{{ old('name') }}"
                           type="text"
                           class="form-control"
                           name="name"
                           placeholder="Name" required>
                </div>

                <label for="permissions" class="form-label">Assign Permissions</label>

                <table class="table">
                    <thead>
                    <tr class="main_title">
                        <th scope="col" width="1%"><input type="checkbox" id="all_permission" name="all_permission"></th>
                    <th scope="col" width="20%">Name</th>
                    <th scope="col" width="1%">Guard</th>
                    </tr>
                    </thead>

                    @foreach($permissions as $permission)
                        <tr>
                            <td>
                                <input type="checkbox"
                                       name="permission[{{ $permission->name }}]"
                                       value="{{ $permission->name }}"
                                       class='permission'>
                            </td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                        </tr>
                    @endforeach
                </table>

                <button type="submit" class="btn btn-primary">Save user</button>
                <a href="{{ route('users.index') }}" class="btn btn-default">Back</a>
            </form>
        </div>
    </div>
    <script src="{{ asset('backend/js/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#all_permission").click(function() {
                $(".permission").prop("checked", this.checked);
            });
            $('.permission').click(function() {
                if ($('.permission:checked').length == $('.permission').length) {
                $('#all_permission').prop('checked', true);
                } else {
                $('#all_permission').prop('checked', false);
                }
            });
            });
    </script>
@endsection

@section('scripts')

@endsection
