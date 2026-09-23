@extends('admin.layouts.app')
@section('title')
<title>Kyc Pending List</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kyc Pending List</h1>
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
                                            <th>VendorId</th>
                                            <th>Vendor Name</th>
                                            <th>Kyc status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kycs as $index => $kyc)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $kyc->vendor->id }}</td>
                                            <td>{{ $kyc->vendor->name}}</td>
                                            <td>
                                                @if($kyc->status == 0)
                                                <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.vendor.kyc.details',$kyc->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-4">
                                    {{ $kycs->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
@endsection
