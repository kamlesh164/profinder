@extends('admin.layouts.app')
@section('title')
<title>Cities</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Cities</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Cities</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.cities.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>State</th>
                                            <th>Name</th>
                                            <th>Pincode</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cities as $index => $city)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $city->state->name }}</td>
                                            <td>{{ $city->name }}</td>
                                            <td>{{ $city->code }}</td>
                                            <td>
                                                <a href="{{ route('admin.cities.edit',$city->id )}}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                @if ($city->user->count() == 0 || $city->services->count() == 0)
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $city->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
<div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                {{__('You can not delete this city. Because there are one or more users/services has been created in this city.')}}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>
<script>
    "use strict";

    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/cities/") }}' + "/" + id)
    }
</script>
@endsection
