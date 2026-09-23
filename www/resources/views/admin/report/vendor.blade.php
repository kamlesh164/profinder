@extends('admin.layouts.app')
@section('title')
<title>Report</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Vendor Report</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Vendor Report</div>
            </div>
        </div>

        <div class="section-body">

            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.vendor.report.get')}}" method="get">
                        <div class="row">
                            <div class="form-group col-3">
                                <label for="report_type">Report Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="report_type" id="report_type" required>
                                    <option value="1">Service Commission</option>
                                    <option value="2">Product</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="vendor_id">Select Vendor <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="vendor_id" id="vendor_id" required>
                                    <option value="all">All</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label for="vendor_id">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="form-group col-2">
                                <label for="vendor_id">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>
                            <div class="col-2 mt-4">
                                <button class="btn btn-primary">Filter</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Amount| Commission</th>
                                            <th>@if(@$datas) {{@$datas->sum('admin_commission')}} @endif</th>
                                            <th><button class="btn btn-sm btn-success">Export Excel</button></th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Amount</th>
                                            <th>Commission</th>
                                            <th>Order Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(@$datas)
                                        @foreach($datas as $data)
                                        <tr>
                                            <td></td>
                                            <td>{{$data->total_amount}}</td>
                                            <td>{{$data->admin_commission}}</td>
                                            <td>{{$data->created_at->format('d M Y')}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
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
    "use strict"
</script>
@endsection
