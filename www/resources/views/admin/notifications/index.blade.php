@extends('admin.layouts.app')
@section('title')
<title>Notifications</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Notifications</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Notifications</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="#" data-toggle="modal" data-target="#add" class="btn btn-primary float-right mb-2"><i class="fas fa-plus"></i></a>
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>SN#</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Is Published</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notis as $index => $noty)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $noty->title }}</td>
                                            <td>{{ $noty->description }}</td>
                                            <td>
                                                <a href="javascript:;" onclick="changeStatus(`{{ $noty->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{$noty->is_published == 1 ? 'checked' : ''}} data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="text-danger" onclick="deleteData(`{{ $noty->id }}`)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Create Notification</h5>
            </div>
            <form id="bhkform" action="{{route('admin.notification.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" id="description" required></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">For User <span class="text-danger">*</span></label>
                            <select name="to_user" class="form-control" id="" required>
                                <option value="1">Customer</option>
                                <option value="2">Vendor</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="">Is Published ? <span class="text-danger">*</span></label>
                            <select name="is_published" class="form-control" required>
                                <option value="0">Yes</option>
                                <option value="1">No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    "use strict";

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/notification") }}'+ '/' + id)
    }

    function changeStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/notification/status')}}" +'/'+ id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
