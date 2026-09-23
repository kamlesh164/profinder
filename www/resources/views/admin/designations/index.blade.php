@extends('admin.layouts.app')
@section('title')
<title>Designations</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Designations</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Designations</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.designations.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($designations as $index => $desig)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $desig->name }}</td>
                                            <td>
                                                <a href="javascript:;" onclick="changeDesigStatus(`{{ $desig->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{$desig->status == 1 ? 'checked':''}} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.designations.edit',$desig->id )}}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
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


<script>
    "use strict";

    function changeDesigStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/designations/status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
