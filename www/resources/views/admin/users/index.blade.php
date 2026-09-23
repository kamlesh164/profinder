@extends('admin.layouts.app')
@section('title')
<title>Users List</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Users List</h1>
        </div>
        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            {{-- <th>User Type</th> --}}
                                            {{-- <th>Is Active</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email}}</td>
                                            <td>{{ $user->phone}}</td>
                                            {{-- <td>{{$user->user_type == 2 ? 'Contractor' : ($user->user_type == 3 ? 'Builder' : 'Indivisual')}}</td>
                                            <td>
                                                <a href="javascript:;" onclick="manageCustomerStatus(`{{ $user->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{$user->status == 1 ? 'checked':''}} data-toggle="toggle" data-on="Active" data-off="Inctive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td> --}}
                                            <td>
                                                <a href="{{ route('admin.customers.show',$user->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-4">
                                    {{ $users->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    "use strict";

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/customers") }}' + "/" + id)
    }

    function manageCustomerStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/customer/status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
