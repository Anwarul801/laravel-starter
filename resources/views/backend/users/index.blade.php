@extends('layouts.app')
{{--
 @Author: Anwarul
 @Date: 2025-11-17 18:12:54
 @LastEditors: Anwarul
 @LastEditTime: 2026-04-07 18:05:16
 @Description: Innova IT
--}}

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-auto">
                    <h5 class="mb-0"> Users </h5>
                </div>
                <div class="col-auto text-end">
                    <div class="primary-btns-wraper">
                        <a href="{{ route('users.create') }}" class="btn btn-primary">Add new user <i
                                class="fas fa-plus"></i></a>
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
                            <input type="text" class="form-control" name="phone" value="{{ $request->phone }}"
                                placeholder="">
                            <div class="label_text">Enter Phone</div>
                        </div>

                    </div>

                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="input_wrap">
                            <select class="form-control" name="date_filter" id="dateFilterSelect">
                                <option value="">-- Select Period --</option>
                                <option value="today" {{ $request->date_filter == 'today' ? 'selected' : '' }}>Today
                                </option>
                                <option value="this_week" {{ $request->date_filter == 'this_week' ? 'selected' : '' }}>This
                                    Week</option>
                                <option value="this_month" {{ $request->date_filter == 'this_month' ? 'selected' : '' }}>
                                    This Month</option>
                                <option value="custom" {{ $request->date_filter == 'custom' ? 'selected' : '' }}>Custom
                                    Range</option>
                            </select>
                            <div class="label_text">Register Date</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-4 col-sm-6 custom-date-range"
                        style="{{ $request->date_filter == 'custom' ? '' : 'display:none;' }}">
                        <div class="input_wrap">
                            <input type="date" class="form-control" name="start_date" value="{{ $request->start_date }}">
                            <div class="label_text">Start Date</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-4 col-sm-6 custom-date-range"
                        style="{{ $request->date_filter == 'custom' ? '' : 'display:none;' }}">
                        <div class="input_wrap">
                            <input type="date" class="form-control" name="end_date" value="{{ $request->end_date }}">
                            <div class="label_text">End Date</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="search-btns-wrap">
                            <button class="search-btn"><i class="ri-search-line"></i> Search</button>
                            <a href="{{ route('users.index') }}" class="reset-btn"><i class="mdi mdi-restart"></i>Reset</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive min-h-400">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr class="main_title">
                            <th>#</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Join</th>
                            <th class="text-center">Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                {{--

                                <td>
                                    @if (!empty($user->phone))
                                        <strong>Phone:</strong> {{ $user->phone }}<br>
                                    @endif

                                    @if (!empty($user->email))
                                        <strong>Email:</strong> {{ $user->email }}
                                    @endif
                                </td>
                                <td>
                                    @if ($user->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                {{--
                                <td>{{ $user->devices->count() ?? '' }} - <a href="{{ route('users.show', $user->id) }}"
                                        class="btn btn-warning btn-sm">Show</a></td> --}}
                                <td>
                                    {{ $user->created_at->format('d M Y') }} <br>
                                    {{ $user->created_at->format('h:i A') }}
                                </td>


                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action <i class="mdi mdi-chevron-down"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('users.show', $user->id) }}" class="dropdown-item">Show</a>
                                            <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">Edit</a>
                                            <a href="{{ route('users.index') }}?became_affiliate={{ $user->id }}" class="dropdown-item">Became Affiliate</a>
                                            <button class="dropdown-item text-danger deleteBtn"
                                                data-id="{{ $user->id }}">
                                                {{ __('Delete') }}
                                            </button>


                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a target="_blank" rel="noopener noreferrer"
                                        href="{{ route('users.login-as', $user->id) }}" class="btn btn-primary"
                                        onclick="return confirm('{{ $user->name }} হিসেবে login করবেন?')">
                                        <i class="mdi mdi-login"></i> Login Frontend
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex">
                {!! $users->links() !!}
            </div>
        </div>
    </div>

    <script>
        document.getElementById('dateFilterSelect').addEventListener('change', function() {
            const customFields = document.querySelectorAll('.custom-date-range');
            customFields.forEach(el => {
                el.style.display = this.value === 'custom' ? '' : 'none';
            });
        });
    </script>
@endsection
