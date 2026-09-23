@extends('admin.layouts.app')
@section('title')
<title>Sliders</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Sliders</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Sliders</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
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
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sliders as $index => $slider)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $slider->name }}</td>
                                            <td>
                                                <img src="{{ asset($slider->image) }}" class="w_80" alt="">
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="changeSliderStatus(`{{ $slider->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{$slider->is_active == 1 ? 'checked':''}} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.sliders.edit',$slider->id )}}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(`{{ $slider->id }}`)"><i class="fa fa-trash" aria-hidden="true"></i></a>
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

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/sliders/") }}' + "/" + id)
    }

    function changeSliderStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/slider-status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
