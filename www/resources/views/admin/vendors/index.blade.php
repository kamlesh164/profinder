@extends('admin.layouts.app')
@section('title')
<title>Vendors List</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Vendors List</h1>
        </div>
        <div class="section-body">
             <form action="{{route('admin.vendor.upload.excel')}}" enctype="multipart/form-data" method="post">
                @csrf
                <label for="">Excel File</label>
                <input type="file" name="excel_file" class="form-control" required>
                <button class="btn btn-sm btn-primary">Submit</button>
            </form>
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
                                            <th>Vendor Type</th>
                                            <th>Kyc</th>
                                            <th>Is Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1; @endphp
                                       @foreach ($vendors as $vendor)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $vendor->name }}</td>
                                            <td>{{ $vendor->email}}</td>
                                            <td>{{ $vendor->phone}}</td>
                                            <td>{{$vendor->vendor_type == 1 ? 'Manufacturer' : ($vendor->vendor_type == 2 ? 'Supplier' : 'Dealer')}}</td>
                                            <td>
                                                @if(@$vendor->kycDetail() && @$vendor->kycDetail->status == 0)
                                                <span class="badge badge-warning">Pending</span>
                                                @elseif(@$vendor->kycDetail->status == 1)
                                                <span class="badge badge-success">Approved</span>
                                                @elseif(@$vendor->kycDetail->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                                @else
                                                <span class="badge badge-danger">Not Submit</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="manageVendorStatus(`{{ $vendor->id }}`)">
                                                    <input id="status_toggle" type="checkbox" {{@$vendor->is_active == 1 ? 'checked':''}} data-toggle="toggle" data-on="Active" data-off="Inctive" data-onstyle="success" data-offstyle="danger">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.vendors.show',@$vendor->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData(`{{ @$vendor->id }}`)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                        </tr> 
                                        @php $i++ @endphp
                                        @endforeach 
                                    </tbody>
                                </table>
                                <div class="mt-4">
                                    {{ $vendors->links('pagination::bootstrap-5') }}
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
        $("#deleteForm").attr("action", '{{ url("admin/vendors") }}' + "/" + id)
    }

    function manageVendorStatus(id) {
        $.ajax({
            type: "put",
            data: {
                _token: '{{ csrf_token() }}'
            },
            url: "{{url('/admin/vendor/status')}}" + "/" + id,
            success: function(response) {
                toastr.success(response)
            },
            error: function(err) {


            }
        })
    }
</script>
@endsection
