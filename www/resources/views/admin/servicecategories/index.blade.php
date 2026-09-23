@extends('admin.layouts.app')
@section('title')
<title>Service Category</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Service Category</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Service Category</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.servicecategory.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
                                            <th>Commission(%)</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $index => $category)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->commission }}</td>
                                            <td>
                                                <img src="{{ asset($category->image) }}" class="w_80" alt="">
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="changeServiceCategoryStatus(`{{ $category->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{$category->is_active == 1 ? 'checked' : ''}} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.servicecategory.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>

                                                @if ($category->service->count() == 0)
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(`{{ $category->id }}`)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @else
                                                <a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @endif
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

<!-- Modal -->
<div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                You can not delete this service category. Because there are one or more services has been created in this category.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/servicecategory") }}' + "/" + id)
    }

    function changeServiceCategoryStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/servicecategory/status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
