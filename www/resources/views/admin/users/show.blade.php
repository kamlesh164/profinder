@extends('admin.layouts.app')
@section('title')
<title>Customer Detail</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>User Detail</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">User Detail</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> User List</a>
            <div class="row mt-4">
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="{{ \App\Helpers\StaticDataHelper::imageOrDefault($customer->image,'user') }}" class="rounded-circle profile-widget-picture">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Joined at</div>
                                    <div class="profile-widget-item-value">{{ $customer->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $customer->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $customer->phone}}</td>
                                    </tr>
                                    {{-- <tr>
                                        <td>Status</td>
                                        <td>

                                            <a href="javascript:;" onclick="manageCustomerStatus(`{{ $customer->id }}`)">
                                                <input id="status_toggle" type="checkbox" {{$customer->status == 1 ? 'checked':''}} data-toggle="toggle" data-on="Active" data-off="InActive" data-onstyle="success" data-offstyle="danger">
                                            </a>

                                        </td>
                                    </tr> --}}
                                </table>
                            </div>
                        </div>


                    </div>
                </div>


                <div class="col-12 col-md-12 col-lg-7">
                    <div class="card">
                        <form method="post" class="needs-validation" novalidate="" action="{{ route('admin.customers.update',$customer->id) }}">
                            @method('put')
                            @csrf
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $customer->name }}" name="name">
                                    </div>

                                    <div class="form-group col-6">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" value="{{ $customer->email }}" name="email">
                                    </div>

                                    <div class="form-group col-6">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $customer->phone }}" name="phone" readonly>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="">Select</option>
                                            <option value="Male" @selected($customer->gender == 'Male')>Male</option>
                                            <option value="Female" @selected($customer->gender == 'Female')>Female</option>
                                            <option value="Other" @selected($customer->gender == 'Other')>Other</option>
                                        </select>
                                    </div>

                                </div>
                                <button class="btn btn-primary" type="submit">Save Changes</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card p-2">
                        <div class="card-header">
                            <h4>User address list</h4>
                        </div>
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>House No.</th>
                                    <th>Area</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Pincode</th>
                                    <th>Address</th>
                                    <th>Is Default</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($addresses as $index => $adds)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $adds->name }}</td>
                                    <td>{{ $adds->mobile }}</td>
                                    <td>{{ $adds->house_no }}</td>
                                    <td>{{ $adds->area }}</td>
                                    <td>{{ $adds->city }}</td>
                                    <td>{{ $adds->state }}</td>
                                    <td>{{ $adds->pincode }}</td>
                                    <td>{{ $adds->address }}</td>
                                    <td><span class="badge badge-{{$adds->is_default == 1 ? 'success':'grey'}}">{{$adds->is_default == 1 ? 'Yes':'No'}}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
</div>

<script>
    "use strict";

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
