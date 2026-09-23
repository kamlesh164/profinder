@extends('admin.layouts.app')
@section('title')
<title>Coupons</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Coupons</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Coupon</div>
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
                                            <th>Coupon Code</th>
                                            <th>Value</th>
                                            <th>Type</th>
                                            <th>Coupon Credit</th>
                                            <th>Expiry</th>
                                            <th>Is Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupans as $index => $coupan)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $coupan->coupan_code }}</td>
                                            <td>{{ $coupan->value }}</td>
                                            <td>{{ $coupan->type == 1 ? 'Percentage':'Fix' }}</td>
                                            <td>{{ $coupan->coupan_for == 1 ? 'Cashback':'Flat' }}</td>
                                            <td>{{ $coupan->expires_at == null ? 'N/A' : ($coupan->expires_at < Carbon\Carbon::now() ? 'Expired': $coupan->expires_at) }}</td>
                                            <td>
                                                <a href="javascript:;" onclick="changeStatus(`{{ $coupan->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{$coupan->is_active == 1 ? 'checked' : ''}} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#update" onclick="UpdateData(`{{$coupan->id}}`,`{{$coupan->coupan_code}}`,`{{$coupan->value}}`,`{{$coupan->expires_at}}`,`{{$coupan->type}}`,`{{$coupan->coupan_for}}`,`{{$coupan->is_active}}`)" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(`{{ $coupan->id }}`)"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                <h5>Create Coupon</h5>
            </div>
            <form id="coupanForm" action="{{route('admin.coupan.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="coupan_code" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="">Coupon Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="1">Percentage</option>
                                <option value="2">Fix</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Value <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="value" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="">Coupon Credit <span class="text-danger">*</span></label>
                            <select name="coupan_for" class="form-control" required>
                                <option value="1">Cashback</option>
                                <option value="2">Flat</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Expiry Date </label>
                            <input type="text" class="form-control datepicker" name="expires_at">
                        </div>
                        <div class="form-group col-6">
                            <label for="">Status <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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
<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Update Coupon</h5>
            </div>
            <form id="updatecoupanForm" action="" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="coupan_code" name="coupan_code" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="">Coupon Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" id="type" required>
                                <option value="1">Percentage</option>
                                <option value="2">Fix</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Value <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="value" name="value" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="">Coupon Credit <span class="text-danger">*</span></label>
                            <select name="coupan_for" class="form-control" id="coupan_for" required>
                                <option value="1">Cashback</option>
                                <option value="2">Flat</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Expiry Date </label>
                            <input type="text" class="form-control datepicker" id="expires_at" name="expires_at">
                        </div>
                        <div class="form-group col-6">
                            <label for="">Status <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control" id="is_active" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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

    function deleteData(id, formtype) {
        $("#deleteForm").attr("action", '{{ url("admin/coupan") }}' + '/' + id)
    }

    function UpdateData(id,code,avalue,expiry,coupantype,coupanfor,status) {
        $("#updatecoupanForm").attr("action", '{{ url("admin/coupan") }}' + '/' + id);
        $('#coupan_code').val(code);
        $('#value').val(avalue);
        $('#expires_at').val(expiry);
        $('#type').val(coupantype);
        $('#coupan_for').val(coupanfor);
        $('#is_active').val(status);
    }

    function changeStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/coupan/status')}}" +'/'+ id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
